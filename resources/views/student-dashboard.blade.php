@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-primary-jlm">Your Dashboard</h1>
        <p class="text-gray-500 mt-1">Welcome back, {{ Auth::user()->name }}! Here's your learning overview.</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
        <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-secondary-jlm hover:shadow-lg transition">
            <div class="text-3xl font-extrabold text-secondary-jlm mb-1">{{ $enrolledCourses->count() }}</div>
            <div class="text-sm text-gray-500 font-medium">Enrolled Courses</div>
        </div>
        <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-primary-jlm hover:shadow-lg transition">
            <div class="text-3xl font-extrabold text-primary-jlm mb-1">
                {{ $enrolledCourses->where('pivot.progress_percentage', 100)->count() }}
            </div>
            <div class="text-sm text-gray-500 font-medium">Completed</div>
        </div>
        <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-accent-jlm hover:shadow-lg transition">
            <div class="text-3xl font-extrabold text-yellow-500 mb-1">
                {{ $enrolledCourses->count() > 0 ? round($enrolledCourses->avg('pivot.progress_percentage')) : 0 }}%
            </div>
            <div class="text-sm text-gray-500 font-medium">Avg. Progress</div>
        </div>
        <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-green-500 hover:shadow-lg transition">
            <div class="text-3xl font-extrabold text-green-500 mb-1">0</div>
            <div class="text-sm text-gray-500 font-medium">Certificates</div>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        <!-- Enrolled Courses -->
        <a href="{{ route('student.courses') }}" class="bg-white p-6 rounded-2xl shadow-md border-t-4 border-secondary-jlm flex flex-col items-center text-center hover:shadow-xl transition duration-300 transform hover:-translate-y-1 group">
            <div class="text-secondary-jlm mb-4 text-5xl group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-book-open"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Enrolled Courses</h3>
            <p class="text-gray-500 text-sm mb-4">Continue learning where you left off. Access all your courses here.</p>
            <span class="mt-auto inline-block bg-secondary-jlm text-white px-5 py-2 rounded-xl font-semibold text-sm group-hover:bg-secondary-jlm/90 transition">View My Courses</span>
        </a>

        <!-- My Progress -->
        <a href="{{ route('student.progress') }}" class="bg-white p-6 rounded-2xl shadow-md border-t-4 border-primary-jlm flex flex-col items-center text-center hover:shadow-xl transition duration-300 transform hover:-translate-y-1 group">
            <div class="text-primary-jlm mb-4 text-5xl group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-chart-line"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">My Progress</h3>
            <p class="text-gray-500 text-sm mb-4">Track your course progress, quiz scores, and achievements.</p>
            <span class="mt-auto inline-block bg-primary-jlm text-white px-5 py-2 rounded-xl font-semibold text-sm group-hover:bg-primary-jlm-dark transition">See My Progress</span>
        </a>

        <!-- Certificates -->
        <a href="{{ route('student.certificates') }}" class="bg-white p-6 rounded-2xl shadow-md border-t-4 border-yellow-400 flex flex-col items-center text-center hover:shadow-xl transition duration-300 transform hover:-translate-y-1 group">
            <div class="text-yellow-400 mb-4 text-5xl group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-award"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Certificates</h3>
            <p class="text-gray-500 text-sm mb-4">Download your completed course certificates and achievements.</p>
            <span class="mt-auto inline-block bg-yellow-400 text-gray-900 px-5 py-2 rounded-xl font-semibold text-sm group-hover:bg-yellow-300 transition">Get Certificates</span>
        </a>

        <!-- Explore Courses -->
        <a href="{{ route('courses') }}" class="bg-white p-6 rounded-2xl shadow-md border-t-4 border-gray-400 flex flex-col items-center text-center hover:shadow-xl transition duration-300 transform hover:-translate-y-1 group">
            <div class="text-gray-400 mb-4 text-5xl group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-search"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Explore Courses</h3>
            <p class="text-gray-500 text-sm mb-4">Discover new courses and expand your knowledge.</p>
            <span class="mt-auto inline-block bg-gray-600 text-white px-5 py-2 rounded-xl font-semibold text-sm group-hover:bg-gray-700 transition">Browse All Courses</span>
        </a>

        <!-- Profile -->
        <a href="{{ url('/profile') }}" class="bg-white p-6 rounded-2xl shadow-md border-t-4 border-blue-400 flex flex-col items-center text-center hover:shadow-xl transition duration-300 transform hover:-translate-y-1 group">
            <div class="text-blue-400 mb-4 text-5xl group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-user-cog"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Profile & Settings</h3>
            <p class="text-gray-500 text-sm mb-4">Update your personal information and account settings.</p>
            <span class="mt-auto inline-block bg-blue-400 text-white px-5 py-2 rounded-xl font-semibold text-sm group-hover:bg-blue-500 transition">Manage Profile</span>
        </a>

        <!-- Help -->
        <a href="{{ route('contact') }}" class="bg-white p-6 rounded-2xl shadow-md border-t-4 border-green-500 flex flex-col items-center text-center hover:shadow-xl transition duration-300 transform hover:-translate-y-1 group">
            <div class="text-green-500 mb-4 text-5xl group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-question-circle"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Help & Support</h3>
            <p class="text-gray-500 text-sm mb-4">Need assistance? Contact our support team anytime.</p>
            <span class="mt-auto inline-block bg-green-500 text-white px-5 py-2 rounded-xl font-semibold text-sm group-hover:bg-green-600 transition">Get Help</span>
        </a>
    </div>

    <!-- Recent Enrollments -->
    @if($enrolledCourses->isNotEmpty())
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <div class="bg-gray-50 border-b border-gray-100 px-6 py-4 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-history mr-2 text-primary-jlm"></i>Recent Courses</h2>
            <a href="{{ route('student.courses') }}" class="text-sm text-secondary-jlm hover:underline font-semibold">View All</a>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($enrolledCourses->take(3) as $course)
            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-primary-jlm/10 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-graduation-cap text-primary-jlm text-lg"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">{{ $course->title }}</p>
                        <p class="text-sm text-gray-400">by {{ $course->instructor?->name ?? 'Instructor' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="hidden sm:block w-24">
                        <div class="flex justify-between mb-1">
                            <span class="text-xs text-gray-400">Progress</span>
                            <span class="text-xs font-bold text-primary-jlm">{{ $course->pivot?->progress_percentage ?? 0 }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-primary-jlm h-2 rounded-full transition-all" style="width: {{ $course->pivot?->progress_percentage ?? 0 }}%"></div>
                        </div>
                    </div>
                    <a href="{{ route('course.detail', $course->slug) }}" class="bg-secondary-jlm text-white px-4 py-1.5 rounded-lg text-sm font-semibold hover:bg-secondary-jlm/90 transition">Continue</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
