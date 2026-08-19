@extends('layouts.app')

@section('content')
<main class="max-w-2xl mx-auto px-6 py-12">
    <h1 class="text-2xl font-bold mb-6">Add Quiz for Lesson: {{ $lesson->title }}</h1>
    <form action="{{ route('lessons.quizzes.store', [$lesson->course_id, $lesson->id]) }}" method="POST" class="bg-white p-6 rounded shadow space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1" for="title">Quiz Title</label>
            <input id="title" name="title" type="text" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" for="description">Description</label>
            <textarea id="description" name="description" rows="3" class="w-full border rounded px-3 py-2"></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" for="time_limit_minutes">Time Limit (minutes, optional)</label>
            <input id="time_limit_minutes" name="time_limit_minutes" type="number" min="0" class="w-full border rounded px-3 py-2">
            <div class="text-xs text-gray-500">Leave blank for no time limit.</div>
        </div>
        <div>
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_published" value="1" class="form-checkbox">
                <span class="ml-2">Publish Quiz</span>
            </label>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Create Quiz</button>
        <a href="{{ route('lessons.quizzes.index', [$lesson->course_id, $lesson->id]) }}" class="text-blue-600 hover:underline ml-4">Cancel</a>
    </form>
</main>
@endsection
