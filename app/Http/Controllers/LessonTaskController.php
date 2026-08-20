<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Task;
use App\Models\Submission;
use Illuminate\Http\Request;

class LessonTaskController extends Controller
{
    /**
     * Display a listing of tasks for a lesson.
     */
    public function index($lessonId)
    {
        $lesson = Lesson::with('course')->findOrFail($lessonId);
        $tasks = Task::where('lesson_id', $lesson->id)->orderBy('id')->get();

        return view('instructor.lesson-tasks', compact('lesson', 'tasks'));
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request, $lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:link,file,survey,quiz',
            'description' => 'nullable|string',
            'is_required' => 'nullable|boolean',
            'peer_review_enabled' => 'nullable|boolean',
            'required_reviews_count' => 'nullable|integer|min:1',
            'survey_questions' => 'nullable|string', // Newline-separated questions
        ]);

        $taskData = [
            'lesson_id' => $lesson->id,
            'title' => $data['title'],
            'type' => $data['type'],
            'description' => $data['description'] ?? null,
            'is_required' => $request->has('is_required'),
            'peer_review_enabled' => $request->has('peer_review_enabled'),
            'required_reviews_count' => $data['required_reviews_count'] ?? 1,
        ];

        // If type is survey, parse survey questions
        if ($data['type'] === 'survey' && !empty($data['survey_questions'])) {
            $questions = array_filter(array_map('trim', explode("\n", $data['survey_questions'])));
            $taskData['config'] = ['questions' => array_values($questions)];
        }

        Task::create($taskData);

        return redirect()->route('lessons.tasks.index', $lessonId)->with('status', 'Task created successfully.');
    }

    /**
     * Show form to edit a task.
     */
    public function edit($lessonId, $taskId)
    {
        $lesson = Lesson::with('course')->findOrFail($lessonId);
        $task = Task::findOrFail($taskId);

        return view('instructor.task-edit', compact('lesson', 'task'));
    }

    /**
     * Update the specified task.
     */
    public function update(Request $request, $lessonId, $taskId)
    {
        $task = Task::findOrFail($taskId);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_required' => 'nullable|boolean',
            'peer_review_enabled' => 'nullable|boolean',
            'required_reviews_count' => 'nullable|integer|min:1',
            'survey_questions' => 'nullable|string',
        ]);

        $taskData = [
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'is_required' => $request->has('is_required'),
            'peer_review_enabled' => $request->has('peer_review_enabled'),
            'required_reviews_count' => $data['required_reviews_count'] ?? 1,
        ];

        if ($task->type === 'survey' && !empty($data['survey_questions'])) {
            $questions = array_filter(array_map('trim', explode("\n", $data['survey_questions'])));
            $taskData['config'] = ['questions' => array_values($questions)];
        }

        $task->update($taskData);

        return redirect()->route('lessons.tasks.index', $lessonId)->with('status', 'Task updated successfully.');
    }

    /**
     * Remove the specified task.
     */
    public function destroy($lessonId, $taskId)
    {
        $task = Task::findOrFail($taskId);
        $task->delete();

        return redirect()->route('lessons.tasks.index', $lessonId)->with('status', 'Task deleted successfully.');
    }

    /**
     * Instructor manually approves a student submission.
     */
    public function approveSubmission($taskId, $submissionId)
    {
        $submission = Submission::findOrFail($submissionId);
        $submission->update(['status' => 'approved']);

        return back()->with('status', 'Submission approved.');
    }

    /**
     * Instructor manually rejects a student submission.
     */
    public function rejectSubmission($taskId, $submissionId)
    {
        $submission = Submission::findOrFail($submissionId);
        $submission->update(['status' => 'rejected']);

        return back()->with('status', 'Submission rejected.');
    }
}
