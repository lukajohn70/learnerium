@extends('layouts.app')

@section('content')
<main class="max-w-4xl mx-auto px-6 py-12">
    <h1 class="text-2xl font-bold mb-6">Quiz Analytics: {{ $quiz->title }}</h1>
    <div class="mb-6">
        <div class="mb-2"><span class="font-semibold">Total Attempts:</span> {{ $totalAttempts }}</div>
        <div class="mb-2"><span class="font-semibold">Average Score:</span>
            <span class="inline-block align-middle w-32 bg-gray-200 rounded-full h-3 mx-2">
                <span class="h-3 rounded-full bg-blue-500 block" style="width: {{ $averageScore ?? 0 }}%"></span>
            </span>
            <span class="align-middle">{{ $averageScore !== null ? $averageScore : 'N/A' }}%</span>
        </div>
        <div class="mb-2"><span class="font-semibold">Pass Rate:</span>
            <span class="inline-block align-middle w-32 bg-gray-200 rounded-full h-3 mx-2">
                <span class="h-3 rounded-full bg-green-500 block" style="width: {{ $passRate ?? 0 }}%"></span>
            </span>
            <span class="align-middle">{{ $passRate !== null ? $passRate : 'N/A' }}%</span>
        </div>
    </div>
    <h2 class="text-xl font-semibold mt-8 mb-2">Per-Question Statistics</h2>
    <div class="bg-white rounded shadow overflow-hidden mb-8">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="text-left px-6 py-3 text-sm font-semibold">Question</th>
                    <th class="text-left px-6 py-3 text-sm font-semibold">Answered</th>
                    <th class="text-left px-6 py-3 text-sm font-semibold">Correct</th>
                    <th class="text-left px-6 py-3 text-sm font-semibold">Accuracy</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($questionStats as $stat)
                    <tr class="border-t">
                        <td class="px-6 py-3">{{ $stat['question']->question_text }}</td>
                        <td class="px-6 py-3">{{ $stat['answered'] }}</td>
                        <td class="px-6 py-3">{{ $stat['correct'] }}</td>
                        <td class="px-6 py-3">
                            <div class="w-24 bg-gray-200 rounded-full h-3 inline-block align-middle">
                                <div class="h-3 rounded-full bg-blue-500" style="width: {{ $stat['accuracy'] ?? 0 }}%"></div>
                            </div>
                            <span class="ml-2 align-middle">{{ $stat['accuracy'] !== null ? $stat['accuracy'] . '%' : 'N/A' }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <h2 class="text-xl font-semibold mt-8 mb-2">Recent Attempts</h2>
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="text-left px-6 py-3 text-sm font-semibold">Student</th>
                    <th class="text-left px-6 py-3 text-sm font-semibold">Score</th>
                    <th class="text-left px-6 py-3 text-sm font-semibold">Passed</th>
                    <th class="text-left px-6 py-3 text-sm font-semibold">Completed At</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($attempts as $attempt)
                    <tr class="border-t">
                        <td class="px-6 py-3">{{ $attempt->user->name ?? 'Unknown' }}</td>
                        <td class="px-6 py-3">
                            <div class="w-20 bg-gray-200 rounded-full h-3 inline-block align-middle">
                                <div class="h-3 rounded-full bg-blue-500" style="width: {{ $attempt->score ?? 0 }}%"></div>
                            </div>
                            <span class="ml-2 align-middle">{{ $attempt->score }}%</span>
                        </td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-0.5 rounded-full text-white text-xs {{ $attempt->passed ? 'bg-green-600' : 'bg-red-600' }}">
                                {{ $attempt->passed ? 'Passed' : 'Failed' }}
                            </span>
                        </td>
                        <td class="px-6 py-3">{{ $attempt->completed_at }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-6 text-gray-600">No attempts yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <a href="{{ route('lessons.quizzes.index', [$quiz->lesson->course_id, $quiz->lesson_id]) }}" class="text-blue-600 hover:underline mt-6 inline-block">Back to Quizzes</a>
</main>
@endsection