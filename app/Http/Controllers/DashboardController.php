<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Show the main dashboard based on user role.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->isInstructor()) {
            return redirect()->route('instructor.dashboard');
        }
        return redirect()->route('student.dashboard');
    }

    /**
     * Show the student dashboard.
     */
    public function studentDashboard()
    {
        $user = Auth::user();
        $enrolledCourses = $user->coursesEnrolled()->withPivot('progress_percentage')->get();
        return view('student-dashboard', compact('user', 'enrolledCourses'));
    }

    /**
     * Show the instructor dashboard.
     */
    public function instructorDashboard()
    {
        $user = Auth::user();
        $courses = $user->coursesTaught()->with(['lessons', 'enrollments'])->get();
        $totalStudents = $courses->sum(fn($c) => $c->enrollments->count());
        return view('instructor-dashboard', compact('user', 'courses', 'totalStudents'));
    }

    /**
     * Switch active view mode between Instructor and Student.
     */
    public function switchRole(Request $request)
    {
        $user = Auth::user();
        $targetRole = $request->input('role');
        if ($user && $user->canSwitchRole()) {
            if (in_array($targetRole, ['student', 'instructor'])) {
                session(['active_role' => $targetRole]);
                $msg = $targetRole === 'student' ? 'Switched to Student View Mode.' : 'Switched to Instructor View Mode.';
                $redirectRoute = $targetRole === 'student' ? 'student.dashboard' : 'instructor.dashboard';
                return redirect()->route($redirectRoute)->with('status', $msg);
            }
        }
        return back();
    }

    /**
     * Upload and update user profile avatar.
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $user = Auth::user();
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\._-]/', '_', $file->getClientOriginalName());
            if (!is_dir(public_path('uploads/avatars'))) {
                mkdir(public_path('uploads/avatars'), 0775, true);
            }
            $file->move(public_path('uploads/avatars'), $filename);
            $user->update(['avatar' => $filename]);
        }

        return back()->with('status', 'Profile picture updated successfully!');
    }

    /**
     * Show Settings page.
     */
    public function settings()
    {
        return view('settings');
    }

    /**
     * Update Profile Details (name & email).
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('status', 'Profile updated successfully!');
    }

    /**
     * Update Password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'current_password'      => 'required',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('status', 'Password updated successfully!');
    }
}
