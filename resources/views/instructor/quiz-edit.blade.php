@extends('layouts.app')

@section('content')
<main class="max-w-2xl mx-auto px-6 py-12">
    <h1 class="text-2xl font-bold mb-6">Edit Quiz: {{ $quiz->title }}</h1>
    <form action="{{ route('lessons.quizzes.update', [$quiz->lesson->course_id, $quiz->lesson_id, $quiz->id]) }}" method="POST" class="bg-white p-6 rounded shadow space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium mb-1" for="title">Quiz Title</label>
            <input id="title" name="title" type="text" class="w-full border rounded px-3 py-2" value="{{ old('title', $quiz->title) }}" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" for="description">Description</label>
            <textarea id="description" name="description" rows="3" class="w-full border rounded px-3 py-2">{{ old('description', $quiz->description) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" for="time_limit_minutes">Time Limit (minutes, optional)</label>
            <input id="time_limit_minutes" name="time_limit_minutes" type="number" min="0" class="w-full border rounded px-3 py-2" value="{{ old('time_limit_minutes', $quiz->time_limit_seconds ? $quiz->time_limit_seconds / 60 : '' ) }}">
            <div class="text-xs text-gray-500">Leave blank for no time limit.</div>
        </div>
        <div>
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_published" value="1" class="form-checkbox" {{ $quiz->is_published ? 'checked' : '' }}>
                <span class="ml-2">Publish Quiz</span>
            </label>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update Quiz</button>
        <a href="{{ route('lessons.quizzes.index', [$quiz->lesson->course_id, $quiz->lesson_id]) }}" class="text-blue-600 hover:underline ml-4">Cancel</a>
    </form>
</main>
@endsection
