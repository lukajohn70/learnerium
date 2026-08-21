<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonDiscussion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonDiscussionController extends Controller
{
    /**
     * Store a new discussion comment or reply.
     */
    public function store(Request $request, Course $course, Lesson $lesson)
    {
        $request->validate([
            'comment'   => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:lesson_discussions,id',
        ]);

        $user = Auth::user();

        // Must be enrolled or be the instructor
        if (!$user->enrolledIn($course->id) && $user->id !== $course->instructor_id && $user->role !== 'admin') {
            abort(403, 'You must be enrolled to post a discussion.');
        }

        LessonDiscussion::create([
            'lesson_id' => $lesson->id,
            'user_id'   => $user->id,
            'comment'   => $request->comment,
            'parent_id' => $request->parent_id ?: null,
        ]);

        return back()->with('status', 'Comment posted!');
    }

    /**
     * Delete a discussion comment.
     */
    public function destroy(LessonDiscussion $discussion)
    {
        $user = Auth::user();

        if ($user->id !== $discussion->user_id && $user->role !== 'admin') {
            abort(403, 'You can only delete your own comments.');
        }

        $discussion->delete();
        return back()->with('status', 'Comment deleted.');
    }
}
