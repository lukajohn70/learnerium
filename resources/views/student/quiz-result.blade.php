@extends('layouts.app')

@section('content')
<main class="max-w-2xl mx-auto px-6 py-12">
    <h1 class="text-2xl font-bold mb-6">Quiz Result: {{ $quiz->title }}</h1>
    <div class="bg-white p-6 rounded shadow mb-6">
        <div class="flex items-center mb-4">
            <span class="text-lg font-semibold mr-4">Score:</span>
            <div class="flex-1">
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="h-4 rounded-full {{ $attempt->passed ? 'bg-green-500' : 'bg-red-500' }}" style="width: {{ $attempt->score }}%"></div>
                </div>
                <div class="text-sm mt-1">{{ $attempt->score }}%</div>
            </div>
            <span class="ml-4 px-3 py-1 rounded-full text-white {{ $attempt->passed ? 'bg-green-600' : 'bg-red-600' }}">
                {{ $attempt->passed ? 'Passed' : 'Failed' }}
            </span>
        </div>
        <p class="mb-4 text-sm text-gray-600">Completed at: {{ $attempt->completed_at }}</p>
        <h2 class="text-lg font-bold mb-2">Your Answers:</h2>
        <ul class="list-disc pl-6">
            @foreach ($quiz->questions as $q)
                <li class="mb-4">
                    <div class="font-semibold mb-1">Q{{ $loop->iteration }}: {{ $q->question_text }}</div>
                    <div class="flex items-center mb-1">
                        <span class="mr-2">Your answer:</span>
                        <span class="font-semibold {{ ($answers[$q->id] ?? null) == $q->correct_answer ? 'text-green-600' : 'text-red-600' }}">
                            {{ $answers[$q->id] ?? 'No answer' }}
                        </span>
                        @if (($answers[$q->id] ?? null) == $q->correct_answer)
                            <span class="ml-2 px-2 py-0.5 rounded bg-green-100 text-green-700 text-xs">Correct</span>
                        @else
                            <span class="ml-2 px-2 py-0.5 rounded bg-red-100 text-red-700 text-xs">Incorrect</span>
                        @endif
                    </div>
                    <div class="text-sm text-gray-600 mb-1">Correct answer: <span class="font-semibold">{{ $q->correct_answer }}</span></div>
                </li>
            @endforeach
        </ul>
    </div>
    <a href="{{ route('student.courses') }}" class="text-blue-600 hover:underline">Back to My Courses</a>
</main>
@endsection
