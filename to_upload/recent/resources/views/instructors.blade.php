@extends('layouts.app')

@section('title', 'Instructors — Learnerium')

@section('content')

<!-- Header Banner -->
<header class="bg-gradient-to-br from-primary-jlm via-indigo-900 to-secondary-jlm text-white py-14 md:py-20 px-4 text-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image: url('https://image.pollinations.ai/prompt/abstract%20education%20learning%20pattern'); background-size: cover; background-position: center;"></div>
    <div class="max-w-4xl mx-auto relative z-10">
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold leading-tight mb-4">
            Meet Our Expert Instructors
        </h1>
        <p class="text-lg sm:text-xl md:text-2xl mb-8 opacity-90 max-w-2xl mx-auto font-light">
            Learn from industry leaders, creative minds, and passionate educators.
        </p>

        <!-- Search Bar -->
        <div class="relative max-w-2xl mx-auto">
            <input type="text" placeholder="Search for an instructor..." class="w-full p-4 pl-12 pr-28 rounded-full border-0 focus:ring-4 focus:ring-accent-jlm/50 shadow-lg text-gray-800 text-base">
            <i class="fas fa-search absolute left-4.5 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
        </div>
    </div>
</header>

<main class="py-16 px-4 bg-gray-50">
    <div class="max-w-7xl mx-auto">
        
        <div class="text-center mb-12">
            <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Featured Educators</h2>
            <p class="text-gray-500 text-sm">Empowering students through real-world experience and personalized guidance.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            
            @php
                $instructors = \App\Models\User::where('role', 'instructor')->with('coursesTaught')->get();
            @endphp

            @forelse($instructors as $inst)
                <div class="bg-white rounded-3xl p-8 shadow-sm hover:shadow-xl border border-gray-100 transition-all duration-300 text-center flex flex-col justify-between group">
                    <div>
                        <div class="relative w-28 h-28 mx-auto mb-6">
                            <img src="https://placehold.co/120x120/1b2299/f7de7a?text={{ urlencode(substr($inst->name, 0, 2)) }}" 
                                 alt="{{ $inst->name }}" class="w-28 h-28 rounded-full object-cover shadow-md border-4 border-white group-hover:scale-105 transition-transform">
                            <span class="absolute bottom-0 right-0 bg-green-500 text-white text-xs px-2 py-0.5 rounded-full font-bold border-2 border-white">Active</span>
                        </div>

                        <h3 class="text-xl font-extrabold text-gray-900 mb-1">{{ $inst->name }}</h3>
                        <p class="text-xs font-bold uppercase tracking-wider text-secondary-jlm mb-3">Verified Instructor</p>

                        <div class="flex justify-center gap-6 text-sm text-gray-500 my-4 border-y border-gray-100 py-3">
                            <div>
                                <span class="font-extrabold text-gray-900 block text-lg">{{ $inst->coursesTaught->count() }}</span>
                                <span class="text-xs">Courses</span>
                            </div>
                            <div>
                                @php
                                    $studentCount = \App\Models\Enrollment::whereIn('course_id', $inst->coursesTaught->pluck('id'))->count();
                                @endphp
                                <span class="font-extrabold text-gray-900 block text-lg">{{ number_format($studentCount) }}</span>
                                <span class="text-xs">Students</span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('courses') }}" class="w-full bg-primary-jlm hover:bg-primary-jlm-dark text-white py-3 rounded-2xl font-bold text-sm transition shadow">
                        View Courses
                    </a>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-3xl p-12 text-center shadow-sm border border-gray-100">
                    <i class="fas fa-user-graduate text-5xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-800">No Instructors Listed Yet</h3>
                    <p class="text-gray-500 text-sm mt-1">Our platform is growing! Check back soon for updated profiles.</p>
                </div>
            @endforelse

        </div>

    </div>
</main>

@endsection
