@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900">Instructor Dashboard</h1>
        <p class="text-gray-500 mt-1">Welcome, {{ Auth::user()->name }}! Manage your courses and students.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
        <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-primary-jlm hover:shadow-lg transition">
            <div class="text-3xl font-extrabold text-primary-jlm mb-1">{{ $courses->count() }}</div>
            <div class="text-sm text-gray-500 font-medium"><i class="fas fa-book mr-1"></i>Courses Created</div>
        </div>
        <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-secondary-jlm hover:shadow-lg transition">
            <div class="text-3xl font-extrabold text-secondary-jlm mb-1">{{ $totalStudents }}</div>
            <div class="text-sm text-gray-500 font-medium"><i class="fas fa-users mr-1"></i>Total Students</div>
        </div>
        <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-yellow-400 hover:shadow-lg transition">
            <div class="text-3xl font-extrabold text-yellow-500 mb-1">⭐ 4.8</div>
            <div class="text-sm text-gray-500 font-medium">Avg. Rating</div>
        </div>
        <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-green-500 hover:shadow-lg transition">
            <div class="text-3xl font-extrabold text-green-500 mb-1">
                {{ $courses->sum(fn($c) => $c->lessons->count()) }}
            </div>
            <div class="text-sm text-gray-500 font-medium"><i class="fas fa-list-ul mr-1"></i>Total Lessons</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        <a href="{{ route('instructor.courses.create') }}" class="bg-white p-6 rounded-2xl shadow-md border-t-4 border-primary-jlm flex flex-col items-center text-center hover:shadow-xl transition duration-300 transform hover:-translate-y-1 group">
            <div class="text-primary-jlm mb-4 text-5xl group-hover:scale-110 transition-transform duration-300"><i class="fas fa-plus-circle"></i></div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Create New Course</h3>
            <p class="text-gray-500 text-sm mb-4">Design and publish a new learning experience.</p>
            <span class="mt-auto inline-block bg-primary-jlm text-white px-5 py-2 rounded-xl font-semibold text-sm group-hover:bg-primary-jlm-dark transition">Get Started</span>
        </a>

        <a href="{{ route('instructor.manage.courses') }}" class="bg-white p-6 rounded-2xl shadow-md border-t-4 border-secondary-jlm flex flex-col items-center text-center hover:shadow-xl transition duration-300 transform hover:-translate-y-1 group">
            <div class="text-secondary-jlm mb-4 text-5xl group-hover:scale-110 transition-transform duration-300"><i class="fas fa-folder-open"></i></div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Manage Courses</h3>
            <p class="text-gray-500 text-sm mb-4">Edit, update, and manage your existing courses.</p>
            <span class="mt-auto inline-block bg-secondary-jlm text-white px-5 py-2 rounded-xl font-semibold text-sm group-hover:bg-secondary-jlm/90 transition">View Courses</span>
        </a>

        <a href="{{ url('/profile') }}" class="bg-white p-6 rounded-2xl shadow-md border-t-4 border-blue-400 flex flex-col items-center text-center hover:shadow-xl transition duration-300 transform hover:-translate-y-1 group">
            <div class="text-blue-400 mb-4 text-5xl group-hover:scale-110 transition-transform duration-300"><i class="fas fa-user-cog"></i></div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Profile & Settings</h3>
            <p class="text-gray-500 text-sm mb-4">Update your instructor profile and preferences.</p>
            <span class="mt-auto inline-block bg-blue-400 text-white px-5 py-2 rounded-xl font-semibold text-sm group-hover:bg-blue-500 transition">Edit Profile</span>
        </a>
    </div>

    <!-- My Courses List -->
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <div class="bg-gray-50 border-b border-gray-100 px-6 py-4 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-book-open mr-2 text-primary-jlm"></i>My Courses</h2>
            <a href="{{ route('instructor.courses.create') }}" class="bg-primary-jlm text-white px-4 py-2 rounded-xl font-semibold text-sm hover:bg-primary-jlm-dark transition">
                <i class="fas fa-plus mr-1"></i>New Course
            </a>
        </div>

        @if($courses->isEmpty())
            <div class="p-12 text-center text-gray-400">
                <div class="text-5xl mb-4"><i class="fas fa-book-open"></i></div>
                <p class="text-lg font-semibold mb-2">No courses yet.</p>
                <p class="text-sm mb-4">Create your first course to start teaching.</p>
                <a href="{{ route('instructor.courses.create') }}" class="bg-primary-jlm text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-primary-jlm-dark transition text-sm">
                    Create Course
                </a>
            </div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($courses as $course)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition flex-wrap gap-3">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0">
                            <img src="{{ $course->thumbnail ?? 'https://placehold.co/48x48/1b2299/f7de7a?text=C' }}" 
                                 alt="{{ $course->title }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $course->title }}</p>
                            <p class="text-sm text-gray-400">
                                <span class="mr-3"><i class="fas fa-users mr-1"></i>{{ $course->enrollments->count() }} students</span>
                                <span><i class="fas fa-list-ul mr-1"></i>{{ $course->lessons->count() }} lessons</span>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 ml-auto">
                        <a href="{{ route('instructor.courses.edit', $course) }}" class="bg-secondary-jlm text-white px-4 py-1.5 rounded-lg text-xs font-semibold hover:bg-secondary-jlm/90 transition">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                        <a href="{{ route('instructor.courses.students', $course) }}" class="border border-primary-jlm text-primary-jlm px-4 py-1.5 rounded-lg text-xs font-semibold hover:bg-primary-jlm/5 transition">
                            <i class="fas fa-users mr-1"></i>Students
                        </a>
                        <a href="{{ route('course.detail', $course->slug) }}" class="border border-gray-300 text-gray-600 px-4 py-1.5 rounded-lg text-xs font-semibold hover:bg-gray-100 transition">
                            <i class="fas fa-eye mr-1"></i>View
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
