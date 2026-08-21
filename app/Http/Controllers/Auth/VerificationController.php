<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | Handles email verification for registered users across any device,
    | email client, or browser session.
    |
    */

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Only require auth for notice page & resend — NOT for verify link execution
        $this->middleware('auth')->only('show', 'resend');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Show the email verification notice view.
     */
    public function show(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->route('dashboard')
            : view('auth.verify');
    }

    /**
     * Verify the user's email address when they click the email link.
     */
    public function verify(Request $request)
    {
        $id = $request->route('id');
        $hash = $request->route('hash');

        $user = User::find($id);

        if (!$user) {
            return redirect()->route('login')->with('error', 'Invalid verification link. User account not found.');
        }

        // Validate hash matches sha1 of user email
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect()->route('login')->with('error', 'Invalid or expired email verification link.');
        }

        // Mark user email as verified
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        // Log the user in if not currently logged in as this user
        if (!Auth::check() || Auth::id() !== $user->id) {
            Auth::login($user);
            if ($request->hasSession()) {
                $request->session()->regenerate();
            }
        }

        $redirectRoute = $user->isInstructor() ? 'instructor.dashboard' : 'student.dashboard';

        return redirect()->route($redirectRoute)->with('status', 'Your email address has been verified successfully! Welcome to Learnerium.');
    }

    /**
     * Resend the email verification notification.
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'A new verification link has been sent to your email address.');
    }
}
