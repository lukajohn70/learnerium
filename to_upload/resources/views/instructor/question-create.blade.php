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
            <h1 class="text-3xl font-extrabold text-gray-900">Add Question</h1>
            <p class="text-gray-500 mt-1">Quiz: <span class="font-semibold text-primary-jlm">{{ $quiz->title }}</span></p>
        </div>

        <div class="bg-white rounded-2xl shadow-md overflow-hidden border-t-4 border-green-500">
            <div class="bg-gray-50 border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-question-circle mr-2 text-green-500"></i>Question Details</h2>
            </div>
            <form action="{{ route('quizzes.questions.store', $quiz->id) }}" method="POST" class="p-6 space-y-5">
                @csrf

                <div>
                    <label for="question_text" class="block text-sm font-semibold text-gray-700 mb-1.5">Question Text <span class="text-red-500">*</span></label>
                    <textarea id="question_text" name="question_text" rows="3" required
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-400/30 focus:border-green-500 transition text-gray-800 resize-none"
                              placeholder="Enter the question..."></textarea>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label for="type" class="block text-sm font-semibold text-gray-700 mb-1.5">Question Type <span class="text-red-500">*</span></label>
                        <select id="type" name="type" required onchange="toggleOptions(this.value)"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-400/30 focus:border-green-500 transition text-gray-800 bg-white">
                            <option value="multiple_choice">Multiple Choice</option>
                            <option value="true_false">True / False</option>
                            <option value="short_answer">Short Answer</option>
                        </select>
                    </div>
                    <div>
                        <label for="order" class="block text-sm font-semibold text-gray-700 mb-1.5">Order</label>
                        <input id="order" name="order" type="number" min="0"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-400/30 focus:border-green-500 transition text-gray-800"
                               placeholder="0">
                    </div>
                </div>

                <div id="options-section">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Answer Options <span class="text-red-500">*</span></label>
                    <div id="options-list" class="space-y-2">
                        <input type="text" name="options[]" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-400/30 focus:border-green-500 transition text-gray-800" placeholder="Option A">
                        <input type="text" name="options[]" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-400/30 focus:border-green-500 transition text-gray-800" placeholder="Option B">
                    </div>
                    <button type="button" onclick="addOption()" class="mt-2 text-sm text-primary-jlm hover:text-secondary-jlm font-semibold transition">
                        <i class="fas fa-plus mr-1"></i>Add Option
                    </button>
                </div>

                <div>
                    <label for="correct_answer" class="block text-sm font-semibold text-gray-700 mb-1.5">Correct Answer <span class="text-red-500">*</span></label>
                    <input id="correct_answer" name="correct_answer" type="text"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-400/30 focus:border-green-500 transition text-gray-800"
                           placeholder="Enter the correct answer exactly">
                    <p class="text-xs text-gray-400 mt-1.5">For multiple choice: type the exact text of the correct option. For true/false: type "true" or "false".</p>
                </div>

                <div class="pt-4 border-t border-gray-100 flex gap-3">
                    <button type="submit" class="bg-green-500 text-white px-8 py-3 rounded-xl font-bold hover:bg-green-600 transition shadow-md">
                        <i class="fas fa-plus mr-2"></i>Create Question
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
