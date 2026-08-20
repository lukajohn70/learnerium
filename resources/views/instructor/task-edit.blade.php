@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="mb-6">
        <a href="{{ route('lessons.tasks.index', $lesson->id) }}" class="text-primary-jlm hover:text-secondary-jlm font-semibold transition text-sm">
            <i class="fas fa-arrow-left mr-2"></i>Back to Tasks
        </a>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">Edit Task</h1>
            <p class="text-gray-500 mt-1">Lesson: <span class="font-semibold text-primary-jlm">{{ $lesson->title }}</span></p>
        </div>

        <div class="bg-white rounded-2xl shadow-md overflow-hidden border-t-4 border-secondary-jlm">
            <div class="bg-gray-50 border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-edit mr-2 text-secondary-jlm"></i>Task Details (Type: <span class="capitalize">{{ $task->type }}</span>)</h2>
            </div>
            <form action="{{ route('lessons.tasks.update', [$lesson->id, $task->id]) }}" method="POST" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-1.5">Task Title <span class="text-red-500">*</span></label>
                    <input id="title" name="title" type="text" value="{{ old('title', $task->title) }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary-jlm/30 focus:border-secondary-jlm transition text-gray-800">
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">Task Instructions</label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary-jlm/30 focus:border-secondary-jlm transition text-gray-800 resize-none">{{ old('description', $task->description) }}</textarea>
                </div>

                <!-- Survey Questions (for Survey Type Only) -->
                @if($task->type === 'survey')
                    @php
                        $questionsText = isset($task->config['questions']) ? implode("\n", $task->config['questions']) : '';
                    @endphp
                    <div>
                        <label for="survey_questions" class="block text-sm font-semibold text-gray-700 mb-1.5">Survey Questions (One per line) <span class="text-red-500">*</span></label>
                        <textarea id="survey_questions" name="survey_questions" rows="4" required
                                  class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary-jlm/30 focus:border-secondary-jlm transition text-gray-800">{{ old('survey_questions', $questionsText) }}</textarea>
                    </div>
                @endif

                <!-- Peer Review Options (for Link & File Types Only) -->
                @if($task->type === 'link' || $task->type === 'file')
                    <div class="space-y-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <div class="relative">
                                <input type="checkbox" name="peer_review_enabled" id="peer_review_enabled" value="1" class="sr-only peer" onchange="toggleReviewsCount(this.checked)" {{ $task->peer_review_enabled ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-checked:bg-secondary-jlm rounded-full transition-colors duration-200"></div>
                                <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform duration-200"></div>
                            </div>
                            <span class="text-sm font-semibold text-gray-700">Enable Peer Review for this task</span>
                        </label>

                        <div id="reviews-count-section" style="{{ $task->peer_review_enabled ? '' : 'display:none;' }}" class="max-w-xs">
                            <label for="required_reviews_count" class="block text-xs font-bold text-gray-500 mb-1">Required Peer Reviews Count</label>
                            <input id="required_reviews_count" name="required_reviews_count" type="number" min="1" value="{{ old('required_reviews_count', $task->required_reviews_count) }}"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary-jlm/30 focus:border-secondary-jlm transition text-gray-800 text-sm">
                        </div>
                    </div>
                @endif

                <label class="flex items-center gap-3 cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" name="is_required" value="1" class="sr-only peer" {{ $task->is_required ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-checked:bg-secondary-jlm rounded-full transition-colors duration-200"></div>
                        <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform duration-200"></div>
                    </div>
                    <span class="text-sm font-semibold text-gray-700">Required (gates the next lesson/section)</span>
                </label>

                <div class="pt-4 border-t border-gray-100 flex gap-3">
                    <button type="submit" class="bg-secondary-jlm text-white px-8 py-3 rounded-xl font-bold hover:bg-secondary-jlm/90 transition shadow-md">
                        <i class="fas fa-save mr-2"></i>Save Changes
                    </button>
                    <a href="{{ route('lessons.tasks.index', $lesson->id) }}" class="border border-gray-200 text-gray-600 px-6 py-3 rounded-xl font-semibold hover:bg-gray-50 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleReviewsCount(checked) {
    document.getElementById('reviews-count-section').style.display = checked ? 'block' : 'none';
}
</script>
@endsection
