<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth facade
use App\Models\User; // Import User model if not already

class DashboardController extends Controller
{
    /**
     * Show the main dashboard based on user role.
     * This method is typically called by the /dashboard route.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
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
     *
     * @return \Illuminate\View\View
     */
    public function studentDashboard()
    {
        $user = Auth::user();
        $enrolledCourses = $user->coursesEnrolled()->withPivot('progress_percentage')->get();

        return view('student-dashboard', compact('user', 'enrolledCourses'));
    }

    /**
     * Show the instructor dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function instructorDashboard()
    {
        $user = Auth::user();
        $courses = $user->coursesTaught()->with(['lessons', 'enrollments'])->get();
        $totalStudents = $courses->sum(fn($c) => $c->enrollments->count());

        return view('instructor-dashboard', compact('user', 'courses', 'totalStudents'));
    }

    // You can add other dashboard-related methods here as needed
    // public function parseAbilityAndArguments() { /* ... */ } // This was a suggestion by Laravel, not needed for now
}
