<?php

namespace App\Http\Controllers;


use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;

class QuizQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $questions = $quiz->questions;
        return view('instructor.quiz-questions', compact('quiz', 'questions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        return view('instructor.question-create', compact('quiz'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $data = $request->validate([
            'question_text' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,short_answer',
            'options' => 'nullable|array',
            'correct_answer' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);
        $data['quiz_id'] = $quiz->id;
        if (isset($data['options'])) {
            $data['options'] = json_encode($data['options']);
        }
        Question::create($data);
        return redirect()->route('quizzes.questions.index', $quizId)->with('status', 'Question created successfully.');
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
    public function edit($quizId, $questionId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $question = Question::findOrFail($questionId);
        return view('instructor.question-edit', compact('quiz', 'question'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $quizId, $questionId)
    {
        $question = Question::findOrFail($questionId);
        $data = $request->validate([
            'question_text' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,short_answer',
            'options' => 'nullable|array',
            'correct_answer' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);
        if (isset($data['options'])) {
            $data['options'] = json_encode($data['options']);
        }
        $question->update($data);
        return redirect()->route('quizzes.questions.index', $quizId)->with('status', 'Question updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($quizId, $questionId)
    {
        $question = Question::findOrFail($questionId);
        $question->delete();
        return redirect()->route('quizzes.questions.index', $quizId)->with('status', 'Question deleted successfully.');
    }
}
