@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900">My Progress</h1>
        <p class="text-gray-500 mt-1">Track your learning journey and achievements.</p>
    </div>

    @if($courses->isEmpty())
        <div class="bg-white rounded-2xl shadow-md p-16 text-center">
            <div class="text-6xl text-gray-200 mb-4"><i class="fas fa-chart-line"></i></div>
            <h2 class="text-xl font-bold text-gray-700 mb-2">No progress to show yet.</h2>
            <p class="text-gray-400 mb-6">Enroll in courses to start tracking your progress.</p>
            <a href="{{ route('courses') }}" class="bg-primary-jlm text-white px-8 py-3 rounded-xl font-semibold hover:bg-primary-jlm-dark transition shadow-md">
                Browse Courses
            </a>
        </div>
    @else
        <!-- Overall Summary -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-secondary-jlm">
                <div class="text-3xl font-extrabold text-secondary-jlm mb-1">{{ $courses->count() }}</div>
                <div class="text-sm text-gray-500 font-medium">Total Courses</div>
            </div>
            <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-green-500">
                <div class="text-3xl font-extrabold text-green-500 mb-1">{{ $courses->where('pivot.progress_percentage', 100)->count() }}</div>
                <div class="text-sm text-gray-500 font-medium">Completed</div>
            </div>
            <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-primary-jlm">
                <div class="text-3xl font-extrabold text-primary-jlm mb-1">{{ $courses->where('pivot.progress_percentage', '<', 100)->where('pivot.progress_percentage', '>', 0)->count() }}</div>
                <div class="text-sm text-gray-500 font-medium">In Progress</div>
            </div>
            <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-yellow-400">
                <div class="text-3xl font-extrabold text-yellow-500 mb-1">{{ $courses->count() > 0 ? round($courses->avg('pivot.progress_percentage')) : 0 }}%</div>
                <div class="text-sm text-gray-500 font-medium">Avg. Progress</div>
            </div>
        </div>

        <!-- Courses Progress List -->
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-tasks mr-2 text-primary-jlm"></i>Course Progress</h2>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach ($courses as $course)
                    @php $prog = $course->pivot?->progress_percentage ?? 0; @endphp
                    <div class="px-6 py-5 hover:bg-gray-50 transition">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-primary-jlm/10 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-graduation-cap text-primary-jlm text-lg"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $course->title }}</p>
                                    <p class="text-sm text-gray-400">by {{ $course->instructor?->name ?? 'Instructor' }}</p>
                                </div>
                            </div>
                            <div class="text-right ml-4">
                                @if($prog >= 100)
                                    <span class="inline-flex items-center gap-1.5 bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">
                                        <i class="fas fa-check-circle"></i>Completed
                                    </span>
                                @elseif($prog > 0)
                                    <span class="inline-flex items-center gap-1.5 bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">
                                        <i class="fas fa-spinner fa-pulse"></i>In Progress
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-500 px-3 py-1 rounded-full text-xs font-bold">
                                        <i class="fas fa-clock"></i>Not Started
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="flex-grow">
                                <div class="w-full bg-gray-100 rounded-full h-2.5">
                                    <div class="h-2.5 rounded-full transition-all duration-500 {{ $prog >= 100 ? 'bg-green-500' : 'bg-gradient-to-r from-primary-jlm to-secondary-jlm' }}"
                                         style="width: {{ $prog }}%"></div>
                                </div>
                            </div>
                            <span class="text-sm font-bold text-gray-700 w-12 text-right">{{ $prog }}%</span>
                            <a href="{{ route('course.detail', $course->slug) }}" class="bg-primary-jlm text-white px-4 py-1.5 rounded-lg text-xs font-semibold hover:bg-primary-jlm-dark transition whitespace-nowrap">
                                {{ $prog > 0 ? 'Continue' : 'Start' }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="mt-8">
        <a href="{{ route('student.dashboard') }}" class="text-primary-jlm hover:text-secondary-jlm font-semibold transition">
            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
        </a>
    </div>
</div>
@endsection
