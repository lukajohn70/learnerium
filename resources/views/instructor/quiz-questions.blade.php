@extends('layouts.app')

@section('content')
<main class="max-w-3xl mx-auto px-6 py-12">
    <h1 class="text-2xl font-bold mb-6">Questions for Quiz: {{ $quiz->title }}</h1>
    <a href="{{ route('quizzes.questions.create', $quiz->id) }}" class="bg-green-600 text-white px-4 py-2 rounded mb-4 inline-block">Add New Question</a>
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="text-left px-6 py-3 text-sm font-semibold">Order</th>
                    <th class="text-left px-6 py-3 text-sm font-semibold">Type</th>
                    <th class="text-left px-6 py-3 text-sm font-semibold">Question</th>
                    <th class="text-left px-6 py-3 text-sm font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($questions as $question)
                    <tr class="border-t">
                        <td class="px-6 py-3">{{ $question->order }}</td>
                        <td class="px-6 py-3">{{ ucfirst(str_replace('_', ' ', $question->type)) }}</td>
                        <td class="px-6 py-3 font-medium">{{ $question->question_text }}</td>
                        <td class="px-6 py-3 flex items-center gap-3">
                            <a href="{{ route('quizzes.questions.edit', [$quiz->id, $question->id]) }}" class="text-blue-600 hover:underline text-sm">Edit</a>
                            <form action="{{ route('quizzes.questions.destroy', [$quiz->id, $question->id]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-sm" onclick="return confirm('Delete this question?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-6 text-gray-600">No questions for this quiz yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <a href="{{ url()->previous() }}" class="text-blue-600 hover:underline mt-6 inline-block">Back</a>
</main>
@endsection
