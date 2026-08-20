@extends('layouts.app')

@section('title', 'Learnerium - Elevating Education with JLM')

@section('content')

<!-- Hero Header Section -->
<header class="bg-gradient-to-br from-primary-jlm via-indigo-900 to-secondary-jlm text-white py-16 md:py-24 px-4 text-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image: url('https://image.pollinations.ai/prompt/minimalistic%20abstract%20pattern%20soft%20gradients%20blue%20pink'); background-size: cover; background-position: center;"></div>
    
    <div class="relative z-10 max-w-5xl mx-auto">
        <span class="inline-block px-4 py-1.5 rounded-full bg-white/10 backdrop-blur border border-white/20 text-accent-jlm font-bold text-xs uppercase tracking-wider mb-6">
            ✨ Creative · Fast · Personalised
        </span>
        <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold leading-tight mb-6 tracking-tight">
            Learning, Elevated by Creativity.
        </h1>
        <p class="text-lg sm:text-xl md:text-2xl mb-10 opacity-90 max-w-3xl mx-auto font-light leading-relaxed">
            Unlock your potential with Learnerium — powered by JLM's innovative, personalised approach to education and skill mastery.
        </p>
        <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
            <a href="{{ url('/courses') }}" class="w-full sm:w-auto bg-accent-jlm text-primary-jlm px-8 py-4 rounded-full text-lg font-bold hover:bg-yellow-300 transition duration-300 shadow-xl transform hover:scale-105">
                <i class="fas fa-compass mr-2"></i>Explore Courses
            </a>
            <a href="{{ route('instructor.apply') }}" class="w-full sm:w-auto border-2 border-white/80 text-white px-8 py-4 rounded-full text-lg font-bold hover:bg-white hover:text-primary-jlm transition duration-300 transform hover:scale-105">
                <i class="fas fa-chalkboard-teacher mr-2"></i>Become an Instructor
            </a>
        </div>
    </div>
</header>

<!-- Search Bar Section -->
<section class="py-8 bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-4xl mx-auto px-4">
        <form action="{{ route('courses') }}" method="GET" class="relative flex items-center">
            <input type="text" name="search" placeholder="Search for courses, skills, or topics..." 
                   class="w-full pl-12 pr-28 py-3.5 border-2 border-gray-200 rounded-full focus:outline-none focus:border-primary-jlm transition text-base shadow-sm">
            <i class="fas fa-search absolute left-4 text-gray-400 text-lg"></i>
            <button type="submit" class="absolute right-2 bg-primary-jlm text-white px-6 py-2 rounded-full font-semibold text-sm hover:bg-primary-jlm-dark transition shadow">
                Search
            </button>
        </form>
    </div>
</section>

<!-- Popular Categories Section -->
<section class="py-16 px-4 bg-gray-50">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-extrabold text-primary-jlm mb-3">Popular Categories</h2>
            <p class="text-gray-500 text-base max-w-xl mx-auto">Discover top-rated subjects curated by leading industry experts.</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6">
            <a href="{{ url('/courses') }}" class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition text-center border border-gray-100 group">
                <div class="w-16 h-16 rounded-2xl bg-yellow-100 text-yellow-700 mx-auto flex items-center justify-center text-xl font-bold mb-4 group-hover:scale-110 transition-transform">
                    Tech
                </div>
                <h3 class="font-bold text-gray-800 text-base">Technology</h3>
            </a>

            <a href="{{ url('/courses') }}" class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition text-center border border-gray-100 group">
                <div class="w-16 h-16 rounded-2xl bg-pink-100 text-pink-600 mx-auto flex items-center justify-center text-xl font-bold mb-4 group-hover:scale-110 transition-transform">
                    Biz
                </div>
                <h3 class="font-bold text-gray-800 text-base">Business</h3>
            </a>

            <a href="{{ url('/courses') }}" class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition text-center border border-gray-100 group">
                <div class="w-16 h-16 rounded-2xl bg-blue-100 text-blue-700 mx-auto flex items-center justify-center text-xl font-bold mb-4 group-hover:scale-110 transition-transform">
                    Arts
                </div>
                <h3 class="font-bold text-gray-800 text-base">Arts & Design</h3>
            </a>

            <a href="{{ url('/courses') }}" class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition text-center border border-gray-100 group">
                <div class="w-16 h-16 rounded-2xl bg-amber-100 text-amber-700 mx-auto flex items-center justify-center text-xl font-bold mb-4 group-hover:scale-110 transition-transform">
                    Media
                </div>
                <h3 class="font-bold text-gray-800 text-base">Media Production</h3>
            </a>

            <a href="{{ url('/courses') }}" class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition text-center border border-gray-100 group col-span-2 sm:col-span-1">
                <div class="w-16 h-16 rounded-2xl bg-rose-100 text-rose-600 mx-auto flex items-center justify-center text-xl font-bold mb-4 group-hover:scale-110 transition-transform">
                    Sci
                </div>
                <h3 class="font-bold text-gray-800 text-base">Science & Data</h3>
            </a>
        </div>
    </div>
</section>

<!-- Core Values Section -->
<section class="py-16 px-4 bg-white">
    <div class="max-w-7xl mx-auto text-center">
        <h2 class="text-3xl md:text-4xl font-extrabold text-primary-jlm mb-4">
            Our Core Values <span class="text-secondary-jlm">(Powered by JLM)</span>
        </h2>
        <p class="text-gray-500 text-base max-w-2xl mx-auto mb-12">Building a future where learning is accessible, engaging, and directly impactful.</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-8 rounded-3xl bg-gray-50 border border-gray-100 text-center">
                <div class="w-16 h-16 rounded-2xl bg-primary-jlm/10 text-primary-jlm mx-auto flex items-center justify-center text-2xl mb-5">
                    <i class="fas fa-palette"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Creative</h3>
                <p class="text-gray-600 text-sm leading-relaxed">Inspiring practical innovation through hands-on projects and interactive task gates.</p>
            </div>

            <div class="p-8 rounded-3xl bg-gray-50 border border-gray-100 text-center">
                <div class="w-16 h-16 rounded-2xl bg-secondary-jlm/10 text-secondary-jlm mx-auto flex items-center justify-center text-2xl mb-5">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Fast</h3>
                <p class="text-gray-600 text-sm leading-relaxed">Streamlined, responsive learning environment optimized for rapid skill acquisition.</p>
            </div>

            <div class="p-8 rounded-3xl bg-gray-50 border border-gray-100 text-center">
                <div class="w-16 h-16 rounded-2xl bg-amber-500/10 text-amber-600 mx-auto flex items-center justify-center text-2xl mb-5">
                    <i class="fas fa-user-check"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Personalised</h3>
                <p class="text-gray-600 text-sm leading-relaxed">Tailored progress tracking, peer review feedback, and flexible course paths.</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="py-16 px-4 bg-gradient-to-r from-primary-jlm to-primary-jlm-dark text-white text-center">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl sm:text-4xl font-extrabold mb-4">Ready to Start Learning?</h2>
        <p class="text-lg text-white/80 mb-8 max-w-2xl mx-auto">Join thousands of students building real-world skills today.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('register') }}" class="bg-secondary-jlm text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-secondary-jlm/90 transition shadow-lg transform hover:scale-105">
                Get Started Free
            </a>
            <a href="{{ route('courses') }}" class="border border-white/40 text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-white/10 transition">
                Browse All Courses
            </a>
        </div>
    </div>
</section>

@endsection