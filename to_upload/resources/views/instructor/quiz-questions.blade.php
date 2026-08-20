@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="mb-6">
        <a href="{{ url()->previous() }}" class="text-primary-jlm hover:text-secondary-jlm font-semibold transition text-sm">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>

    <div class="max-w-3xl mx-auto">
        <div class="mb-8 flex items-center justify-between gap-4 flex-wrap">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">Quiz Questions</h1>
                <p class="text-gray-500 mt-1">{{ $quiz->title }}</p>
            </div>
            <a href="{{ route('quizzes.questions.create', $quiz->id) }}"
               class="inline-flex items-center gap-2 bg-green-500 text-white px-5 py-2.5 rounded-xl font-semibold hover:bg-green-600 transition shadow text-sm">
                <i class="fas fa-plus"></i>Add Question
            </a>
        </div>

        @if($questions->isEmpty())
            <div class="bg-white rounded-2xl shadow-md p-16 text-center">
                <div class="text-6xl text-gray-200 mb-4"><i class="fas fa-question"></i></div>
                <h2 class="text-xl font-bold text-gray-700 mb-2">No questions yet.</h2>
                <p class="text-gray-400 mb-6">Add your first question to this quiz.</p>
                <a href="{{ route('quizzes.questions.create', $quiz->id) }}"
                   class="bg-green-500 text-white px-8 py-3 rounded-xl font-semibold hover:bg-green-600 transition shadow-md">
                    Add Question
                </a>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                <div class="divide-y divide-gray-50">
                    @foreach($questions as $question)
                    <div class="px-6 py-4 flex items-center gap-4 hover:bg-gray-50 transition flex-wrap">
                        <span class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 font-bold text-sm flex items-center justify-center flex-shrink-0">
                            {{ $question->order }}
                        </span>
                        <div class="flex-grow min-w-0">
                            <p class="font-semibold text-gray-800 truncate">{{ $question->question_text }}</p>
                            <span class="text-xs text-gray-400 capitalize">{{ str_replace('_', ' ', $question->type) }}</span>
                        </div>
                        <div class="flex items-center gap-2 ml-auto flex-shrink-0">
                            <a href="{{ route('quizzes.questions.edit', [$quiz->id, $question->id]) }}"
                               class="bg-secondary-jlm text-white px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-secondary-jlm/90 transition">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </a>
                            <form action="{{ route('quizzes.questions.destroy', [$quiz->id, $question->id]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Delete this question?')"
                                        class="text-red-400 hover:text-red-600 px-2 py-1.5 rounded-lg hover:bg-red-50 transition text-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
