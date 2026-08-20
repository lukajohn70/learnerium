<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Instructor analytics for a quiz.
     */
    public function analytics($quizId)
    {
        $quiz = Quiz::with(['questions', 'attempts.user', 'lesson.course'])->findOrFail($quizId);
        $attempts = $quiz->attempts;
        $totalAttempts = $attempts->count();
        $averageScore = $totalAttempts > 0 ? round($attempts->avg('score'), 2) : null;
        $passCount = $attempts->where('passed', true)->count();
        $passRate = $totalAttempts > 0 ? round(($passCount / $totalAttempts) * 100, 1) : null;

        // Per-question stats
        $questionStats = [];
        foreach ($quiz->questions as $question) {
            $correct = 0;
            $answered = 0;
            foreach ($attempts as $attempt) {
                $answers = is_array($attempt->answers) ? $attempt->answers : json_decode($attempt->answers, true);
                if (isset($answers[$question->id])) {
                    $answered++;
                    if ($answers[$question->id] == $question->correct_answer) {
                        $correct++;
                    }
                }
            }
            $questionStats[] = [
                'question' => $question,
                'answered' => $answered,
                'correct' => $correct,
                'accuracy' => $answered > 0 ? round(($correct / $answered) * 100, 1) : null,
            ];
        }

        return view('instructor.quiz-analytics', compact('quiz', 'attempts', 'averageScore', 'passRate', 'questionStats', 'totalAttempts'));
    }

    /**
     * Display a listing of quizzes for a lesson.
     */
    public function index($lessonId)
    {
        $lesson = Lesson::with('course')->findOrFail($lessonId);
        $quizzes = Quiz::where('lesson_id', $lesson->id)->get();
        return view('instructor.lesson-quizzes', compact('lesson', 'quizzes'));
    }

    /**
     * Show the form for creating a new quiz.
     */
    public function create($lessonId)
    {
        $lesson = Lesson::with('course')->findOrFail($lessonId);
        return view('instructor.quiz-create', compact('lesson'));
    }

    /**
     * Store a newly created quiz in storage.
     */
    public function store(Request $request, $lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_published' => 'boolean',
            'time_limit_minutes' => 'nullable|integer|min:0',
        ]);
        $data['lesson_id'] = $lesson->id;
        $data['time_limit_seconds'] = isset($data['time_limit_minutes']) && $data['time_limit_minutes'] !== null && $data['time_limit_minutes'] !== ''
            ? $data['time_limit_minutes'] * 60 : null;
        unset($data['time_limit_minutes']);
        Quiz::create($data);

        return redirect()->route('lessons.quizzes.index', $lesson->id)->with('status', 'Quiz created successfully.');
    }

    /**
     * Show the form for editing the specified quiz.
     */
    public function edit($lessonId, $quizId)
    {
        $lesson = Lesson::with('course')->findOrFail($lessonId);
        $quiz = Quiz::findOrFail($quizId);
        return view('instructor.quiz-edit', compact('lesson', 'quiz'));
    }

    /**
     * Update the specified quiz in storage.
     */
    public function update(Request $request, $lessonId, $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_published' => 'boolean',
            'time_limit_minutes' => 'nullable|integer|min:0',
        ]);
        $data['time_limit_seconds'] = isset($data['time_limit_minutes']) && $data['time_limit_minutes'] !== null && $data['time_limit_minutes'] !== ''
            ? $data['time_limit_minutes'] * 60 : null;
        unset($data['time_limit_minutes']);
        $quiz->update($data);

        return redirect()->route('lessons.quizzes.index', $lessonId)->with('status', 'Quiz updated successfully.');
    }

    /**
     * Remove the specified quiz from storage.
     */
    public function destroy($lessonId, $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $quiz->delete();

        return redirect()->route('lessons.quizzes.index', $lessonId)->with('status', 'Quiz deleted successfully.');
    }
}
