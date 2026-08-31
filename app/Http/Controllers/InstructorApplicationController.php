<?php

namespace App\Http\Controllers;

use App\Models\InstructorApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstructorApplicationController extends Controller
{
    /**
     * Show the instructor application form.
     */
    public function showForm()
    {
        $user = Auth::user();

        if ($user && $user->role === 'instructor') {
            return redirect()->route('instructor.dashboard')
                ->with('info', 'You are already an approved Instructor!');
        }

        $existingApplication = $user ? $user->instructorApplication : null;

        return view('instructor-application', compact('existingApplication'));
    }

    /**
     * Handle submission of the instructor application.
     */
    public function submit(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login.student')
                ->with('error', 'Please log in to apply as an instructor.');
        }

        if ($user->role === 'instructor') {
            return redirect()->route('instructor.dashboard');
        }

        $request->validate([
            'headline' => 'required|string|max:255',
            'expertise_area' => 'required|string|max:255',
            'bio' => 'required|string|min:30',
            'portfolio_url' => 'nullable|url|max:255',
            'sample_video_url' => 'nullable|url|max:255',
        ]);

        $app = InstructorApplication::updateOrCreate(
            ['user_id' => $user->id],
            [
                'headline' => $request->headline,
                'expertise_area' => $request->expertise_area,
                'bio' => $request->bio,
                'portfolio_url' => $request->portfolio_url,
                'sample_video_url' => $request->sample_video_url,
                'status' => 'pending',
                'rejection_reason' => null,
            ]
        );

        // Notify admins
        try {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                \App\Models\AppNotification::notify(
                    $admin->id,
                    'support',
                    "👨‍🏫 New Instructor Application: {$user->name}",
                    "Headline: {$request->headline} &bull; Area: {$request->expertise_area}",
                    route('admin.instructor-applications'),
                    'fa-chalkboard-teacher',
                    'purple'
                );
            }
        } catch (\Throwable $e) {}

        return redirect()->route('instructor.apply')
            ->with('status', 'Your instructor application has been submitted successfully! Our team will review your profile.');
    }

    /**
     * List all applications for Admin / Platform Owner review.
     */
    public function index()
    {
        $applications = InstructorApplication::with('user')
            ->latest()
            ->get();

        return view('admin.instructor-applications', compact('applications'));
    }

    /**
     * Approve an application and upgrade user role to instructor.
     */
    public function approve(InstructorApplication $application)
    {
        $application->update(['status' => 'approved']);

        $user = $application->user;
        if ($user) {
            $user->update(['role' => 'instructor']);
            try {
                \App\Models\AppNotification::notify(
                    $user->id,
                    'announcement',
                    "🎉 Congratulations! You are now an Instructor",
                    "Your instructor application has been approved. You can now create and manage courses!",
                    route('instructor.dashboard'),
                    'fa-chalkboard-teacher',
                    'green'
                );
            } catch (\Throwable $e) {}
        }

        return back()->with('status', "Application for {$user->name} has been APPROVED! They are now an Instructor.");
    }

    /**
     * Reject an application with optional feedback.
     */
    public function reject(Request $request, InstructorApplication $application)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $reason = $request->rejection_reason ?: 'Application does not meet current criteria.';

        $application->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);

        $user = $application->user;
        if ($user) {
            try {
                \App\Models\AppNotification::notify(
                    $user->id,
                    'system',
                    "Instructor Application Status Update",
                    "Status: " . $reason,
                    route('instructor.apply'),
                    'fa-info-circle',
                    'amber'
                );
            } catch (\Throwable $e) {}
        }

        return back()->with('status', "Application for {$application->user->name} has been REJECTED.");
    }
}
