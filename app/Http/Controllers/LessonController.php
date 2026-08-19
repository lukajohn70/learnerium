<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
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

        // Check if user is enrolled or is the instructor
        $user = Auth::user();
        if (!$user->enrolledIn($course->id) && $user->id !== $course->instructor_id) {
            abort(403, 'You are not enrolled in this course.');
        }

        // Get or create lesson progress for student
        $progress = null;
        if ($user->id !== $course->instructor_id) {
            $progress = LessonProgress::firstOrCreate(
                ['user_id' => $user->id, 'lesson_id' => $lesson->id],
                ['progress_percentage' => 0, 'completed' => false]
            );
        }

        $lessons = $course->lessons;

        return view('student.lesson', compact('course', 'lesson', 'lessons', 'progress'));
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

        return response()->json(['message' => 'Lesson marked as completed']);
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'required|integer|min:0',
            'video_url' => 'nullable|url',
            'content' => 'nullable|string',
        ]);

        $lesson = $course->lessons()->create($validated);

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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'required|integer|min:0',
            'video_url' => 'nullable|url',
            'content' => 'nullable|string',
        ]);

        $lesson->update($validated);

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

        return redirect()->route('instructor.courses.edit', $course)->with('success', 'Lesson deleted successfully!');
    }
}
