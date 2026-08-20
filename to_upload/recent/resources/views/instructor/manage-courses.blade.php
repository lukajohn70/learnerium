@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Manage Courses</h1>
            <p class="text-gray-500 mt-1">Edit, publish, and track all your courses.</p>
        </div>
        <a href="{{ route('instructor.courses.create') }}" class="inline-flex items-center gap-2 bg-primary-jlm text-white px-5 py-2.5 rounded-xl font-semibold hover:bg-primary-jlm-dark transition shadow text-sm">
            <i class="fas fa-plus"></i>Create New Course
        </a>
    </div>

    @if(session('status'))
        <div class="mb-6 bg-emerald-50 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-xl flex items-center space-x-3">
            <i class="fas fa-check-circle text-emerald-500"></i>
            <span class="font-medium text-sm">{{ session('status') }}</span>
        </div>
    @endif

    @forelse ($courses as $course)
        @php $avgProgress = $course->enrollments->count() ? round($course->enrollments->avg('progress_percentage')) : 0; @endphp
        <div class="bg-white rounded-2xl shadow-md overflow-hidden mb-5 hover:shadow-lg transition">
            <div class="p-5 flex flex-col sm:flex-row gap-5">
                <!-- Thumbnail -->
                <div class="w-full sm:w-32 h-32 rounded-xl overflow-hidden flex-shrink-0">
                    <img src="{{ $course->thumbnail ?? 'https://placehold.co/128x128/1b2299/f7de7a?text=C' }}"
                         alt="{{ $course->title }}" class="w-full h-full object-cover">
                </div>

                <!-- Info -->
                <div class="flex-grow">
                    <div class="flex items-start justify-between flex-wrap gap-2 mb-2">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">{{ $course->title }}</h2>
                            <div class="flex flex-wrap gap-2 mt-1">
                                <span class="text-xs font-semibold bg-primary-jlm/10 text-primary-jlm px-2.5 py-1 rounded-full capitalize">{{ $course->level }}</span>
                                <span class="text-xs font-semibold bg-purple-100 text-purple-700 px-2.5 py-1 rounded-full"><i class="fas fa-list-ul mr-1"></i>{{ $course->lessons->count() }} Lessons</span>
                                @if($course->published_at)
                                    <span class="text-xs font-semibold bg-green-100 text-green-700 px-2.5 py-1 rounded-full"><i class="fas fa-check mr-1"></i>Published</span>
                                @else
                                    <span class="text-xs font-semibold bg-yellow-100 text-yellow-700 px-2.5 py-1 rounded-full"><i class="fas fa-clock mr-1"></i>Draft</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-extrabold text-primary-jlm">{{ $course->enrollments->count() }}</p>
                            <p class="text-xs text-gray-400">students</p>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <div class="flex justify-between mb-1">
                            <span class="text-xs text-gray-500">Avg. Student Progress</span>
                            <span class="text-xs font-bold text-primary-jlm">{{ $avgProgress }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-gradient-to-r from-primary-jlm to-secondary-jlm h-2 rounded-full" style="width: {{ $avgProgress }}%"></div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('instructor.courses.edit', $course) }}" class="bg-secondary-jlm text-white px-4 py-2 rounded-xl font-semibold text-xs hover:bg-secondary-jlm/90 transition shadow-sm">
                            <i class="fas fa-edit mr-1"></i>Edit Content & Quizzes
                        </a>
                        <a href="{{ route('instructor.courses.students', $course->id) }}" class="border border-primary-jlm text-primary-jlm px-4 py-2 rounded-xl font-semibold text-xs hover:bg-primary-jlm/5 transition">
                            <i class="fas fa-users mr-1"></i>Students
                        </a>
                        <a href="{{ route('course.detail', $course->slug) }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-xl font-semibold text-xs hover:bg-gray-100 transition">
                            <i class="fas fa-eye mr-1"></i>Preview
                        </a>
                        @if(!$course->published_at)
                            <form action="{{ route('instructor.courses.publish', $course->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-xl font-semibold text-xs hover:bg-green-600 transition">
                                    <i class="fas fa-rocket mr-1"></i>Publish
                                </button>
                            </form>
                        @else
                            <span class="border border-green-200 bg-green-50 text-green-600 px-4 py-2 rounded-xl font-semibold text-xs">
                                Published {{ $course->published_at->format('M d, Y') }}
                            </span>
                        @endif

                        <!-- Delete Course Button -->
                        <form action="{{ route('instructor.courses.destroy', $course) }}" method="POST" class="inline" onsubmit="return confirm('⚠️ Are you sure you want to PERMANENTLY DELETE &quot;{{ $course->title }}&quot;?\n\nThis will remove all modules, lessons, quizzes, materials, and student progress records!');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 px-3.5 py-2 rounded-xl font-semibold text-xs transition shadow-2xs" title="Delete Course">
                                <i class="fas fa-trash-alt mr-1"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl shadow-md p-16 text-center">
            <div class="text-6xl text-gray-200 mb-4"><i class="fas fa-book-open"></i></div>
            <h2 class="text-xl font-bold text-gray-700 mb-2">No courses yet.</h2>
            <p class="text-gray-400 mb-6">Create your first course and start teaching.</p>
            <a href="{{ route('instructor.courses.create') }}" class="bg-primary-jlm text-white px-8 py-3 rounded-xl font-semibold hover:bg-primary-jlm-dark transition shadow-md">
                Create Course
            </a>
        </div>
    @endforelse

    <div class="mt-8">
        <a href="{{ route('instructor.dashboard') }}" class="text-primary-jlm hover:text-secondary-jlm font-semibold transition">
            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
        </a>
    </div>
</div>
@endsection
