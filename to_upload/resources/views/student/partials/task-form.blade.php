{{--
    Partial: task-form
    Variables: $task (Task model)
--}}
<form action="{{ route('student.tasks.submit', $task->id) }}" method="POST"
      enctype="multipart/form-data"
      class="space-y-4">
    @csrf

    @if($task->type === 'link')
        <div>
            <label for="link_{{ $task->id }}" class="block text-sm font-semibold text-gray-700 mb-1">
                <i class="fas fa-link text-blue-500 mr-1"></i> Paste your link
            </label>
            <input type="url" id="link_{{ $task->id }}" name="submission_value"
                   placeholder="https://..."
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
                   required>
        </div>

    @elseif($task->type === 'file')
        <div>
            <label for="file_{{ $task->id }}" class="block text-sm font-semibold text-gray-700 mb-1">
                <i class="fas fa-upload text-green-500 mr-1"></i> Upload your file
            </label>
            <input type="file" id="file_{{ $task->id }}" name="submission_file"
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 transition bg-white"
                   required>
            <p class="text-xs text-gray-400 mt-1">Max file size: 20MB</p>
        </div>

    @elseif($task->type === 'survey')
        @if(!empty($task->config['questions']))
            <div class="space-y-3">
                @foreach($task->config['questions'] as $idx => $question)
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            {{ $idx + 1 }}. {{ $question }}
                        </label>
                        <textarea name="survey_answers[{{ $idx }}]" rows="2"
                                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400 transition resize-none"
                                  placeholder="Your answer..."
                                  required></textarea>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500">No survey questions have been configured for this task yet.</p>
        @endif

    @elseif($task->type === 'quiz')
        <div class="bg-indigo-50 border border-indigo-200 rounded-xl px-4 py-3 text-indigo-800 text-sm flex items-center gap-2">
            <i class="fas fa-info-circle"></i>
            <span>Complete the quiz assigned to this lesson to satisfy this task gate.</span>
        </div>

    @endif

    @if($task->type !== 'quiz')
        @if($task->peer_review_enabled)
            <div class="flex items-center gap-2 bg-purple-50 border border-purple-200 rounded-lg px-3 py-2 text-purple-700 text-xs">
                <i class="fas fa-users"></i>
                <span>This task uses <strong>Peer Review</strong>. Your submission will be reviewed by {{ $task->required_reviews_count ?? 1 }} classmate(s) before it's approved.</span>
            </div>
        @endif

        <button type="submit"
                class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-bold px-6 py-2.5 rounded-xl transition shadow-sm text-sm">
            <i class="fas fa-paper-plane"></i> Submit
        </button>
    @endif
</form>
