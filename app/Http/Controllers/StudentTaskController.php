<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Task;
use App\Models\Submission;
use App\Models\PeerReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentTaskController extends Controller
{
    /**
     * Submit a task response (link, file, or survey).
     */
    public function store(Request $request, $taskId)
    {
        $task = Task::with('lesson')->findOrFail($taskId);
        $user = Auth::user();

        // Check if already submitted
        $existing = Submission::where('task_id', $task->id)->where('user_id', $user->id)->first();
        if ($existing) {
            return back()->with('error', 'You have already submitted this task.');
        }

        // Validate depending on task type
        if ($task->type === 'link') {
            $request->validate(['submission_value' => 'required|url']);
            $submissionValue = $request->submission_value;
            $fileName = null;
        } elseif ($task->type === 'file') {
            $request->validate(['submission_file' => 'required|file|max:20480']); // 20MB max
            $path = $request->file('submission_file')->store('task-submissions', 'public');
            $submissionValue = $path;
            $fileName = $request->file('submission_file')->getClientOriginalName();
        } elseif ($task->type === 'survey') {
            $request->validate(['survey_answers' => 'required|array']);
            $submissionValue = json_encode($request->survey_answers);
            $fileName = null;
        } else {
            return back()->with('error', 'Invalid task type.');
        }

        Submission::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'submission_value' => $submissionValue,
            'file_name' => $fileName,
            'status' => 'submitted',
        ]);

        // Send notifications to instructor and student
        try {
            $course = $task->lesson?->course;
            if ($course && $course->instructor_id) {
                // Alert Instructor
                \App\Models\AppNotification::notify(
                    $course->instructor_id,
                    'submission',
                    "New Task Submission from {$user->name} 📝",
                    "Student {$user->name} submitted an assignment for \"{$task->lesson->title}\". Click to review and grade.",
                    route('instructor.submissions'),
                    'fa-tasks',
                    'blue'
                );

                // Confirmation to Student
                \App\Models\AppNotification::notify(
                    $user->id,
                    'submission',
                    'Assignment Submitted Successfully! ✅',
                    "Your submission for \"{$task->title}\" in {$task->lesson->title} was received and is awaiting instructor grading.",
                    route('courses.lessons.show', [$course->slug, $task->lesson->id]),
                    'fa-check-circle',
                    'green'
                );
            }
        } catch (\Throwable $e) {}

        return back()->with('status', 'Task submitted successfully! Awaiting review.');
    }


    /**
     * Submit a peer review for another student's submission.
     */
    public function submitPeerReview(Request $request, $submissionId)
    {
        $submission = Submission::with('task')->findOrFail($submissionId);
        $user = Auth::user();

        // Prevent reviewing your own submission
        if ($submission->user_id === $user->id) {
            return back()->with('error', 'You cannot review your own submission.');
        }

        // Prevent duplicate reviews
        $alreadyReviewed = PeerReview::where('submission_id', $submission->id)->where('reviewer_id', $user->id)->exists();
        if ($alreadyReviewed) {
            return back()->with('error', 'You have already reviewed this submission.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'approved' => 'required|boolean',
            'feedback' => 'nullable|string|max:1000',
        ]);

        PeerReview::create([
            'submission_id' => $submission->id,
            'reviewer_id' => $user->id,
            'rating' => $request->rating,
            'approved' => $request->approved,
            'feedback' => $request->feedback,
        ]);

        // Auto-approve submission if enough reviews received
        $task = $submission->task;
        if ($task->peer_review_enabled) {
            $approvalCount = PeerReview::where('submission_id', $submission->id)->where('approved', true)->count();
            if ($approvalCount >= $task->required_reviews_count) {
                $submission->update(['status' => 'approved']);
            }
        }

        return back()->with('status', 'Peer review submitted!');
    }

    /**
     * Get pending peer review submissions for the current user.
     */
    public static function pendingReviewsFor($userId, $lesson)
    {
        // Find submissions from OTHER students in this lesson's peer-review tasks
        // that haven't been reviewed by this user yet
        $peerReviewTaskIds = Task::where('lesson_id', $lesson->id)
            ->where('peer_review_enabled', true)
            ->pluck('id');

        return Submission::with(['user', 'task'])
            ->whereIn('task_id', $peerReviewTaskIds)
            ->where('user_id', '!=', $userId)
            ->whereDoesntHave('peerReviews', function ($q) use ($userId) {
                $q->where('reviewer_id', $userId);
            })
            ->get();
    }
}
