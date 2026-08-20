@extends('layouts.app')

@section('content')
<div class="py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Profile Header -->
        <div class="bg-gradient-to-br from-primary-jlm to-secondary-jlm rounded-2xl p-8 mb-6 text-white shadow-xl relative overflow-hidden">
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 20% 50%, white 1px, transparent 1px), radial-gradient(circle at 80% 20%, white 1px, transparent 1px); background-size: 30px 30px;"></div>
            <div class="relative z-10 flex flex-col sm:flex-row items-center sm:items-start gap-6">
                <div class="relative">
                    <img src="https://placehold.co/120x120/f7de7a/1b2299?text={{ urlencode(substr(Auth::user()->name, 0, 2)) }}" 
                         alt="{{ Auth::user()->name }}" 
                         class="w-28 h-28 rounded-full border-4 border-white shadow-lg">
                    <span class="absolute bottom-1 right-1 bg-green-400 border-2 border-white w-5 h-5 rounded-full"></span>
                </div>
                <div class="text-center sm:text-left">
                    <h1 class="text-3xl font-extrabold mb-1">{{ Auth::user()->name }}</h1>
                    <p class="text-white/80 text-lg mb-2">{{ Auth::user()->email }}</p>
                    <div class="flex flex-wrap gap-2 justify-center sm:justify-start">
                        <span class="px-3 py-1 bg-white/20 rounded-full text-sm font-medium capitalize">
                            <i class="fas fa-user-tag mr-1"></i>
                            {{ Auth::user()->isInstructor() ? 'Instructor' : 'Student' }}
                        </span>
                        <span class="px-3 py-1 bg-white/20 rounded-full text-sm font-medium">
                            <i class="fas fa-calendar mr-1"></i>
                            Joined {{ Auth::user()->created_at->format('M Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Stats Cards -->
            @if(Auth::user()->isInstructor())
                @php $courses = Auth::user()->coursesTaught; @endphp
                <div class="bg-white rounded-xl shadow-md p-6 text-center border-t-4 border-primary-jlm hover:shadow-lg transition">
                    <div class="text-4xl font-extrabold text-primary-jlm mb-1">{{ $courses->count() }}</div>
                    <div class="text-gray-500 font-medium"><i class="fas fa-book-open mr-1"></i> Courses Created</div>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 text-center border-t-4 border-secondary-jlm hover:shadow-lg transition">
                    <div class="text-4xl font-extrabold text-secondary-jlm mb-1">
                        {{ $courses->sum(fn($c) => $c->enrollments()->count()) }}
                    </div>
                    <div class="text-gray-500 font-medium"><i class="fas fa-users mr-1"></i> Total Students</div>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 text-center border-t-4 border-accent-jlm hover:shadow-lg transition">
                    <div class="text-4xl font-extrabold text-yellow-500 mb-1">⭐ 4.8</div>
                    <div class="text-gray-500 font-medium">Avg. Rating</div>
                </div>
            @else
                @php $enrollments = Auth::user()->coursesEnrolled; @endphp
                <div class="bg-white rounded-xl shadow-md p-6 text-center border-t-4 border-primary-jlm hover:shadow-lg transition">
                    <div class="text-4xl font-extrabold text-primary-jlm mb-1">{{ $enrollments->count() }}</div>
                    <div class="text-gray-500 font-medium"><i class="fas fa-book-open mr-1"></i> Enrolled Courses</div>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 text-center border-t-4 border-secondary-jlm hover:shadow-lg transition">
                    <div class="text-4xl font-extrabold text-secondary-jlm mb-1">
                        {{ $enrollments->where('pivot.progress_percentage', 100)->count() }}
                    </div>
                    <div class="text-gray-500 font-medium"><i class="fas fa-check-circle mr-1"></i> Completed</div>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 text-center border-t-4 border-accent-jlm hover:shadow-lg transition">
                    <div class="text-4xl font-extrabold text-yellow-500 mb-1">
                        {{ $enrollments->count() > 0 ? round($enrollments->avg('pivot.progress_percentage')) : 0 }}%
                    </div>
                    <div class="text-gray-500 font-medium"><i class="fas fa-chart-line mr-1"></i> Avg. Progress</div>
                </div>
            @endif
        </div>

        <!-- Account Info Card -->
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-100 px-6 py-4">
                <h2 class="text-xl font-bold text-gray-800"><i class="fas fa-id-card mr-2 text-primary-jlm"></i>Account Information</h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-gray-400 uppercase tracking-wide block mb-1">Full Name</label>
                        <p class="text-gray-800 font-medium text-lg">{{ Auth::user()->name }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-400 uppercase tracking-wide block mb-1">Email Address</label>
                        <p class="text-gray-800 font-medium text-lg">{{ Auth::user()->email }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-400 uppercase tracking-wide block mb-1">Role</label>
                        <p class="text-gray-800 font-medium text-lg capitalize">{{ Auth::user()->isInstructor() ? 'Instructor' : 'Student' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-400 uppercase tracking-wide block mb-1">Member Since</label>
                        <p class="text-gray-800 font-medium text-lg">{{ Auth::user()->created_at->format('d F, Y') }}</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 flex flex-wrap gap-3">
                    <a href="{{ url('/settings') }}" class="bg-primary-jlm text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-primary-jlm-dark transition text-sm">
                        <i class="fas fa-edit mr-2"></i>Edit Profile
                    </a>
                    <a href="{{ route('dashboard') }}" class="border border-primary-jlm text-primary-jlm px-6 py-2.5 rounded-xl font-semibold hover:bg-primary-jlm/5 transition text-sm">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
