<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $user = Auth::user();

        if (!$user || !$user->isStudent()) {
            abort(403, 'Student access only.');
        }

        // If course is paid, redirect to checkout
        if ((float) $course->price > 0) {
            return redirect()->route('courses.checkout', $course)
                ->with('info', 'Please complete payment to enroll in this course.');
        }

        // Free course: enroll immediately as paid
        Enrollment::firstOrCreate(
            [
                'user_id' => $user->id,
                'course_id' => $course->id,
            ],
            [
                'progress_percentage' => 0,
                'completion_date' => null,
                'payment_status' => 'paid',
                'amount_paid' => 0.00,
            ]
        );

        return back()->with('status', 'You are now enrolled in this course!');
    }
}
