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

        $pendingEnrollments = \App\Models\Enrollment::with(['user', 'course'])
            ->whereIn('course_id', $courses->pluck('id'))
            ->where('payment_status', 'pending')
            ->latest()
            ->take(15)
            ->get();

        return view('instructor-dashboard', compact('user', 'courses', 'totalStudents', 'totalEarned', 'pendingPayout', 'pendingSubmissionsCount', 'pendingEnrollments'));
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


        // 1. Notify all registered Admins (both in-app and email)
        \App\Models\AppNotification::notifyAdmins(
            'payout',
            '💳 Payout Requested',
            "Instructor {$user->name} has requested a payout to {$user->bank_name} ({$user->account_number}).",
            route('admin.dashboard'),
            'fa-wallet',
            'green'
        );

        // 2. Send confirmation to Instructor
        \App\Models\AppNotification::notify(
            $user->id,
            'payout',
            'Payout Request Received 💳',
            "Your payout request to {$user->bank_name} ({$user->account_number}) has been received and is being processed by administration.",
            route('instructor.dashboard'),
            'fa-money-bill-wave',
            'green'
        );

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

    /**
     * Request account deletion.
     */
    public function requestAccountDeletion(Request $request)
    {
        $user = Auth::user();
        $reason = $request->input('reason', 'User submitted account deletion request via Settings.');

        // 1. Create Inbound Message record in database
        \App\Models\InboundMessage::create([
            'user_id' => $user->id,
            'name'    => $user->name,
            'email'   => $user->email,
            'subject' => "⚠️ Account Deletion Request — {$user->name} ({$user->email})",
            'message' => "User: {$user->name}\nEmail: {$user->email}\nRole: " . ucfirst($user->role) . " (ID: #{$user->id})\nRequested At: " . now()->format('d M Y, h:i A') . "\n\nReason given:\n" . ($reason ?: 'No specific reason provided.') . "\n\nPlease review and process according to platform policy.",
            'status'  => 'unread',
        ]);

        // 2. In-App and Email Notification to all registered Admins
        \App\Models\AppNotification::notifyAdmins(
            'support',
            "⚠️ Account Deletion Request: {$user->name}",
            "User {$user->email} has requested permanent account deletion. Reason: " . ($reason ?: 'None provided'),
            route('admin.dashboard'),
            'fa-user-slash',
            'red'
        );

        // 3. In-App Notification to the requesting User
        \App\Models\AppNotification::notify(
            $user->id,
            'system',
            "Account Deletion Request Received",
            "We have received your account deletion request. Our support team will review and process it.",
            route('user.inbox'),
            'fa-clock',
            'amber'
        );

        // 4. Trigger Email to Admin
        try {
            $adminEmail = config('mail.from.address') ?: 'learnerium@jlm.com.ng';
            $adminName  = config('mail.from.name') ?: 'Learnerium Support';

            \Illuminate\Support\Facades\Mail::send('emails.admin_broadcast', [
                'recipient' => (object)['name' => $adminName, 'email' => $adminEmail],
                'subject'   => "⚠️ Urgent: Account Deletion Request — {$user->name}",
                'content'   => "User {$user->name} ({$user->email}, Role: " . ucfirst($user->role) . ", ID: #{$user->id}) has requested permanent account deletion.\n\nReason:\n{$reason}\n\nPlease log in to the admin dashboard to review and manage this user's account.",
            ], function ($m) use ($adminEmail, $adminName, $user) {
                $m->to($adminEmail, $adminName)
                  ->subject("⚠️ Urgent: Account Deletion Request — {$user->name}");
            });
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Could not send account deletion email: " . $e->getMessage());
        }

        return back()->with('status', 'Your account deletion request has been received. Our team will review and process it shortly.');
    }
}
