@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="mb-6">
        <a href="{{ route('instructor.courses.edit', $lesson->course_id) }}" class="text-primary-jlm hover:text-secondary-jlm font-semibold transition text-sm">
            <i class="fas fa-arrow-left mr-2"></i>Back to Course
        </a>
    </div>

    @if(session('status'))
        <div class="mb-6 bg-emerald-50 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-2 text-sm font-medium">
            <i class="fas fa-check-circle text-emerald-500"></i>{{ session('status') }}
        </div>
    @endif

    <div class="max-w-4xl mx-auto space-y-8">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">Manage Lesson Tasks & Gates</h1>
            <p class="text-gray-500 mt-1">Add tasks (Link submission, file uploads, surveys) that students must complete to progress past: <span class="font-semibold text-primary-jlm">{{ $lesson->title }}</span></p>
        </div>

        <!-- Add Task Form Card -->
        <div class="bg-white rounded-2xl shadow-md overflow-hidden border-t-4 border-green-500">
            <div class="bg-gray-50 border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-plus-circle mr-2 text-green-500"></i>Add New Task / Prerequisite</h2>
            </div>
            <form action="{{ route('lessons.tasks.store', $lesson->id) }}" method="POST" class="p-6 space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-1.5">Task Title <span class="text-red-500">*</span></label>
                        <input id="title" name="title" type="text" required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-400/30 focus:border-green-500 transition text-gray-800"
                               placeholder="e.g., Practical Assignment: Implement Your First Working Prototype">
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-semibold text-gray-700 mb-1.5">Task Type <span class="text-red-500">*</span></label>
                        <select id="type" name="type" required onchange="toggleTaskOptions(this.value)"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-400/30 focus:border-green-500 transition text-gray-800 bg-white">
                            <option value="link">🔗 Submit URL / Live Project Link</option>
                            <option value="file">📁 Upload Document or Code Archive (PDF, ZIP, DOCX)</option>
                            <option value="survey">📋 Complete Reflection Survey / Q&A</option>
                            <option value="quiz">📝 Pass Lesson Quiz</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">Task Instructions & Evaluation Criteria</label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-400/30 focus:border-green-500 transition text-gray-800 resize-none text-sm"
                              placeholder="Detail the expected deliverable, requirements, criteria for passing, and any reference materials needed to complete this task successfully..."></textarea>
                </div>

                <!-- Survey Questions Fields (Hidden by default) -->
                <div id="survey-questions-section" style="display:none;">
                    <label for="survey_questions" class="block text-sm font-semibold text-gray-700 mb-1.5">Survey Questions (One per line) <span class="text-red-500">*</span></label>
                    <textarea id="survey_questions" name="survey_questions" rows="4"
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-400/30 focus:border-green-500 transition text-gray-800 text-sm"
                              placeholder="e.g., What was the most valuable insight from this lesson?&#10;What challenges did you face during the practical exercise?&#10;How will you apply these concepts in your work?"></textarea>
                </div>


                <!-- Peer Review Options (Visible for link & file types) -->
                <div id="peer-review-section" class="space-y-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="peer_review_enabled" id="peer_review_enabled" value="1" class="sr-only peer" onchange="toggleReviewsCount(this.checked)">
                            <div class="w-11 h-6 bg-gray-200 peer-checked:bg-green-500 rounded-full transition-colors duration-200"></div>
                            <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform duration-200"></div>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">Enable Peer Review for this task</span>
                    </label>

                    <div id="reviews-count-section" style="display:none;" class="max-w-xs">
                        <label for="required_reviews_count" class="block text-xs font-bold text-gray-500 mb-1">Required Peer Reviews Count</label>
                        <input id="required_reviews_count" name="required_reviews_count" type="number" min="1" value="1"
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400/30 focus:border-green-500 transition text-gray-800 text-sm">
                        <p class="text-xs text-gray-400 mt-1">Number of review approvals needed from other students to pass.</p>
                    </div>
                </div>

                <label class="flex items-center gap-3 cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" name="is_required" value="1" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-checked:bg-green-500 rounded-full transition-colors duration-200"></div>
                        <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform duration-200"></div>
                    </div>
                    <span class="text-sm font-semibold text-gray-700">Required (gates the next lesson/section)</span>
                </label>

                <div class="pt-4 border-t border-gray-100 flex gap-3">
                    <button type="submit" class="bg-green-500 text-white px-8 py-3 rounded-xl font-bold hover:bg-green-600 transition shadow-md">
                        <i class="fas fa-plus mr-2"></i>Create Task
                    </button>
                </div>
            </form>
        </div>

        <!-- Task List Card -->
        @if($tasks->count() > 0)
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-tasks mr-2 text-primary-jlm"></i>Existing Lesson Tasks ({{ $tasks->count() }})</h2>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($tasks as $task)
                <div class="px-6 py-4 hover:bg-gray-50 transition">
                    <div class="flex items-center justify-between gap-4 flex-wrap">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-primary-jlm/10 text-primary-jlm flex items-center justify-center flex-shrink-0">
                                @if($task->type === 'link')
                                    <i class="fas fa-link"></i>
                                @elseif($task->type === 'file')
                                    <i class="fas fa-file-upload"></i>
                                @elseif($task->type === 'survey')
                                    <i class="fas fa-poll"></i>
                                @else
                                    <i class="fas fa-graduation-cap"></i>
                                @endif
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $task->title }}</p>
                                <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full capitalize font-medium">{{ $task->type }}</span>
                                    @if($task->is_required)
                                        <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full font-semibold">Required</span>
                                    @else
                                        <span class="text-xs bg-gray-100 text-gray-400 px-2 py-0.5 rounded-full">Optional</span>
                                    @endif
                                    @if($task->peer_review_enabled)
                                        <span class="text-xs bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full font-semibold"><i class="fas fa-users mr-1"></i>Peer Review ({{ $task->required_reviews_count }} approvals)</span>
                                    @endif
                                    @php $pendingCount = $task->submissions()->where('status', 'submitted')->count(); @endphp
                                    @if($pendingCount > 0)
                                        <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-semibold animate-pulse"><i class="fas fa-bell mr-1"></i>{{ $pendingCount }} pending</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 ml-auto">
                            <button onclick="document.getElementById('subs-{{ $task->id }}').classList.toggle('hidden')"
                                    class="bg-blue-50 text-blue-600 border border-blue-200 px-3.5 py-1.5 rounded-lg text-xs font-semibold hover:bg-blue-100 transition">
                                <i class="fas fa-inbox mr-1"></i> Submissions
                            </button>
                            <a href="{{ route('lessons.tasks.edit', [$lesson->id, $task->id]) }}"
                               class="bg-secondary-jlm text-white px-3.5 py-1.5 rounded-lg text-xs font-semibold hover:bg-secondary-jlm/90 transition shadow-sm">
                                Edit
                            </a>
                            <form action="{{ route('lessons.tasks.destroy', [$lesson->id, $task->id]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Delete this task? Students will no longer be gated by it.')"
                                        class="text-red-400 hover:text-red-600 px-2 py-1.5 rounded-lg hover:bg-red-50 transition text-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Submissions Panel (hidden by default) -->
                    <div id="subs-{{ $task->id }}" class="hidden mt-4 border-t border-dashed border-gray-200 pt-4">
                        @php $submissions = $task->submissions()->with('user')->latest()->get(); @endphp
                        @if($submissions->isEmpty())
                            <p class="text-sm text-gray-400 italic">No submissions yet.</p>
                        @else
                            <div class="space-y-3">
                                @foreach($submissions as $sub)
                                <div class="flex items-start gap-4 bg-gray-50 border border-gray-200 rounded-xl p-3 flex-wrap">
                                    <div class="flex-grow">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-semibold text-sm text-gray-800">{{ $sub->user->name }}</span>
                                            @if($sub->status === 'approved')
                                                <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-semibold">✓ Approved</span>
                                            @elseif($sub->status === 'rejected')
                                                <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full font-semibold">✗ Rejected</span>
                                            @else
                                                <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-semibold">⏳ Pending</span>
                                            @endif
                                            <span class="text-xs text-gray-400 ml-auto">{{ $sub->created_at->diffForHumans() }}</span>
                                        </div>
                                        @if($task->type === 'link')
                                            <a href="{{ $sub->submission_value }}" target="_blank" class="text-sm text-blue-600 underline hover:text-blue-800 break-all">{{ $sub->submission_value }}</a>
                                        @elseif($task->type === 'file')
                                            <p class="text-sm text-gray-700"><i class="fas fa-file mr-1 text-gray-400"></i>{{ $sub->file_name }}</p>
                                        @elseif($task->type === 'survey')
                                            @php $answers = json_decode($sub->submission_value, true); @endphp
                                            @if(is_array($answers))
                                                @foreach($answers as $q => $a)
                                                    <p class="text-xs text-gray-600 mt-1"><span class="font-semibold">Q{{ $loop->iteration }}:</span> {{ $a }}</p>
                                                @endforeach
                                            @endif
                                        @endif
                                    </div>
                                    @if($sub->status !== 'approved')
                                    <div class="flex gap-2 flex-shrink-0 self-center">
                                        <form action="{{ route('instructor.tasks.approve', [$task->id, $sub->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-green-500 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-green-600 transition">
                                                <i class="fas fa-check mr-1"></i>Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('instructor.tasks.reject', [$task->id, $sub->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-red-400 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-red-600 transition">
                                                <i class="fas fa-times mr-1"></i>Reject
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function toggleTaskOptions(type) {
    const surveySection = document.getElementById('survey-questions-section');
    const peerSection = document.getElementById('peer-review-section');
    const surveyTextarea = document.getElementById('survey_questions');

    if (type === 'survey') {
        surveySection.style.display = 'block';
        surveyTextarea.required = true;
        peerSection.style.display = 'none';
    } else {
        surveySection.style.display = 'none';
        surveyTextarea.required = false;
        if (type === 'link' || type === 'file') {
            peerSection.style.display = 'block';
        } else {
            peerSection.style.display = 'none';
        }
    }
}

function toggleReviewsCount(checked) {
    document.getElementById('reviews-count-section').style.display = checked ? 'block' : 'none';
}
</script>
@endsection
