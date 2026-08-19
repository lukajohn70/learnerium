@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900"><i class="fas fa-award mr-3 text-yellow-400"></i>My Certificates</h1>
        <p class="text-gray-500 mt-1">Certificates you've earned for completing courses.</p>
    </div>

    @php $completedCourses = $courses->where('pivot.progress_percentage', 100); @endphp

    @if($completedCourses->isEmpty())
        <div class="bg-white rounded-2xl shadow-md p-16 text-center">
            <div class="text-6xl text-yellow-200 mb-4"><i class="fas fa-medal"></i></div>
            <h2 class="text-xl font-bold text-gray-700 mb-2">No certificates yet.</h2>
            <p class="text-gray-400 mb-6">Complete a course to earn your first certificate!</p>
            <a href="{{ route('student.courses') }}" class="bg-yellow-400 text-gray-900 px-8 py-3 rounded-xl font-semibold hover:bg-yellow-300 transition shadow-md">
                View My Courses
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach ($completedCourses as $course)
                <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                    <!-- Certificate Preview -->
                    <div class="relative bg-gradient-to-br from-yellow-400 to-amber-500 px-6 pt-8 pb-6 text-gray-900">
                        <div class="absolute top-3 right-3 text-white/40 text-4xl"><i class="fas fa-certificate"></i></div>
                        <div class="text-xs font-bold uppercase tracking-widest mb-2 text-amber-900">Certificate of Completion</div>
                        <h2 class="text-lg font-extrabold leading-tight mb-1">{{ $course->title }}</h2>
                        <p class="text-xs text-amber-900/80">Awarded to: {{ Auth::user()->name }}</p>
                    </div>
                    <div class="p-5 flex justify-between items-center">
                        <div>
                            <p class="text-sm font-semibold text-gray-700">Course Completed</p>
                            <p class="text-xs text-gray-400">by {{ $course->instructor?->name ?? 'Instructor' }}</p>
                        </div>
                        <button onclick="alert('Certificate download feature coming soon!')" 
                                class="bg-yellow-400 text-gray-900 px-4 py-2 rounded-xl font-semibold hover:bg-yellow-300 transition text-sm">
                            <i class="fas fa-download mr-1"></i>Download
                        </button>
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
