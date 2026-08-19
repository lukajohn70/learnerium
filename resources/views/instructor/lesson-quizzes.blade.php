@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="mb-6">
        <a href="{{ route('instructor.courses.edit', $lesson->course_id) }}" class="text-primary-jlm hover:text-secondary-jlm font-semibold transition text-sm">
            <i class="fas fa-arrow-left mr-2"></i>Back to Course
        </a>
    </div>

    <div class="max-w-3xl mx-auto">
        <div class="mb-8 flex items-center justify-between gap-4 flex-wrap">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">Quizzes</h1>
                <p class="text-gray-500 mt-1">For lesson: <span class="font-semibold text-primary-jlm">{{ $lesson->title }}</span></p>
            </div>
            <a href="{{ route('lessons.quizzes.create', [$lesson->course_id, $lesson->id]) }}"
               class="inline-flex items-center gap-2 bg-green-500 text-white px-5 py-2.5 rounded-xl font-semibold hover:bg-green-600 transition shadow text-sm">
                <i class="fas fa-plus"></i>Add Quiz
            </a>
        </div>

        @if($quizzes->isEmpty())
            <div class="bg-white rounded-2xl shadow-md p-16 text-center">
                <div class="text-6xl text-gray-200 mb-4"><i class="fas fa-question-circle"></i></div>
                <h2 class="text-xl font-bold text-gray-700 mb-2">No quizzes yet.</h2>
                <p class="text-gray-400 mb-6">Create your first quiz for this lesson.</p>
                <a href="{{ route('lessons.quizzes.create', [$lesson->course_id, $lesson->id]) }}"
                   class="bg-green-500 text-white px-8 py-3 rounded-xl font-semibold hover:bg-green-600 transition shadow-md">
                    Add Quiz
                </a>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                <div class="divide-y divide-gray-50">
                    @foreach($quizzes as $quiz)
                    <div class="px-6 py-4 flex items-center gap-4 hover:bg-gray-50 transition flex-wrap">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-clipboard-list text-purple-500"></i>
                        </div>
                        <div class="flex-grow">
                            <p class="font-semibold text-gray-800">{{ $quiz->title }}</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                @if($quiz->is_published)
                                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-semibold">Published</span>
                                @else
                                    <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full font-semibold">Draft</span>
                                @endif
                                @if($quiz->time_limit_minutes)
                                    <span class="text-xs text-gray-400"><i class="fas fa-clock mr-1"></i>{{ $quiz->time_limit_minutes }} min</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2 ml-auto">
                            <a href="{{ route('lessons.quizzes.edit', [$lesson->course_id, $lesson->id, $quiz->id]) }}"
                               class="bg-secondary-jlm text-white px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-secondary-jlm/90 transition">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </a>
                            <a href="{{ route('instructor.quizzes.analytics', [$lesson->course_id, $lesson->id, $quiz->id]) }}"
                               class="border border-purple-300 text-purple-600 px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-purple-50 transition">
                                <i class="fas fa-chart-bar mr-1"></i>Analytics
                            </a>
                            <form action="{{ route('lessons.quizzes.destroy', [$lesson->course_id, $lesson->id, $quiz->id]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Delete this quiz?')"
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
