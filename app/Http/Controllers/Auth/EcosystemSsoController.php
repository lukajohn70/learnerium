<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EcosystemSsoController extends Controller
{
    /**
     * Authenticate student seamlessly via ZAVORA HMAC handover token.
     *
     * GET /auth/ecosystem/sso?token=...
     */
    public function ssoLogin(Request $request)
    {
        $token = $request->query('token');

        if (empty($token) || strpos($token, '.') === false) {
            return redirect()->route('login.student')
                ->with('error', 'Invalid or missing ecosystem SSO token.');
        }

        $parts = explode('.', $token, 2);
        if (count($parts) !== 2) {
            return redirect()->route('login.student')
                ->with('error', 'Malformed SSO token structure.');
        }

        list($b64Payload, $signature) = $parts;

        // 1. Verify HMAC Signature
        $secret = config('services.ecosystem.api_secret') ?: env('ECOSYSTEM_API_SECRET');
        if (empty($secret)) {
            Log::error('Ecosystem SSO failed: ECOSYSTEM_API_SECRET is not configured.');
            return redirect()->route('login.student')
                ->with('error', 'SSO configuration error on learning platform.');
        }

        $expectedSig = hash_hmac('sha256', $b64Payload, $secret);
        if (!hash_equals($expectedSig, $signature)) {
            Log::warning('Ecosystem SSO failed: signature mismatch.');
            return redirect()->route('login.student')
                ->with('error', 'SSO security signature could not be verified.');
        }

        // 2. Decode and Validate Payload
        $payload = json_decode(base64_decode($b64Payload), true);
        if (!is_array($payload) || empty($payload['email'])) {
            return redirect()->route('login.student')
                ->with('error', 'Invalid SSO token contents.');
        }

        // 3. Expiration Check (Valid for 15 minutes)
        $timestamp = isset($payload['timestamp']) ? (int) $payload['timestamp'] : 0;
        if (abs(time() - $timestamp) > 900) {
            return redirect()->route('login.student')
                ->with('error', 'SSO token has expired. Please launch course from your ZAVORA account again.');
        }

        // 4. Locate or Match Student Account
        $email = strtolower(trim($payload['email']));
        $user = User::where('email', $email)->first();

        if (!$user) {
            // Auto-create student if flagged or provisioned
            $name = !empty($payload['name']) ? trim($payload['name']) : 'Learnerium Student';
            $user = User::create([
                'name'              => $name,
                'email'             => $email,
                'password'          => bcrypt(\Illuminate\Support\Str::random(16)),
                'role'              => 'student',
                'email_verified_at' => now(),
            ]);
        }

        // 5. Establish Authenticated Session
        Auth::login($user);
        session(['active_role' => 'student']);

        if ($request->hasSession()) {
            $request->session()->put('auth.password_confirmed_at', time());
            $request->session()->regenerate();
        }

        // 6. Resolve Direct Course or Portal Destination
        if (!empty($payload['course_id'])) {
            $course = Course::find((int) $payload['course_id']);
            if ($course) {
                return redirect()->route('course.detail', $course->slug)
                    ->with('success', "Welcome, {$user->name}! You have been authenticated from ZAVORA.");
            }
        }

        if (!empty($payload['course_slug'])) {
            return redirect()->route('course.detail', $payload['course_slug'])
                ->with('success', "Welcome, {$user->name}! You have been authenticated from ZAVORA.");
        }

        if (!empty($payload['redirect_to'])) {
            return redirect($payload['redirect_to'])
                ->with('success', "Welcome, {$user->name}!");
        }

        return redirect()->route('student.dashboard')
            ->with('success', "Welcome back, {$user->name}! Seamlessly signed in via ZAVORA.");
    }
}
