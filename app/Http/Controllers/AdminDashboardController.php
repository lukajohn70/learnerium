<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Coupon;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * Main Admin Dashboard Overview.
     */
    public function index()
    {
        $stats = [
            'total_users'       => User::count(),
            'total_students'    => User::where('role', 'student')->count(),
            'total_instructors' => User::where('role', 'instructor')->count(),
            'total_courses'     => Course::count(),
            'published_courses' => Course::whereNotNull('published_at')->count(),
            'total_enrollments' => Enrollment::count(),
            'paid_enrollments'  => Enrollment::where('payment_status', 'paid')->count(),
            'total_revenue'     => Enrollment::where('payment_status', 'paid')->sum('amount_paid'),
            'total_coupons'     => Coupon::count(),
        ];

        $recentUsers    = User::latest()->take(5)->get();
        $recentEnrolls  = Enrollment::with(['user', 'course'])->latest()->take(10)->get();
        $recentCourses  = Course::with('instructor')->latest()->take(5)->get();
        $recentCoupons  = Coupon::with('course')->latest()->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentEnrolls', 'recentCourses', 'recentCoupons'));
    }

    /**
     * Manage Users.
     */
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(25);
        return view('admin.users', compact('users'));
    }

    /**
     * Update a user's role.
     */
    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:student,instructor,admin',
        ]);

        $user->update(['role' => $request->role]);
        return back()->with('status', "Role for {$user->name} updated to {$request->role}.");
    }

    /**
     * Manage Courses.
     */
    public function courses()
    {
        $courses = Course::with(['instructor', 'enrollments'])->latest()->paginate(20);
        return view('admin.courses', compact('courses'));
    }

    /**
     * Toggle course published state.
     */
    public function toggleCoursePublish(Course $course)
    {
        if ($course->published_at) {
            $course->update(['published_at' => null]);
            $msg = "Course '{$course->title}' has been unpublished.";
        } else {
            $course->update(['published_at' => now()]);
            $msg = "Course '{$course->title}' has been published.";
        }

        return back()->with('status', $msg);
    }

    /**
     * Manage Coupons.
     */
    public function coupons()
    {
        $coupons = Coupon::with('course')->latest()->get();
        $courses  = Course::whereNotNull('published_at')->orderBy('title')->get();
        return view('admin.coupons', compact('coupons', 'courses'));
    }

    /**
     * Create a new coupon.
     */
    public function storeCoupon(Request $request)
    {
        $request->validate([
            'code'           => 'required|string|max:100|unique:coupons,code',
            'discount_type'  => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:1',
            'course_id'      => 'nullable|exists:courses,id',
            'max_uses'       => 'nullable|integer|min:1',
            'expires_at'     => 'nullable|date|after:today',
        ]);

        Coupon::create([
            'code'           => strtoupper($request->code),
            'discount_type'  => $request->discount_type,
            'discount_value' => $request->discount_value,
            'course_id'      => $request->course_id ?: null,
            'max_uses'       => $request->max_uses ?: null,
            'used_count'     => 0,
            'active'         => true,
            'expires_at'     => $request->expires_at ?: null,
        ]);

        return back()->with('status', 'Coupon created successfully!');
    }

    /**
     * Delete a coupon.
     */
    public function destroyCoupon(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('status', 'Coupon deleted.');
    }

    /**
     * View all payments / enrollments.
     */
    public function payments()
    {
        $enrollments = Enrollment::with(['user', 'course'])
            ->where('payment_status', 'paid')
            ->latest()
            ->paginate(25);

        $totalRevenue = Enrollment::where('payment_status', 'paid')->sum('amount_paid');

        return view('admin.payments', compact('enrollments', 'totalRevenue'));
    }
}
