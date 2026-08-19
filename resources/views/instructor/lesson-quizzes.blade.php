@extends('layouts.app')

@section('content')
<main class="max-w-3xl mx-auto px-6 py-12">
    <h1 class="text-2xl font-bold mb-6">Quizzes for Lesson: {{ $lesson->title }}</h1>
    <a href="{{ route('lessons.quizzes.create', [$lesson->course_id, $lesson->id]) }}" class="bg-green-600 text-white px-4 py-2 rounded mb-4 inline-block">Add New Quiz</a>
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="text-left px-6 py-3 text-sm font-semibold">Title</th>
                    <th class="text-left px-6 py-3 text-sm font-semibold">Published</th>
                    <th class="text-left px-6 py-3 text-sm font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($quizzes as $quiz)
                    <tr class="border-t">
                        <td class="px-6 py-3 font-medium">{{ $quiz->title }}</td>
                        <td class="px-6 py-3">{{ $quiz->is_published ? 'Yes' : 'No' }}</td>
                        <td class="px-6 py-3 flex items-center gap-3">
                            <a href="{{ route('lessons.quizzes.edit', [$lesson->course_id, $lesson->id, $quiz->id]) }}" class="text-blue-600 hover:underline text-sm">Edit</a>
                            <a href="{{ route('instructor.quizzes.analytics', [$lesson->course_id, $lesson->id, $quiz->id]) }}" class="text-purple-600 hover:underline text-sm">Analytics</a>
                            <form action="{{ route('lessons.quizzes.destroy', [$lesson->course_id, $lesson->id, $quiz->id]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-sm" onclick="return confirm('Delete this quiz?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-6 text-gray-600">No quizzes for this lesson yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <a href="{{ route('instructor.courses.edit', $lesson->course_id) }}" class="text-blue-600 hover:underline mt-6 inline-block">Back to Course</a>
</main>
@endsection
