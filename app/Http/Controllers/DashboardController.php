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

        $totalEarned = \App\Models\Enrollment::whereIn('course_id', $courses->pluck('id'))
            ->where('payment_status', 'paid')
            ->sum('instructor_share');

        $pendingPayout = \App\Models\Enrollment::whereIn('course_id', $courses->pluck('id'))
            ->where('payment_status', 'paid')
            ->where('payout_status', 'pending')
            ->sum('instructor_share');

        $pendingSubmissionsCount = \App\Models\Submission::whereHas('task.lesson.course', function($q) use ($user) {
            $q->where('instructor_id', $user->id);
        })->where('status', 'pending')->count();

        return view('instructor-dashboard', compact('user', 'courses', 'totalStudents', 'totalEarned', 'pendingPayout', 'pendingSubmissionsCount'));
    }

    /**
     * Update instructor payout bank details.
     */
    public function updateBankDetails(Request $request)
    {
        $request->validate([
            'bank_name'      => 'required|string|max:100',
            'bank_code'      => 'nullable|string|max:20',
            'account_number' => 'required|string|size:10',
            'account_name'   => 'required|string|max:150',
        ]);

        // Auto-heal missing columns if migrations haven't run yet
        try {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'bank_name')) {
                \Illuminate\Support\Facades\Schema::table('users', function(\Illuminate\Database\Schema\Blueprint $t) {
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'bank_name')) $t->string('bank_name')->nullable();
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'bank_code')) $t->string('bank_code', 20)->nullable();
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'account_number')) $t->string('account_number')->nullable();
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'account_name')) $t->string('account_name')->nullable();
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'payout_requested_at')) $t->string('payout_requested_at')->nullable();
                });
            }
        } catch (\Throwable $e) {}

        $user = Auth::user();
        $user->update([
            'bank_name'      => $request->bank_name,
            'bank_code'      => $request->bank_code,
            'account_number' => $request->account_number,
            'account_name'   => $request->account_name,
        ]);

        return back()->with('status', 'Bank details verified and saved successfully!');
    }

    /**
     * Instructor requests a payout.
     */
    public function requestPayout()
    {
        $user = Auth::user();

        if (empty($user->bank_name) || empty($user->account_number) || empty($user->account_name)) {
            return back()->with('error', 'Please update your bank details first before requesting a payout.');
        }

        try {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'payout_requested_at')) {
                \Illuminate\Support\Facades\Schema::table('users', function(\Illuminate\Database\Schema\Blueprint $t) {
                    $t->string('payout_requested_at')->nullable();
                });
            }
        } catch (\Throwable $e) {}

        $user->update(['payout_requested_at' => now()->toDateTimeString()]);


        // Notify admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            \App\Models\AppNotification::notify(
                $admin->id,
                'payout',
                'Payout Requested 💳',
                "Instructor {$user->name} has requested a payout to {$user->bank_name} ({$user->account_number}).",
                route('admin.dashboard'),
                'fa-wallet',
                'green'
            );
        }

        return back()->with('status', 'Payout request submitted to platform administration!');
    }

    /**
     * Consolidated student submissions grading inbox for instructors.
     */
    public function instructorSubmissions()
    {
        $user = Auth::user();
        $submissions = \App\Models\Submission::whereHas('task.lesson.course', function($q) use ($user) {
            $q->where('instructor_id', $user->id);
        })->with(['task.lesson.course', 'user'])
          ->latest()
          ->paginate(25);

        return view('instructor.submissions-inbox', compact('submissions'));
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
