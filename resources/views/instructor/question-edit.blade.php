x@extends('layouts.app')

@section('content')
<main class="max-w-2xl mx-auto px-6 py-12">
    <h1 class="text-2xl font-bold mb-6">Edit Question for Quiz: {{ $quiz->title }}</h1>
    <form action="{{ route('quizzes.questions.update', [$quiz->id, $question->id]) }}" method="POST" class="bg-white p-6 rounded shadow space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium mb-1" for="question_text">Question Text</label>
            <textarea id="question_text" name="question_text" rows="3" class="w-full border rounded px-3 py-2" required>{{ old('question_text', $question->question_text) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" for="type">Type</label>
            <select id="type" name="type" class="w-full border rounded px-3 py-2" required>
                <option value="multiple_choice" {{ $question->type == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                <option value="true_false" {{ $question->type == 'true_false' ? 'selected' : '' }}>True/False</option>
                <option value="short_answer" {{ $question->type == 'short_answer' ? 'selected' : '' }}>Short Answer</option>
            </select>
        </div>
        <div id="options-section" style="display:none;">
            <label class="block text-sm font-medium mb-1">Options (for Multiple Choice)</label>
            <div id="options-list">
                @if($question->type == 'multiple_choice' && $question->options)
                    @foreach(json_decode($question->options, true) as $option)
                        <input type="text" name="options[]" class="w-full border rounded px-3 py-2 mb-2" value="{{ $option }}">
                    @endforeach
                @else
                    <input type="text" name="options[]" class="w-full border rounded px-3 py-2 mb-2" placeholder="Option 1">
                    <input type="text" name="options[]" class="w-full border rounded px-3 py-2 mb-2" placeholder="Option 2">
                @endif
            </div>
            <button type="button" onclick="addOption()" class="bg-gray-200 px-2 py-1 rounded">Add Option</button>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" for="correct_answer">Correct Answer</label>
            <input id="correct_answer" name="correct_answer" type="text" class="w-full border rounded px-3 py-2" value="{{ old('correct_answer', $question->correct_answer) }}">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" for="order">Order</label>
            <input id="order" name="order" type="number" min="0" class="w-full border rounded px-3 py-2" value="{{ old('order', $question->order) }}">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update Question</button>
        <a href="{{ route('quizzes.questions.index', $quiz->id) }}" class="text-blue-600 hover:underline ml-4">Cancel</a>
    </form>
</main>
<script>
document.getElementById('type').addEventListener('change', function() {
    document.getElementById('options-section').style.display = this.value === 'multiple_choice' ? 'block' : 'none';
});
function addOption() {
    var input = document.createElement('input');
    input.type = 'text';
    input.name = 'options[]';
    input.className = 'w-full border rounded px-3 py-2 mb-2';
    input.placeholder = 'Option';
    document.getElementById('options-list').appendChild(input);
}
// Show options section if editing a multiple choice question
if(document.getElementById('type').value === 'multiple_choice') {
    document.getElementById('options-section').style.display = 'block';
}
</script>
@endsection
