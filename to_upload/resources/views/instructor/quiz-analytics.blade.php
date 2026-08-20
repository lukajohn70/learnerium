@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="mb-6">
        <a href="{{ route('lessons.quizzes.index', [$quiz->lesson->course_id, $quiz->lesson_id]) }}" class="text-primary-jlm hover:text-secondary-jlm font-semibold transition text-sm">
            <i class="fas fa-arrow-left mr-2"></i>Back to Quizzes
        </a>
    </div>

    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900">Quiz Analytics</h1>
        <p class="text-gray-500 mt-1">{{ $quiz->title }}</p>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-5 mb-10">
        <div class="bg-white rounded-2xl shadow-md p-6 text-center border-t-4 border-primary-jlm hover:shadow-lg transition">
            <div class="text-4xl font-extrabold text-primary-jlm mb-1">{{ $totalAttempts }}</div>
            <div class="text-sm text-gray-500 font-medium"><i class="fas fa-users mr-1"></i>Total Attempts</div>
        </div>
        <div class="bg-white rounded-2xl shadow-md p-6 text-center border-t-4 border-blue-400 hover:shadow-lg transition">
            <div class="text-4xl font-extrabold text-blue-500 mb-1">{{ $averageScore !== null ? $averageScore . '%' : 'N/A' }}</div>
            <div class="text-sm text-gray-500 font-medium"><i class="fas fa-chart-line mr-1"></i>Avg. Score</div>
            @if($averageScore !== null)
                <div class="mt-2 w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-blue-400 h-2 rounded-full" style="width: {{ $averageScore }}%"></div>
                </div>
            @endif
        </div>
        <div class="bg-white rounded-2xl shadow-md p-6 text-center border-t-4 border-green-500 hover:shadow-lg transition col-span-2 lg:col-span-1">
            <div class="text-4xl font-extrabold text-green-500 mb-1">{{ $passRate !== null ? $passRate . '%' : 'N/A' }}</div>
            <div class="text-sm text-gray-500 font-medium"><i class="fas fa-check-circle mr-1"></i>Pass Rate</div>
            @if($passRate !== null)
                <div class="mt-2 w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $passRate }}%"></div>
                </div>
            @endif
        </div>
    </div>

    <!-- Per-Question Stats -->
    @if(!empty($questionStats))
    <div class="bg-white rounded-2xl shadow-md overflow-hidden mb-8">
        <div class="bg-gray-50 border-b border-gray-100 px-6 py-4">
            <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-list-ul mr-2 text-primary-jlm"></i>Per-Question Statistics</h2>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($questionStats as $stat)
            <div class="px-6 py-4 hover:bg-gray-50 transition">
                <p class="font-semibold text-gray-800 mb-2">{{ $stat['question']->question_text }}</p>
                <div class="flex flex-wrap gap-4 text-sm">
                    <span class="text-gray-500">Answered: <strong class="text-gray-700">{{ $stat['answered'] }}</strong></span>
                    <span class="text-gray-500">Correct: <strong class="text-green-600">{{ $stat['correct'] }}</strong></span>
                    <div class="flex items-center gap-2">
                        <span class="text-gray-500">Accuracy:</span>
                        <div class="w-24 bg-gray-100 rounded-full h-2">
                            <div class="h-2 rounded-full bg-gradient-to-r from-primary-jlm to-secondary-jlm" style="width: {{ $stat['accuracy'] ?? 0 }}%"></div>
                        </div>
                        <strong class="text-primary-jlm">{{ $stat['accuracy'] !== null ? $stat['accuracy'] . '%' : 'N/A' }}</strong>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Recent Attempts -->
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <div class="bg-gray-50 border-b border-gray-100 px-6 py-4">
            <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-history mr-2 text-secondary-jlm"></i>Recent Attempts</h2>
        </div>
        @if($attempts->isEmpty())
            <div class="p-10 text-center text-gray-400">
                <div class="text-4xl mb-3"><i class="fas fa-inbox"></i></div>
                <p>No attempts yet.</p>
            </div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($attempts as $attempt)
                <div class="px-6 py-4 flex flex-wrap items-center gap-4 hover:bg-gray-50 transition">
                    <div class="flex items-center gap-3 flex-grow">
                        <div class="w-9 h-9 rounded-full bg-primary-jlm/10 text-primary-jlm font-bold text-sm flex items-center justify-center flex-shrink-0">
                            {{ strtoupper(substr($attempt->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <span class="font-semibold text-gray-800">{{ $attempt->user->name ?? 'Unknown' }}</span>
                    </div>
                    <div class="flex items-center gap-4 ml-auto">
                        <div class="flex items-center gap-2">
                            <div class="w-20 bg-gray-100 rounded-full h-2">
                                <div class="h-2 rounded-full {{ $attempt->passed ? 'bg-green-500' : 'bg-red-400' }}" style="width: {{ $attempt->score ?? 0 }}%"></div>
                            </div>
                            <span class="text-sm font-bold text-gray-700">{{ $attempt->score }}%</span>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $attempt->passed ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                            {{ $attempt->passed ? 'Passed' : 'Failed' }}
                        </span>
                        <span class="text-xs text-gray-400">{{ $attempt->completed_at }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection