@extends('layouts.app')

@section('title', 'Student Submissions — Instructor Inbox')

@section('content')
<div class="container mx-auto px-4 py-10 max-w-6xl">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('instructor.dashboard') }}" class="text-gray-400 hover:text-primary-jlm transition"><i class="fas fa-arrow-left"></i></a>
                <h1 class="text-2xl font-extrabold text-gray-900">Student Submissions Inbox</h1>
            </div>
            <p class="text-sm text-gray-500 mt-1">Review, grade, and approve student task submissions across all your courses.</p>
        </div>
    </div>

    @if(session('status'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl text-sm font-medium flex items-center gap-2">
            <i class="fas fa-check-circle text-emerald-500"></i>{{ session('status') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        @if($submissions->isEmpty())
            <div class="p-16 text-center text-gray-400">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400 text-2xl">
                    <i class="fas fa-tasks"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-700 mb-1">No Submissions Yet</h3>
                <p class="text-sm text-gray-400">When students submit task assignments for your courses, they will appear here for grading.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-500 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4">Student</th>
                            <th class="px-6 py-4">Course & Lesson</th>
                            <th class="px-6 py-4">Task</th>
                            <th class="px-6 py-4">Submission Content</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($submissions as $sub)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $sub->user->avatarUrl() }}" class="w-9 h-9 rounded-full object-cover border border-gray-200">
                                    <div>
                                        <p class="font-bold text-gray-900 leading-tight">{{ $sub->user->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $sub->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-800">{{ $sub->task->lesson->course->title ?? 'Course' }}</p>
                                <p class="text-xs text-gray-400">{{ $sub->task->lesson->title ?? 'Lesson' }}</p>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-700">
                                {{ $sub->task->title ?? 'Task' }}
                            </td>
                            <td class="px-6 py-4 max-w-xs">
                                @if($sub->file_path)
                                    <a href="{{ asset($sub->file_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-primary-jlm font-bold hover:underline bg-blue-50 px-2.5 py-1 rounded-lg">
                                        <i class="fas fa-file-download"></i> View Attachment
                                    </a>
                                @elseif($sub->link)
                                    <a href="{{ $sub->link }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-blue-600 font-bold hover:underline bg-blue-50 px-2.5 py-1 rounded-lg truncate max-w-[200px]">
                                        <i class="fas fa-external-link-alt"></i> {{ $sub->link }}
                                    </a>
                                @elseif($sub->text_content)
                                    <p class="text-xs text-gray-600 line-clamp-2">{{ $sub->text_content }}</p>
                                @else
                                    <span class="text-xs text-gray-400">Completed</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($sub->status === 'approved')
                                    <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-2.5 py-1 rounded-full">Approved</span>
                                @elseif($sub->status === 'rejected')
                                    <span class="bg-rose-100 text-rose-700 text-xs font-bold px-2.5 py-1 rounded-full">Rejected</span>
                                @else
                                    <span class="bg-amber-100 text-amber-700 text-xs font-bold px-2.5 py-1 rounded-full">Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <form action="{{ route('instructor.tasks.approve', ['task' => $sub->task_id, 'submission' => $sub->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs px-3 py-1.5 rounded-xl font-bold transition shadow-sm">
                                            <i class="fas fa-check mr-1"></i>Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('instructor.tasks.reject', ['task' => $sub->task_id, 'submission' => $sub->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs px-3 py-1.5 rounded-xl font-bold transition">
                                            <i class="fas fa-times mr-1"></i>Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $submissions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
