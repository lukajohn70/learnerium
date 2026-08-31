<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Submission;
use App\Http\Controllers\StudentTaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    /**
     * Show lesson player for students
     */
    public function show(Course $course, Lesson $lesson)
    {
        // Verify lesson belongs to course
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        // Check if user is enrolled (with payment) or is the instructor/admin
        $user = Auth::user();
        $isInstructor = $user->id === $course->instructor_id;
        $isAdmin = $user->role === 'admin';

        if (!$isInstructor && !$isAdmin && !$user->enrolledIn($course->id)) {
            // If paid course, redirect to checkout
            if ((float) $course->price > 0) {
                return redirect()->route('courses.checkout', $course)
                    ->with('info', 'Please complete payment to access this lesson.');
            }
            // Free course — just enroll them
            \App\Models\Enrollment::firstOrCreate(
                ['user_id' => $user->id, 'course_id' => $course->id],
                ['payment_status' => 'paid', 'amount_paid' => 0]
            );
        }

        // Verify module unlock and drip status for student
        if (!$lesson->isUnlockedFor($user)) {
            $dripMsg = $lesson->dripMessageFor($user);
            $errorText = $dripMsg 
                ? "🔒 Drip Content Locked: This lesson {$dripMsg}."
                : "🔒 Module Locked: Complete all lessons in previous modules first to unlock this section.";
            return redirect()->route('course.detail', $course->slug)->with('error', $errorText);
        }

        // Get or create lesson progress for student
        $progress = null;
        if ($user->id !== $course->instructor_id) {
            $progress = LessonProgress::firstOrCreate(
                ['user_id' => $user->id, 'lesson_id' => $lesson->id],
                ['progress_percentage' => 0, 'completed' => false]
            );
        }

        $lessons = $course->lessons()->with('tasks')->get();


        // Load tasks for this lesson
        $tasks = $lesson->tasks()->orderBy('id')->get();

        // Load user's own submissions keyed by task_id
        $userSubmissions = [];
        $pendingRequiredTask = false;
        $lessonCompleted = false;
        $pendingReviews = [];

        if ($user->id !== $course->instructor_id) {
            // Check lesson completion status
            $lessonProgress = LessonProgress::where('user_id', $user->id)
                ->where('lesson_id', $lesson->id)
                ->first();
            $lessonCompleted = $lessonProgress && $lessonProgress->completed;

            // Submissions by this user for this lesson's tasks
            $taskIds = $tasks->pluck('id');
            $submissions = Submission::whereIn('task_id', $taskIds)
                ->where('user_id', $user->id)
                ->get()
                ->keyBy('task_id');
            $userSubmissions = $submissions->toArray();

            // Convert back to models for easy access in view
            $userSubmissions = $submissions; // keep as collection

            // Check if any required task is not yet approved
            foreach ($tasks as $task) {
                if ($task->is_required) {
                    $sub = $submissions->get($task->id);
                    if (!$sub || $sub->status !== 'approved') {
                        $pendingRequiredTask = true;
                        break;
                    }
                }
            }

            // Load peer review submissions for each peer-review task
            foreach ($tasks->where('peer_review_enabled', true) as $task) {
                $pendingReviews[$task->id] = StudentTaskController::pendingReviewsFor($user->id, $lesson);
            }
        }

        return view('student.lesson', compact(
            'course', 'lesson', 'lessons', 'progress',
            'tasks', 'userSubmissions', 'pendingRequiredTask',
            'lessonCompleted', 'pendingReviews'
        ));
    }

    /**
     * Mark lesson as completed
     */
    public function markComplete(Course $course, Lesson $lesson)
    {
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        $user = Auth::user();
        if (!$user->enrolledIn($course->id)) {
            abort(403, 'You are not enrolled in this course.');
        }

        $progress = LessonProgress::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->firstOrCreate(
                [],
                ['progress_percentage' => 100, 'completed' => true, 'completed_at' => now()]
            );

        if (!$progress->completed) {
            $progress->update([
                'completed' => true,
                'progress_percentage' => 100,
                'completed_at' => now(),
            ]);
        }

        // Update enrollment progress after marking lesson complete
        $enrollment = $user->enrollments()->where('course_id', $course->id)->first();
        if ($enrollment) {
            $enrollment->updateProgress();
        }

        return redirect()->back()->with('status', 'Lesson marked as completed! Great work.');
    }

    /**
     * Store a new lesson (instructor only)
     */
    public function store(Request $request, Course $course)
    {
        $user = Auth::user();
        if ($user->id !== $course->instructor_id) {
            abort(403, 'You are not the instructor of this course.');
        }

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'module_id'        => 'nullable|exists:modules,id',
            'description'      => 'nullable|string',
            'order'            => 'required|integer|min:0',
            'video_url'        => 'nullable|url',
            'duration_minutes' => 'nullable|integer|min:0',
            'content'          => 'nullable|string',
            'drip_date'        => 'nullable|date',
            'drip_days'        => 'nullable|integer|min:0',
        ]);

        $validated['drip_date'] = $request->drip_date ?: null;
        $validated['drip_days'] = $request->drip_days !== null && $request->drip_days !== '' ? (int)$request->drip_days : null;

        $lesson = $course->lessons()->create($validated);

        // Auto-recalculate course duration
        $totalMinutes = (int) $course->lessons()->sum('duration_minutes');
        if ($totalMinutes > 0) {
            $course->update(['duration_minutes' => $totalMinutes]);
        }

        return redirect()->route('instructor.courses.edit', $course)->with('success', 'Lesson created successfully!');
    }

    /**
     * Update a lesson (instructor only)
     */
    public function update(Request $request, Course $course, Lesson $lesson)
    {
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        $user = Auth::user();
        if ($user->id !== $course->instructor_id) {
            abort(403, 'You are not the instructor of this course.');
        }

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'module_id'        => 'nullable|exists:modules,id',
            'description'      => 'nullable|string',
            'order'            => 'required|integer|min:0',
            'video_url'        => 'nullable|url',
            'duration_minutes' => 'nullable|integer|min:0',
            'content'          => 'nullable|string',
            'drip_date'        => 'nullable|date',
            'drip_days'        => 'nullable|integer|min:0',
        ]);

        $validated['drip_date'] = $request->drip_date ?: null;
        $validated['drip_days'] = $request->drip_days !== null && $request->drip_days !== '' ? (int)$request->drip_days : null;

        $lesson->update($validated);

        // Auto-recalculate course duration
        $totalMinutes = (int) $course->lessons()->sum('duration_minutes');
        if ($totalMinutes > 0) {
            $course->update(['duration_minutes' => $totalMinutes]);
        }

        return redirect()->route('instructor.courses.edit', $course)->with('success', 'Lesson updated successfully!');
    }

    /**
     * Delete a lesson (instructor only)
     */
    public function destroy(Course $course, Lesson $lesson)
    {
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        $user = Auth::user();
        if ($user->id !== $course->instructor_id) {
            abort(403, 'You are not the instructor of this course.');
        }

        $lesson->delete();

        // Auto-recalculate course duration
        $totalMinutes = (int) $course->lessons()->sum('duration_minutes');
        $course->update(['duration_minutes' => max(1, $totalMinutes)]);

        return redirect()->route('instructor.courses.edit', $course)->with('success', 'Lesson deleted successfully!');
    }
}


