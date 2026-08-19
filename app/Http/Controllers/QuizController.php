<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    // Instructor analytics for a quiz
    public function analytics($courseId, $lessonId, $quizId)
    {
        $quiz = Quiz::with(['questions', 'attempts.user'])->findOrFail($quizId);
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($courseId, $lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $quizzes = $lesson->quizzes ?? $lesson->hasMany(Quiz::class)->get();
        return view('instructor.lesson-quizzes', compact('lesson', 'quizzes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($courseId, $lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        return view('instructor.quiz-create', compact('lesson'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $courseId, $lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_published' => 'boolean',
            'time_limit_minutes' => 'nullable|integer|min:0',
        ]);
        $data['lesson_id'] = $lesson->id;
        // Convert minutes to seconds for DB
        $data['time_limit_seconds'] = isset($data['time_limit_minutes']) && $data['time_limit_minutes'] !== null && $data['time_limit_minutes'] !== ''
            ? $data['time_limit_minutes'] * 60 : null;
        unset($data['time_limit_minutes']);
        Quiz::create($data);
        return redirect()->route('lessons.quizzes.index', [$courseId, $lessonId])->with('status', 'Quiz created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($courseId, $lessonId, $quizId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $quiz = Quiz::findOrFail($quizId);
        return view('instructor.quiz-edit', compact('lesson', 'quiz'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $courseId, $lessonId, $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_published' => 'boolean',
            'time_limit_minutes' => 'nullable|integer|min:0',
        ]);
        // Convert minutes to seconds for DB
        $data['time_limit_seconds'] = isset($data['time_limit_minutes']) && $data['time_limit_minutes'] !== null && $data['time_limit_minutes'] !== ''
            ? $data['time_limit_minutes'] * 60 : null;
        unset($data['time_limit_minutes']);
        $quiz->update($data);
        return redirect()->route('lessons.quizzes.index', [$courseId, $lessonId])->with('status', 'Quiz updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($courseId, $lessonId, $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $quiz->delete();
        return redirect()->route('lessons.quizzes.index', [$courseId, $lessonId])->with('status', 'Quiz deleted successfully.');
    }
}
