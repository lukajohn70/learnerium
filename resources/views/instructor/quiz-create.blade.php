@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="mb-6">
        <a href="{{ route('lessons.quizzes.index', [$lesson->course_id, $lesson->id]) }}" class="text-primary-jlm hover:text-secondary-jlm font-semibold transition text-sm">
            <i class="fas fa-arrow-left mr-2"></i>Back to Quizzes
        </a>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">Add Quiz</h1>
            <p class="text-gray-500 mt-1">For lesson: <span class="font-semibold text-primary-jlm">{{ $lesson->title }}</span></p>
        </div>

        <div class="bg-white rounded-2xl shadow-md overflow-hidden border-t-4 border-green-500">
            <div class="bg-gray-50 border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-clipboard-list mr-2 text-green-500"></i>Quiz Details</h2>
            </div>
            <form action="{{ route('lessons.quizzes.store', [$lesson->course_id, $lesson->id]) }}" method="POST" class="p-6 space-y-5">
                @csrf

                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-1.5">Quiz Title <span class="text-red-500">*</span></label>
                    <input id="title" name="title" type="text" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-400/30 focus:border-green-500 transition text-gray-800"
                           placeholder="e.g., Module 1 Assessment">
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">Description</label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-400/30 focus:border-green-500 transition text-gray-800 resize-none"
                              placeholder="Optional quiz description..."></textarea>
                </div>

                <div>
                    <label for="time_limit_minutes" class="block text-sm font-semibold text-gray-700 mb-1.5">Time Limit (minutes)</label>
                    <input id="time_limit_minutes" name="time_limit_minutes" type="number" min="0"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-400/30 focus:border-green-500 transition text-gray-800"
                           placeholder="Leave blank for no time limit">
                    <p class="text-xs text-gray-400 mt-1.5">Set to 0 or leave blank for no time limit.</p>
                </div>

                <label class="flex items-center gap-3 cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" name="is_published" value="1" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-checked:bg-green-500 rounded-full transition-colors duration-200"></div>
                        <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform duration-200"></div>
                    </div>
                    <span class="text-sm font-semibold text-gray-700">Publish Quiz (make visible to students)</span>
                </label>

                <div class="pt-4 border-t border-gray-100 flex gap-3">
                    <button type="submit" class="bg-green-500 text-white px-8 py-3 rounded-xl font-bold hover:bg-green-600 transition shadow-md">
                        <i class="fas fa-plus mr-2"></i>Create Quiz
                    </button>
                    <a href="{{ route('lessons.quizzes.index', [$lesson->course_id, $lesson->id]) }}" class="border border-gray-200 text-gray-600 px-6 py-3 rounded-xl font-semibold hover:bg-gray-50 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
