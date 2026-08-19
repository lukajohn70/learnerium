@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="mb-6">
        <a href="{{ route('quizzes.questions.index', $quiz->id) }}" class="text-primary-jlm hover:text-secondary-jlm font-semibold transition text-sm">
            <i class="fas fa-arrow-left mr-2"></i>Back to Questions
        </a>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">Edit Question</h1>
            <p class="text-gray-500 mt-1">Quiz: <span class="font-semibold text-primary-jlm">{{ $quiz->title }}</span></p>
        </div>

        <div class="bg-white rounded-2xl shadow-md overflow-hidden border-t-4 border-secondary-jlm">
            <div class="bg-gray-50 border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-edit mr-2 text-secondary-jlm"></i>Question Details</h2>
            </div>
            <form action="{{ route('quizzes.questions.update', [$quiz->id, $question->id]) }}" method="POST" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="question_text" class="block text-sm font-semibold text-gray-700 mb-1.5">Question Text <span class="text-red-500">*</span></label>
                    <textarea id="question_text" name="question_text" rows="3" required
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary-jlm/30 focus:border-secondary-jlm transition text-gray-800 resize-none">{{ old('question_text', $question->question_text) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label for="type" class="block text-sm font-semibold text-gray-700 mb-1.5">Question Type <span class="text-red-500">*</span></label>
                        <select id="type" name="type" required onchange="toggleOptions(this.value)"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary-jlm/30 focus:border-secondary-jlm transition text-gray-800 bg-white">
                            <option value="multiple_choice" {{ $question->type == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                            <option value="true_false" {{ $question->type == 'true_false' ? 'selected' : '' }}>True / False</option>
                            <option value="short_answer" {{ $question->type == 'short_answer' ? 'selected' : '' }}>Short Answer</option>
                        </select>
                    </div>
                    <div>
                        <label for="order" class="block text-sm font-semibold text-gray-700 mb-1.5">Order</label>
                        <input id="order" name="order" type="number" min="0" value="{{ old('order', $question->order) }}"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary-jlm/30 focus:border-secondary-jlm transition text-gray-800">
                    </div>
                </div>

                <div id="options-section" style="{{ $question->type == 'multiple_choice' ? '' : 'display:none;' }}">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Answer Options</label>
                    <div id="options-list" class="space-y-2">
                        @if($question->type == 'multiple_choice' && $question->options)
                            @foreach(json_decode($question->options, true) as $option)
                                <input type="text" name="options[]" value="{{ $option }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none text-gray-800">
                            @endforeach
                        @else
                            <input type="text" name="options[]" placeholder="Option A"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none text-gray-800">
                            <input type="text" name="options[]" placeholder="Option B"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none text-gray-800">
                        @endif
                    </div>
                    <button type="button" onclick="addOption()" class="mt-2 text-sm text-primary-jlm hover:text-secondary-jlm font-semibold transition">
                        <i class="fas fa-plus mr-1"></i>Add Option
                    </button>
                </div>

                <div>
                    <label for="correct_answer" class="block text-sm font-semibold text-gray-700 mb-1.5">Correct Answer</label>
                    <input id="correct_answer" name="correct_answer" type="text" value="{{ old('correct_answer', $question->correct_answer) }}"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary-jlm/30 focus:border-secondary-jlm transition text-gray-800">
                </div>

                <div class="pt-4 border-t border-gray-100 flex gap-3">
                    <button type="submit" class="bg-secondary-jlm text-white px-8 py-3 rounded-xl font-bold hover:bg-secondary-jlm/90 transition shadow-md">
                        <i class="fas fa-save mr-2"></i>Update Question
                    </button>
                    <a href="{{ route('quizzes.questions.index', $quiz->id) }}" class="border border-gray-200 text-gray-600 px-6 py-3 rounded-xl font-semibold hover:bg-gray-50 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function toggleOptions(type) {
    document.getElementById('options-section').style.display = type === 'multiple_choice' ? 'block' : 'none';
}
function addOption() {
    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'options[]';
    input.className = 'w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none text-gray-800';
    input.placeholder = 'Option';
    document.getElementById('options-list').appendChild(input);
}
</script>
@endsection
