@extends('layouts.app')

@section('title', 'Manage Courses — Admin Learnerium')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-book-open text-pink-600"></i> Course Management
                </h1>
                <p class="text-xs text-gray-500 mt-1">Review all published and draft courses across the platform.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-xl text-xs font-bold shadow-sm hover:bg-gray-50 transition">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        @if(session('status'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                <i class="fas fa-check-circle text-emerald-500"></i>{{ session('status') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden divide-y divide-gray-100">
            @forelse($courses as $course)
            <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 hover:bg-gray-50 transition">
                <div class="flex items-center gap-4 min-w-0">
                    <img src="{{ $course->thumbnailUrl() }}" class="w-20 h-14 object-cover rounded-xl border border-gray-200 flex-shrink-0">
                    <div class="min-w-0">
                        <a href="{{ route('course.detail', $course->slug) }}" class="text-gray-900 font-extrabold text-base hover:text-pink-600 transition truncate block">
                            {{ $course->title }}
                        </a>
                        <p class="text-xs text-gray-500 mt-0.5">
                            By {{ $course->instructor->name ?? 'Instructor' }} &bull;
                            <span class="font-bold text-gray-800">₦{{ number_format($course->price, 2) }}</span> &bull;
                            {{ $course->enrollments->count() }} Enrolled Students
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    <span class="text-xs px-3 py-1 rounded-full font-bold {{ $course->published_at ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $course->published_at ? 'Published' : 'Draft' }}
                    </span>
                    <a href="{{ route('course.detail', $course->slug) }}" target="_blank"
                       class="text-xs bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 px-3.5 py-2 rounded-xl font-bold transition shadow-sm flex items-center gap-1.5" title="View Course Page">
                        <i class="fas fa-eye text-gray-400"></i> View
                    </a>
                    <a href="{{ route('student.certificate.view', $course) }}" target="_blank"
                       class="text-xs bg-amber-50 border border-amber-200 hover:bg-amber-100 text-amber-800 px-3.5 py-2 rounded-xl font-bold transition shadow-sm flex items-center gap-1.5" title="Preview Course Certificate">
                        <i class="fas fa-certificate text-amber-500"></i> Certificate
                    </a>
                    <form action="{{ route('admin.courses.toggle', $course) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-xl font-bold transition shadow-sm">
                            {{ $course->published_at ? 'Unpublish' : 'Publish' }}
                        </button>
                    </form>

                </div>
            </div>
            @empty
            <div class="p-12 text-center text-gray-400">No courses found.</div>
            @endforelse
        </div>

        @if($courses->hasPages())
            <div class="mt-6">
                {{ $courses->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
