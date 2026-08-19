@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">My Courses</h1>
            <p class="text-gray-500 mt-1">Continue where you left off.</p>
        </div>
        <a href="{{ route('courses') }}" class="inline-flex items-center gap-2 bg-secondary-jlm text-white px-5 py-2.5 rounded-xl font-semibold hover:bg-secondary-jlm/90 transition shadow text-sm">
            <i class="fas fa-search"></i>Explore More Courses
        </a>
    </div>

    @if($courses->isEmpty())
        <div class="bg-white rounded-2xl shadow-md p-16 text-center">
            <div class="text-6xl text-gray-200 mb-4"><i class="fas fa-book-open"></i></div>
            <h2 class="text-xl font-bold text-gray-700 mb-2">You're not enrolled in any courses yet.</h2>
            <p class="text-gray-400 mb-6">Start your learning journey today!</p>
            <a href="{{ route('courses') }}" class="bg-secondary-jlm text-white px-8 py-3 rounded-xl font-semibold hover:bg-secondary-jlm/90 transition shadow-md">
                Browse Courses
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach ($courses as $course)
                <div class="bg-white rounded-2xl shadow-md overflow-hidden flex flex-col hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                    <div class="relative h-44 overflow-hidden">
                        <img src="{{ $course->thumbnail ?? 'https://placehold.co/400x250/1b2299/f7de7a?text='.urlencode($course->title) }}" 
                             alt="{{ $course->title }}" 
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                        <div class="absolute bottom-3 left-3">
                            <span class="bg-white/90 text-primary-jlm text-xs font-bold px-2.5 py-1 rounded-full">{{ $course->level }}</span>
                        </div>
                    </div>
                    <div class="p-5 flex-grow flex flex-col">
                        <h2 class="text-lg font-bold text-gray-900 mb-1 line-clamp-2">{{ $course->title }}</h2>
                        <p class="text-sm text-gray-500 mb-4">by {{ $course->instructor?->name ?? 'Instructor' }}</p>

                        <!-- Progress Bar -->
                        <div class="mb-4 mt-auto">
                            <div class="flex justify-between items-center mb-1.5">
                                <span class="text-xs text-gray-500 font-medium">Progress</span>
                                <span class="text-xs font-bold text-primary-jlm">{{ $course->pivot?->progress_percentage ?? 0 }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5">
                                <div class="bg-gradient-to-r from-primary-jlm to-secondary-jlm h-2.5 rounded-full transition-all duration-500"
                                     style="width: {{ $course->pivot?->progress_percentage ?? 0 }}%"></div>
                            </div>
                        </div>

                        <a href="{{ route('course.detail', $course->slug) }}" 
                           class="w-full text-center bg-primary-jlm text-white py-2.5 rounded-xl font-semibold hover:bg-primary-jlm-dark transition text-sm">
                            <i class="fas fa-play mr-2"></i>
                            {{ ($course->pivot?->progress_percentage ?? 0) > 0 ? 'Continue Learning' : 'Start Course' }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="mt-8">
        <a href="{{ route('student.dashboard') }}" class="text-primary-jlm hover:text-secondary-jlm font-semibold transition">
            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
        </a>
    </div>
</div>
@endsection
