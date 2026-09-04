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

        $discussion = LessonDiscussion::create([
            'lesson_id' => $lesson->id,
            'user_id'   => $user->id,
            'comment'   => $request->comment,
            'parent_id' => $request->parent_id ?: null,
        ]);

        // Send notifications
        try {
            $lessonUrl = route('lesson.show', [$course->slug, $lesson->id]);
            $cleanComment = strip_tags($request->comment);

            // 1. If student posted, notify the course instructor
            if ($course->instructor_id && $course->instructor_id !== $user->id) {
                \App\Models\AppNotification::notify(
                    $course->instructor_id,
                    'submission',
                    "New Discussion Question 💬",
                    "{$user->name} posted in \"{$lesson->title}\": \"" . \Illuminate\Support\Str::limit($cleanComment, 80) . "\"",
                    $lessonUrl,
                    'fa-comments',
                    'blue'
                );
            }

            // 2. If it's a reply to a parent comment, notify the original author
            if ($request->parent_id) {
                $parentComment = LessonDiscussion::find($request->parent_id);
                if ($parentComment && $parentComment->user_id !== $user->id) {
                    \App\Models\AppNotification::notify(
                        $parentComment->user_id,
                        'submission',
                        "New Reply to Your Comment 💬",
                        "{$user->name} replied to your discussion in \"{$lesson->title}\".",
                        $lessonUrl,
                        'fa-reply',
                        'purple'
                    );
                }
            }
        } catch (\Throwable $e) {}

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
