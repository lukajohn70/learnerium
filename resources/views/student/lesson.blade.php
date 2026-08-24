@extends('layouts.app')

@section('title', $lesson->title . ' — ' . $course->title . ' — Learnerium')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Top breadcrumb bar -->
    <div class="mb-6 flex flex-wrap items-center gap-3">
        <a href="{{ route('course.detail', $course->slug) }}" class="text-sm text-primary-jlm hover:text-secondary-jlm font-semibold transition flex items-center gap-1">
            <i class="fas fa-arrow-left"></i> {{ $course->title }}
        </a>
        @if($progress)
            <div class="flex items-center gap-2 ml-auto">
                <span class="text-xs text-gray-400 font-medium">Overall Progress</span>
                <div class="w-32 bg-gray-100 rounded-full h-2">
                    <div class="bg-gradient-to-r from-primary-jlm to-secondary-jlm h-2 rounded-full" style="width: {{ $progress->progress_percentage }}%"></div>
                </div>
                <span class="text-xs font-bold text-primary-jlm">{{ $progress->progress_percentage }}%</span>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-3 space-y-6">
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">

                <!-- Smart Video/Media Player -->
                @if($lesson->video_url)
                    @php
                        $embedUrl = null;
                        $isIframe = false;
                        $rawUrl = trim($lesson->video_url);

                        if (preg_match('/drive\.google\.com\/(?:file\/d\/|open\?id=)([^\/\?&]+)/i', $rawUrl, $matches)) {
                            $embedUrl = 'https://drive.google.com/file/d/' . $matches[1] . '/preview';
                            $isIframe = true;
                        } elseif (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $rawUrl, $matches)) {
                            $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                            $isIframe = true;
                        } elseif (preg_match('/vimeo\.com\/(\d+)/i', $rawUrl, $matches)) {
                            $embedUrl = 'https://player.vimeo.com/video/' . $matches[1];
                            $isIframe = true;
                        } elseif (strpos($rawUrl, 'youtube.com/embed/') !== false) {
                            $embedUrl = $rawUrl;
                            $isIframe = true;
                        } else {
                            $embedUrl = $rawUrl;
                            $isIframe = false;
                        }
                    @endphp

                    <div class="bg-black">
                        @if($isIframe)
                            <div class="aspect-video w-full">
                                <iframe id="lessonVideoIframe" class="w-full h-full border-0" src="{{ $embedUrl . (strpos($embedUrl, '?') !== false ? '&' : '?') . 'enablejsapi=1' }}"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                            </div>
                        @else
                            <video id="lessonVideoPlayer" class="w-full max-h-[480px]" controls>
                                <source src="{{ $embedUrl }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @endif
                    </div>
                @endif

                <!-- Lesson Content -->
                <div class="p-6 md:p-8">
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-2">{{ $lesson->title }}</h1>
                    @if($lesson->description)
                        <p class="text-gray-500 mb-6 text-base leading-relaxed">{{ $lesson->description }}</p>
                    @endif

                    @if($lesson->content)
                        <div class="prose max-w-none mb-8 text-gray-700 leading-relaxed border-t border-gray-100 pt-6">
                            {!! $lesson->content !!}
                        </div>
                    @endif

                    <!-- Completion -->
                    @if(auth()->user()->id !== $course->instructor_id)
                        <div class="border-t border-gray-100 pt-6">
                            @if($lessonCompleted)
                                <div class="flex items-center gap-3 bg-green-50 border border-green-200 rounded-xl px-5 py-4 text-green-800">
                                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                                    <div>
                                        <p class="font-bold">Lesson Completed!</p>
                                    </div>
                                </div>
                            @elseif($pendingRequiredTask)
                                <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 rounded-xl px-5 py-4 text-amber-800">
                                    <i class="fas fa-lock text-amber-500 text-xl"></i>
                                    <div>
                                        <p class="font-bold">Complete Required Tasks Below</p>
                                        <p class="text-sm text-amber-600">You must complete all required tasks before marking this lesson complete.</p>
                                    </div>
                                </div>
                            @else
                                <form id="completeLessonForm" action="{{ route('lesson.complete', [$course, $lesson]) }}" method="POST">
                                    @csrf
                                    <button type="submit" id="completeLessonBtn"
                                            class="bg-primary-jlm hover:bg-primary-jlm-dark disabled:opacity-50 disabled:cursor-not-allowed text-white px-8 py-3 rounded-xl font-bold transition shadow-md flex items-center gap-2">
                                        <i class="fas fa-check"></i><span id="completeBtnTxt">Mark as Complete</span>
                                    </button>
                                </form>

                                @if($lesson->video_url && !$lessonCompleted)
                                    <div id="videoGateMsg" class="mt-3 text-xs font-semibold text-amber-800 bg-amber-50 border border-amber-200 px-4 py-2.5 rounded-xl inline-flex items-center gap-2">
                                        <i class="fas fa-play-circle text-amber-500"></i>
                                        <span>Video Requirement: Watch at least 80% of this video lesson to unlock completion (<span id="watchPercentTxt" class="font-bold">0%</span> watched).</span>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @else
                        <div class="border-t border-gray-100 pt-5">

                            <div class="flex items-center gap-3 bg-blue-50 border border-blue-200 rounded-xl px-5 py-4 text-blue-800">
                                <i class="fas fa-chalkboard-teacher text-blue-400 text-xl"></i>
                                <span class="font-medium">You are viewing this lesson as the course instructor.</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- ======================= TASK GATES ======================= -->
            @if($tasks->count() > 0 && auth()->user()->id !== $course->instructor_id)
                <div class="space-y-4">
                    <h2 class="text-xl font-extrabold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-tasks text-orange-500"></i> Lesson Tasks
                        <span class="text-sm font-normal text-gray-400 ml-1">({{ $tasks->where('is_required', true)->count() }} required)</span>
                    </h2>

                    @foreach($tasks as $task)
                        @php
                            $mySubmission = $userSubmissions[$task->id] ?? null;
                            $isApproved = $mySubmission && $mySubmission->status === 'approved';
                            $isPending  = $mySubmission && $mySubmission->status === 'submitted';
                            $isRejected = $mySubmission && $mySubmission->status === 'rejected';
                        @endphp

                        <div class="bg-white rounded-2xl shadow-sm border {{ $task->is_required ? 'border-orange-200' : 'border-gray-100' }} overflow-hidden">
                            <!-- Task Header -->
                            <div class="flex items-center gap-3 px-6 py-4 {{ $task->is_required ? 'bg-orange-50' : 'bg-gray-50' }} border-b border-gray-100">
                                @if($task->type === 'link')
                                    <i class="fas fa-link text-blue-500 text-lg w-6 text-center"></i>
                                @elseif($task->type === 'file')
                                    <i class="fas fa-upload text-green-500 text-lg w-6 text-center"></i>
                                @elseif($task->type === 'survey')
                                    <i class="fas fa-clipboard-list text-purple-500 text-lg w-6 text-center"></i>
                                @elseif($task->type === 'quiz')
                                    <i class="fas fa-question-circle text-indigo-500 text-lg w-6 text-center"></i>
                                @else
                                    <i class="fas fa-star-of-life text-yellow-500 text-lg w-6 text-center"></i>
                                @endif
                                <div class="flex-grow">
                                    <h3 class="font-bold text-gray-800 text-base">{{ $task->title }}</h3>
                                    @if($task->description)
                                        <p class="text-gray-500 text-sm">{{ $task->description }}</p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    @if($task->is_required)
                                        <span class="text-xs bg-orange-100 text-orange-700 px-2 py-0.5 rounded-full font-bold uppercase tracking-wide">Required</span>
                                    @else
                                        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full font-medium uppercase tracking-wide">Optional</span>
                                    @endif

                                    @if($isApproved)
                                        <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-semibold flex items-center gap-1"><i class="fas fa-check-circle"></i> Approved</span>
                                    @elseif($isPending)
                                        <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full font-semibold flex items-center gap-1"><i class="fas fa-clock"></i> Pending Review</span>
                                    @elseif($isRejected)
                                        <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full font-semibold flex items-center gap-1"><i class="fas fa-times-circle"></i> Rejected</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Task Body: Submission Form -->
                            <div class="px-6 py-5">
                                @if($isApproved)
                                    <div class="flex items-center gap-2 text-green-700 bg-green-50 rounded-lg px-4 py-3">
                                        <i class="fas fa-check-circle text-lg"></i>
                                        <div>
                                            <p class="font-semibold text-sm">Task Completed!</p>
                                            @if($mySubmission->submission_value && $task->type === 'link')
                                                <a href="{{ $mySubmission->submission_value }}" target="_blank" class="text-xs text-blue-600 underline hover:text-blue-800">View submitted link</a>
                                            @elseif($mySubmission->file_name)
                                                <p class="text-xs text-gray-500">File: {{ $mySubmission->file_name }}</p>
                                            @endif
                                        </div>
                                    </div>

                                @elseif($isPending)
                                    <div class="flex items-center gap-2 text-yellow-700 bg-yellow-50 rounded-lg px-4 py-3">
                                        <i class="fas fa-hourglass-half text-lg"></i>
                                        <div>
                                            <p class="font-semibold text-sm">Submission received — awaiting review.</p>
                                            @if($task->type === 'link')
                                                <a href="{{ $mySubmission->submission_value }}" target="_blank" class="text-xs text-blue-600 underline hover:text-blue-800">{{ $mySubmission->submission_value }}</a>
                                            @elseif($task->file_name)
                                                <p class="text-xs text-gray-500">File: {{ $mySubmission->file_name }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Peer Review: show other submissions to review -->
                                    @if($task->peer_review_enabled && isset($pendingReviews[$task->id]) && $pendingReviews[$task->id]->count() > 0)
                                        <div class="mt-4 border-t border-dashed border-gray-200 pt-4">
                                            <h4 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                                                <i class="fas fa-users text-purple-500"></i> Peer Reviews Needed
                                                <span class="text-xs text-gray-400 font-normal">(Review classmates' work to help them get approved)</span>
                                            </h4>
                                            @foreach($pendingReviews[$task->id] as $sub)
                                                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-3">
                                                    <div class="flex items-start justify-between gap-4 mb-3">
                                                        <div>
                                                            <p class="text-xs font-semibold text-gray-600 mb-1">Submission from: <span class="text-primary-jlm">{{ $sub->user->name }}</span></p>
                                                            @if($task->type === 'link')
                                                                <a href="{{ $sub->submission_value }}" target="_blank" class="text-sm text-blue-600 underline hover:text-blue-800 break-all">{{ $sub->submission_value }}</a>
                                                            @elseif($sub->file_name)
                                                                <p class="text-sm text-gray-700"><i class="fas fa-file mr-1"></i>{{ $sub->file_name }}</p>
                                                            @elseif($task->type === 'survey')
                                                                @php $answers = json_decode($sub->submission_value, true); @endphp
                                                                @if(is_array($answers))
                                                                    @foreach($answers as $q => $a)
                                                                        <p class="text-xs text-gray-600 mt-1"><span class="font-medium">Q{{ $loop->iteration }}:</span> {{ $a }}</p>
                                                                    @endforeach
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <form action="{{ route('student.tasks.peer-review', $sub->id) }}" method="POST" class="space-y-2">
                                                        @csrf
                                                        <div class="flex items-center gap-4">
                                                            <div>
                                                                <label class="block text-xs font-semibold text-gray-600 mb-1">Rating</label>
                                                                <select name="rating" class="border rounded-lg px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400">
                                                                    <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                                                                    <option value="4">⭐⭐⭐⭐ Good</option>
                                                                    <option value="3">⭐⭐⭐ Average</option>
                                                                    <option value="2">⭐⭐ Below average</option>
                                                                    <option value="1">⭐ Poor</option>
                                                                </select>
                                                            </div>
                                                            <div>
                                                                <label class="block text-xs font-semibold text-gray-600 mb-1">Decision</label>
                                                                <div class="flex gap-2">
                                                                    <label class="flex items-center gap-1 cursor-pointer">
                                                                        <input type="radio" name="approved" value="1" checked class="text-green-500"> <span class="text-sm text-green-700 font-semibold">Approve</span>
                                                                    </label>
                                                                    <label class="flex items-center gap-1 cursor-pointer">
                                                                        <input type="radio" name="approved" value="0" class="text-red-500"> <span class="text-sm text-red-700 font-semibold">Reject</span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <textarea name="feedback" rows="2" placeholder="Optional feedback for this student..." class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400 resize-none"></textarea>
                                                        <button type="submit" class="inline-flex items-center gap-1.5 bg-purple-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-purple-700 transition">
                                                            <i class="fas fa-paper-plane"></i> Submit Review
                                                        </button>
                                                    </form>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                @elseif($isRejected)
                                    <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3 mb-4">
                                        <p class="font-semibold text-red-700 text-sm flex items-center gap-2"><i class="fas fa-times-circle"></i> Submission was rejected. Please try again.</p>
                                        @if($mySubmission->feedback)
                                            <p class="text-sm text-red-600 mt-1">Feedback: {{ $mySubmission->feedback }}</p>
                                        @endif
                                    </div>
                                    {{-- Fall through to show form again --}}
                                    @include('student.partials.task-form', ['task' => $task])

                                @else
                                    {{-- No submission yet --}}
                                    @include('student.partials.task-form', ['task' => $task])
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            <!-- ======================= END TASK GATES ======================= -->

            <!-- ======================= LESSON DISCUSSIONS ======================= -->
            @php
                $discussions = $lesson->discussions()->whereNull('parent_id')->with(['user', 'replies.user'])->latest()->get();
            @endphp
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fas fa-comments text-primary-jlm text-lg"></i>
                    <h2 class="font-bold text-gray-800 text-base">Discussion ({{ $discussions->count() }})</h2>
                </div>

                <!-- Post new comment -->
                <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/50">
                    <form action="{{ route('lesson.discussion.store', [$course, $lesson]) }}" method="POST" class="flex gap-3">
                        @csrf
                        <img src="{{ auth()->user()->avatarUrl() }}" class="w-9 h-9 rounded-full border-2 border-gray-100 object-cover flex-shrink-0 mt-0.5">
                        <div class="flex-1">
                            <textarea name="comment" rows="2" placeholder="Share your thoughts, ask a question..."
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-jlm/30 focus:border-primary-jlm text-sm resize-none text-gray-800" required></textarea>
                            <div class="mt-2 flex justify-end">
                                <button type="submit" class="bg-primary-jlm hover:bg-primary-jlm-dark text-white px-5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                                    <i class="fas fa-paper-plane"></i> Post Comment
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Comments List -->
                <div class="divide-y divide-gray-50">
                    @forelse($discussions as $comment)
                    <div class="px-6 py-4" id="comment-{{ $comment->id }}">
                        <div class="flex gap-3">
                            <img src="{{ $comment->user->avatarUrl() }}" class="w-9 h-9 rounded-full object-cover border-2 border-gray-100 flex-shrink-0 mt-0.5">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1 flex-wrap">
                                    <span class="font-bold text-sm text-gray-800">{{ $comment->user->name }}</span>
                                    @if($comment->user->id === $course->instructor_id)
                                        <span class="bg-primary-jlm/10 text-primary-jlm text-[10px] font-bold px-1.5 py-0.5 rounded-md">Instructor</span>
                                    @endif
                                    <span class="text-[10px] text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-700 leading-relaxed">{{ $comment->comment }}</p>

                                <div class="mt-2 flex items-center gap-3">
                                    <button onclick="toggleReplyForm({{ $comment->id }})" class="text-[10px] text-primary-jlm font-semibold hover:underline flex items-center gap-1">
                                        <i class="fas fa-reply"></i> Reply
                                    </button>
                                    @if(auth()->user()->id === $comment->user_id || auth()->user()->role === 'admin')
                                        <form action="{{ route('lesson.discussion.destroy', $comment) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Delete this comment?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-[10px] text-red-400 font-semibold hover:underline flex items-center gap-1">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <!-- Reply Form (hidden by default) -->
                                <div id="reply-form-{{ $comment->id }}" class="hidden mt-3">
                                    <form action="{{ route('lesson.discussion.store', [$course, $lesson]) }}" method="POST" class="flex gap-2">
                                        @csrf
                                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                        <textarea name="comment" rows="1" placeholder="Write a reply..."
                                            class="flex-1 px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-jlm/30 text-xs resize-none text-gray-800" required></textarea>
                                        <button type="submit" class="bg-primary-jlm text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-primary-jlm-dark transition flex-shrink-0">
                                            <i class="fas fa-reply"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Nested Replies -->
                                @if($comment->replies->count() > 0)
                                <div class="mt-3 pl-4 border-l-2 border-gray-100 space-y-3">
                                    @foreach($comment->replies as $reply)
                                    <div class="flex gap-2.5">
                                        <img src="{{ $reply->user->avatarUrl() }}" class="w-7 h-7 rounded-full object-cover border border-gray-100 flex-shrink-0">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-0.5 flex-wrap">
                                                <span class="font-bold text-xs text-gray-800">{{ $reply->user->name }}</span>
                                                @if($reply->user->id === $course->instructor_id)
                                                    <span class="bg-primary-jlm/10 text-primary-jlm text-[10px] font-bold px-1 rounded">Instructor</span>
                                                @endif
                                                <span class="text-[10px] text-gray-400">{{ $reply->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-xs text-gray-700">{{ $reply->comment }}</p>
                                            @if(auth()->user()->id === $reply->user_id || auth()->user()->role === 'admin')
                                                <form action="{{ route('lesson.discussion.destroy', $reply) }}" method="POST" class="mt-1 inline"
                                                    onsubmit="return confirm('Delete this reply?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-[10px] text-red-400 hover:underline">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-10 text-center text-gray-400">
                        <i class="fas fa-comment-slash text-4xl mb-3 opacity-30 block"></i>
                        <p class="text-sm">Be the first to start a discussion on this lesson!</p>
                    </div>
                    @endforelse
                </div>
            </div>
            <!-- ======================= END DISCUSSIONS ======================= -->

        </div>

        <!-- Sidebar: Module & Lesson Navigation -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-md p-5 sticky top-24">
                <h3 class="text-base font-bold text-gray-800 mb-4 flex items-center justify-between">
                    <span class="flex items-center gap-2"><i class="fas fa-layer-group text-primary-jlm"></i>Course Curriculum</span>
                </h3>

                <div class="space-y-4 max-h-[65vh] overflow-y-auto pr-1">
                    @if($course->modules->count() > 0)
                        @foreach($course->modules as $modIndex => $mod)
                            @php
                                $modUnlocked = $mod->isUnlockedFor(auth()->user());
                                $modCompleted = $mod->isCompletedBy(auth()->user());
                            @endphp
                            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-xs">
                                <div class="bg-gray-50 px-3.5 py-2.5 border-b border-gray-200 flex items-center justify-between text-xs font-bold">
                                    <div class="flex items-center gap-2 text-gray-800 truncate">
                                        @if(!$modUnlocked)
                                            <i class="fas fa-lock text-amber-500 flex-shrink-0" title="Module Locked"></i>
                                        @elseif($modCompleted)
                                            <i class="fas fa-check-circle text-emerald-500 flex-shrink-0" title="Module Completed"></i>
                                        @else
                                            <i class="fas fa-book-open text-primary-jlm flex-shrink-0"></i>
                                        @endif
                                        <span class="truncate">M{{ $modIndex + 1 }}: {{ $mod->title }}</span>
                                    </div>
                                    @if(!$modUnlocked)
                                        <span class="text-[10px] bg-amber-100 text-amber-800 font-bold px-2 py-0.5 rounded-full flex-shrink-0">Locked</span>
                                    @endif
                                </div>
                                <div class="divide-y divide-gray-100">
                                    @foreach($mod->lessons as $item)
                                        @php
                                            $itemUnlocked = $modUnlocked;
                                            $itemProgress = $item->userProgress(auth()->user());
                                            $isCompleted = $itemProgress && $itemProgress->completed;
                                        @endphp
                                        @if($itemUnlocked)
                                            <a href="{{ route('lesson.show', [$course, $item]) }}"
                                               class="flex items-center justify-between px-3.5 py-2 text-xs transition {{ $item->id === $lesson->id ? 'bg-primary-jlm text-white font-bold' : 'hover:bg-gray-50 text-gray-700' }}">
                                                <div class="flex items-center gap-2 truncate">
                                                    @if($isCompleted)
                                                        <i class="fas fa-check-circle text-emerald-500 text-xs flex-shrink-0"></i>
                                                    @else
                                                        <i class="far fa-circle text-gray-300 text-xs flex-shrink-0"></i>
                                                    @endif
                                                    <span class="truncate">{{ $item->title }}</span>
                                                </div>
                                                @if($item->tasks->where('is_required', true)->count() > 0)
                                                    <i class="fas fa-tasks text-orange-400 text-[10px] ml-1 flex-shrink-0"></i>
                                                @endif
                                            </a>
                                        @else
                                            <div class="flex items-center justify-between px-3.5 py-2 text-xs text-gray-400 bg-gray-50/50 cursor-not-allowed">
                                                <div class="flex items-center gap-2 truncate">
                                                    <i class="fas fa-lock text-gray-300 text-xs flex-shrink-0"></i>
                                                    <span class="truncate">{{ $item->title }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        @foreach($lessons as $item)
                            <a href="{{ route('lesson.show', [$course, $item]) }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition {{ $item->id === $lesson->id ? 'bg-primary-jlm text-white font-bold' : 'hover:bg-gray-50 text-gray-700' }}">
                                <span class="truncate">{{ $item->title }}</span>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('status'))
    <div id="flash-msg" class="fixed bottom-6 right-6 bg-green-600 text-white px-6 py-3 rounded-2xl shadow-xl flex items-center gap-2 z-50 animate-fade-in">
        <i class="fas fa-check-circle"></i> {{ session('status') }}
    </div>
    <script>setTimeout(() => { const el = document.getElementById('flash-msg'); if(el) el.remove(); }, 4000);</script>
@endif
@if(session('error'))
    <div id="flash-err" class="fixed bottom-6 right-6 bg-red-600 text-white px-6 py-3 rounded-2xl shadow-xl flex items-center gap-2 z-50">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
    <script>setTimeout(() => { const el = document.getElementById('flash-err'); if(el) el.remove(); }, 5000);</script>
@endif
<script>
function toggleReplyForm(commentId) {
    const form = document.getElementById('reply-form-' + commentId);
    if (form) {
        form.classList.toggle('hidden');
        if (!form.classList.contains('hidden')) {
            form.querySelector('textarea').focus();
        }
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const hasVideo = {{ $lesson->video_url && !$lessonCompleted ? 'true' : 'false' }};
    const completeBtn = document.getElementById('completeLessonBtn');
    const completeBtnTxt = document.getElementById('completeBtnTxt');
    const watchPercentTxt = document.getElementById('watchPercentTxt');
    const videoGateMsg = document.getElementById('videoGateMsg');
    
    if (hasVideo && completeBtn) {
        let maxPercentWatched = 0;
        const requiredPercent = 80;

        function unlockCompletion() {
            completeBtn.disabled = false;
            if (completeBtnTxt) completeBtnTxt.textContent = 'Mark as Complete';
            if (videoGateMsg) {
                videoGateMsg.className = 'mt-3 text-xs font-semibold text-emerald-800 bg-emerald-50 border border-emerald-200 px-4 py-2.5 rounded-xl inline-flex items-center gap-2';
                videoGateMsg.innerHTML = '<i class="fas fa-check-circle text-emerald-500"></i><span>Video Requirement Met (80%+ watched)! You can now mark this lesson as complete.</span>';
            }
        }

        // Lock button initially
        completeBtn.disabled = true;

        // 1. Native HTML5 Video Player
        const videoPlayer = document.getElementById('lessonVideoPlayer');
        if (videoPlayer) {
            videoPlayer.addEventListener('timeupdate', function () {
                if (videoPlayer.duration > 0) {
                    const currentPercent = Math.min(100, Math.round((videoPlayer.currentTime / videoPlayer.duration) * 100));
                    if (currentPercent > maxPercentWatched) {
                        maxPercentWatched = currentPercent;
                        if (watchPercentTxt) watchPercentTxt.textContent = maxPercentWatched + '%';
                    }
                    if (maxPercentWatched >= requiredPercent) {
                        unlockCompletion();
                    }
                }
            });
            videoPlayer.addEventListener('ended', function () {
                maxPercentWatched = 100;
                if (watchPercentTxt) watchPercentTxt.textContent = '100%';
                unlockCompletion();
            });
        }

        // 2. Embedded Video Watch Timer (YouTube / Vimeo / External)
        const iframe = document.getElementById('lessonVideoIframe');
        if (iframe) {
            let activeWatchSeconds = 0;
            const targetWatchSeconds = Math.min(120, Math.max(30, {{ (int)($lesson->duration_minutes ? $lesson->duration_minutes * 60 * 0.8 : 60) }}));
            
            const watchInterval = setInterval(() => {
                activeWatchSeconds += 1;
                const estPercent = Math.min(100, Math.round((activeWatchSeconds / targetWatchSeconds) * 100));
                if (estPercent > maxPercentWatched) {
                    maxPercentWatched = estPercent;
                    if (watchPercentTxt) watchPercentTxt.textContent = maxPercentWatched + '%';
                }
                if (maxPercentWatched >= requiredPercent) {
                    clearInterval(watchInterval);
                    unlockCompletion();
                }
            }, 1000);
        }
    }
});
</script>
@endsection


