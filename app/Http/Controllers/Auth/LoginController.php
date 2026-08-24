<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // ─── View Methods ────────────────────────────────────────────────────────

    public function showLoginForm()
    {
        return view('auth.login', ['role' => 'student']);
    }

    public function showInstructorLoginForm()
    {
        return view('auth.login', ['role' => 'instructor']);
    }

    public function showAdminLoginForm()
    {
        return view('auth.login', ['role' => 'admin']);
    }

    // ─── Student Login ────────────────────────────────────────────────────────

    public function loginStudent(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            // Always respect the portal they chose: set active_role = student
            session(['active_role' => 'student']);

            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    // ─── Instructor Login ─────────────────────────────────────────────────────

    public function loginInstructor(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $user = Auth::user();

            // Pure student accounts cannot use instructor portal
            if ($user->role === 'student') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                throw ValidationException::withMessages([
                    $this->username() => ['This account is a Student account. Please use the Student Sign In page.'],
                ]);
            }

            // Always respect the portal they chose: set active_role = instructor
            session(['active_role' => 'instructor']);

            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    // ─── Admin Login ──────────────────────────────────────────────────────────

    public function loginAdmin(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $user = Auth::user();

            // Only admin accounts can use admin portal
            if ($user->role !== 'admin') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                throw ValidationException::withMessages([
                    $this->username() => ['Access denied. This portal is for administrators only.'],
                ]);
            }

            session(['active_role' => 'admin']);

            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    // ─── Post-Auth Redirect ───────────────────────────────────────────────────

    protected function authenticated(Request $request, $user)
    {
        $activeRole = session('active_role');

        if ($activeRole === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($activeRole === 'instructor') {
            return redirect()->route('instructor.dashboard');
        }

        if ($activeRole === 'student') {
            return redirect()->route('student.dashboard');
        }

        // Fallback based on database role (no session set)
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'instructor') {
            return redirect()->route('instructor.dashboard');
        }

        return redirect()->route('student.dashboard');
    }
}
