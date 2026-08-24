<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the student login form.
     */
    public function showLoginForm()
    {
        return view('auth.login', ['role' => 'student']);
    }

    /**
     * Show the instructor login form.
     */
    public function showInstructorLoginForm()
    {
        return view('auth.login', ['role' => 'instructor']);
    }

    /**
     * Handle instructor login request.
     */
    public function loginInstructor(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $user = Auth::user();

            // Strict check: Only block if the account is purely a student and not an instructor or admin
            if ($user->role === 'student') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                throw ValidationException::withMessages([
                    $this->username() => ['This account is registered as a Student. Please use the Student Sign In page.'],
                ]);
            }

            session(['active_role' => $user->isAdmin() ? 'admin' : 'instructor']);

            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Handle student login request.
     */
    public function loginStudent(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $user = Auth::user();

            session(['active_role' => $user->isAdmin() ? 'admin' : 'student']);

            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    /**
     * The user has been authenticated.
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if (session('active_role') === 'instructor' || ($user->role === 'instructor' && !session()->has('active_role'))) {
            return redirect()->route('instructor.dashboard');
        }

        return redirect()->route('student.dashboard');
    }
}
