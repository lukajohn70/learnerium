@extends('layouts.app')

@section('content')
<main class="max-w-2xl mx-auto px-6 py-12">
    <h1 class="text-2xl font-bold mb-6">Quiz: {{ $quiz->title }}</h1>
    @if ($hasAttempted)
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded mb-6">
            <p class="mb-2 font-semibold">You have already attempted this quiz.</p>
            <p class="mb-2">Attempt History:</p>
            <ul class="list-disc pl-6">
                @foreach ($attempts as $attempt)
                    <li>
                        Score: {{ $attempt->score }}% | Status: <span class="font-semibold {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">{{ $attempt->passed ? 'Passed' : 'Failed' }}</span> | <a href="{{ route('student.quiz.result', [$course->id, $lesson->id, $quiz->id, 'attempt' => $attempt->id]) }}" class="text-blue-600 hover:underline">View</a> | {{ $attempt->completed_at }}
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <form id="quizForm" action="{{ route('student.quiz.submit', [$course->id, $lesson->id, $quiz->id]) }}" method="POST" class="bg-white p-6 rounded shadow space-y-6">
            @csrf
            @if ($quiz->time_limit_seconds)
                <div class="mb-4 text-red-600 font-semibold">
                    Time Remaining: <span id="timer"></span>
                </div>
                <script>
                let timeLeft = {{ $quiz->time_limit_seconds }};
                function updateTimer() {
                    const min = Math.floor(timeLeft / 60);
                    const sec = timeLeft % 60;
                    document.getElementById('timer').textContent = `${min}:${sec.toString().padStart(2, '0')}`;
                    if (timeLeft <= 0) {
                        document.getElementById('quizForm').submit();
                    } else {
                        timeLeft--;
                        setTimeout(updateTimer, 1000);
                    }
                }
                updateTimer();
                </script>
            @endif
            <div class="mb-6 flex items-center justify-between">
                <div class="text-sm text-gray-600">Questions: <span class="font-semibold">1</span> to <span class="font-semibold">{{ $quiz->questions->count() }}</span></div>
                <div class="flex gap-1">
                    @foreach ($quiz->questions as $q)
                        <span class="w-3 h-3 rounded-full {{ $loop->first ? 'bg-blue-500' : 'bg-gray-300' }} inline-block"></span>
                    @endforeach
                </div>
            </div>
            @foreach ($quiz->questions as $q)
                <div class="mb-4 p-4 rounded {{ $loop->first ? 'bg-blue-50 border border-blue-200' : 'bg-gray-50' }}">
                    <div class="font-semibold mb-2">Q{{ $loop->iteration }}. {{ $q->question_text }}</div>
                    @if ($q->type === 'multiple_choice' && $q->options)
                        @foreach (json_decode($q->options, true) as $opt)
                            <label class="block mb-1">
                                <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt }}" required> {{ $opt }}
                            </label>
                        @endforeach
                    @elseif ($q->type === 'true_false')
                        <label class="block mb-1"><input type="radio" name="answers[{{ $q->id }}]" value="true" required> True</label>
                        <label class="block mb-1"><input type="radio" name="answers[{{ $q->id }}]" value="false" required> False</label>
                    @elseif ($q->type === 'short_answer')
                        <input type="text" name="answers[{{ $q->id }}]" class="w-full border rounded px-3 py-2" required>
                    @endif
                </div>
            @endforeach
            <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-700 text-white px-6 py-2 rounded shadow hover:from-blue-600 hover:to-blue-800 transition">Submit Quiz</button>
        </form>
    @endif
</main>
@endsection
