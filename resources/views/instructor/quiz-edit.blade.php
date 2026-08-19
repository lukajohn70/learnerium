@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="mb-6">
        <a href="{{ route('lessons.quizzes.index', [$quiz->lesson->course_id, $quiz->lesson_id]) }}" class="text-primary-jlm hover:text-secondary-jlm font-semibold transition text-sm">
            <i class="fas fa-arrow-left mr-2"></i>Back to Quizzes
        </a>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">Edit Quiz</h1>
            <p class="text-gray-500 mt-1">{{ $quiz->title }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-md overflow-hidden border-t-4 border-secondary-jlm">
            <div class="bg-gray-50 border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-edit mr-2 text-secondary-jlm"></i>Quiz Details</h2>
            </div>
            <form action="{{ route('lessons.quizzes.update', [$quiz->lesson->course_id, $quiz->lesson_id, $quiz->id]) }}" method="POST" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-1.5">Quiz Title <span class="text-red-500">*</span></label>
                    <input id="title" name="title" type="text" value="{{ old('title', $quiz->title) }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary-jlm/30 focus:border-secondary-jlm transition text-gray-800">
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">Description</label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary-jlm/30 focus:border-secondary-jlm transition text-gray-800 resize-none">{{ old('description', $quiz->description) }}</textarea>
                </div>

                <div>
                    <label for="time_limit_minutes" class="block text-sm font-semibold text-gray-700 mb-1.5">Time Limit (minutes)</label>
                    <input id="time_limit_minutes" name="time_limit_minutes" type="number" min="0"
                           value="{{ old('time_limit_minutes', $quiz->time_limit_seconds ? $quiz->time_limit_seconds / 60 : '') }}"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary-jlm/30 focus:border-secondary-jlm transition text-gray-800"
                           placeholder="Leave blank for no time limit">
                </div>

                <label class="flex items-center gap-3 cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" name="is_published" value="1" class="sr-only peer" {{ $quiz->is_published ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-checked:bg-secondary-jlm rounded-full transition-colors duration-200"></div>
                        <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform duration-200"></div>
                    </div>
                    <span class="text-sm font-semibold text-gray-700">Published (visible to students)</span>
                </label>

                <div class="pt-4 border-t border-gray-100 flex gap-3">
                    <button type="submit" class="bg-secondary-jlm text-white px-8 py-3 rounded-xl font-bold hover:bg-secondary-jlm/90 transition shadow-md">
                        <i class="fas fa-save mr-2"></i>Update Quiz
                    </button>
                    <a href="{{ route('lessons.quizzes.index', [$quiz->lesson->course_id, $quiz->lesson_id]) }}"
                       class="border border-gray-200 text-gray-600 px-6 py-3 rounded-xl font-semibold hover:bg-gray-50 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
