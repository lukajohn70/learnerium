<?php

namespace App\Http\Controllers;


use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentQuizController extends Controller
{
    // Show quiz player
    public function show($courseId, $lessonId, $quizId)
    {
        $quiz = Quiz::with('questions')->findOrFail($quizId);
        $lesson = Lesson::findOrFail($lessonId);
        $course = Course::findOrFail($courseId);
        $user = Auth::user();
        $attempts = $quiz->attempts()->where('user_id', $user->id)->orderByDesc('completed_at')->get();
        $hasAttempted = $attempts->count() > 0;
        return view('student.quiz-player', compact('quiz', 'lesson', 'course', 'user', 'hasAttempted', 'attempts'));
    }

    // Handle quiz submission
    public function submit(Request $request, $courseId, $lessonId, $quizId)
    {
        $quiz = Quiz::with('questions')->findOrFail($quizId);
        $user = Auth::user();
        $answers = $request->input('answers', []);
        $score = 0;
        $total = $quiz->questions->count();
        $details = [];
        foreach ($quiz->questions as $question) {
            $given = $answers[$question->id] ?? null;
            $correct = $question->correct_answer;
            $isCorrect = ($given !== null && $given == $correct);
            $score += $isCorrect ? 1 : 0;
            $details[] = [
                'question_id' => $question->id,
                'given' => $given,
                'correct' => $correct,
                'is_correct' => $isCorrect,
            ];
        }
        $percent = $total > 0 ? round(($score / $total) * 100) : 0;
        $passed = $percent >= 60; // Example pass mark
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => $user->id,
            'score' => $percent,
            'answers' => json_encode($answers),
            'passed' => $passed,
            'completed_at' => now(),
        ]);

        // Mark lesson as complete if quiz is passed or always on completion
        $lesson = $quiz->lesson;
        $lessonProgress = \App\Models\LessonProgress::firstOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'progress_percentage' => 0,
                'completed' => false,
            ]
        );
        if (!$lessonProgress->completed) {
            $lessonProgress->update([
                'progress_percentage' => 100,
                'completed' => true,
                'completed_at' => now(),
            ]);
        }
        // Update enrollment progress
        $enrollment = $user->enrollments()->where('course_id', $courseId)->first();
        if ($enrollment) {
            $enrollment->updateProgress();
        }

        return redirect()->route('student.quiz.result', [$courseId, $lessonId, $quizId, 'attempt' => $attempt->id])
            ->with('status', 'Quiz submitted successfully!');
    }

    // Show quiz result
    public function result(Request $request, $courseId, $lessonId, $quizId)
    {
        $quiz = Quiz::with('questions')->findOrFail($quizId);
        $user = Auth::user();
        $attemptId = $request->query('attempt');
        $attempt = QuizAttempt::where('id', $attemptId)->where('user_id', $user->id)->firstOrFail();
        $answers = json_decode($attempt->answers, true);
        return view('student.quiz-result', compact('quiz', 'attempt', 'answers'));
    }
}
