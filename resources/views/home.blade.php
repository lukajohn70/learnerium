@extends('layouts.app')
@section('title', 'Learnerium — Learning, Elevated by Creativity')
@section('meta_description', 'Learnerium is Nigeria\'s premier online learning platform. Discover expert-led courses in technology, business, design, and more — powered by JLM\'s innovative, personalised education approach.')
@section('og_title', 'Learnerium — Learning, Elevated by Creativity')
@section('og_description', 'Discover expert-led online courses in tech, business, and design. Earn verified certificates and unlock your potential with Learnerium — powered by JLM.')
@section('og_image', asset('logo-only.png'))



@section('content')

<!-- Hero Section (JLM Layout Replica) -->
<section id="home" class="relative min-h-[85vh] flex items-center justify-center overflow-hidden bg-gradient-to-br from-gray-50 via-white to-gray-50 py-16 lg:py-24">
    <div class="absolute inset-0 bg-gradient-to-r from-[#1b2299]/5 to-[#e4306d]/5 pointer-events-none"></div>
    <div class="absolute top-20 left-10 w-40 h-40 bg-[#f7de7a]/30 rounded-full blur-2xl animate-pulse pointer-events-none"></div>
    <div class="absolute bottom-20 right-10 w-60 h-60 bg-[#e4306d]/20 rounded-full blur-2xl animate-pulse pointer-events-none"></div>

    <div class="container mx-auto px-4 lg:px-8 relative z-10">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="text-center lg:text-left">
                <div class="inline-flex items-center space-x-2 bg-white/80 backdrop-blur-md border border-[#1b2299]/20 rounded-full px-4 py-2 mb-6 shadow-sm">
                    <span class="text-xs font-bold text-gray-700">✨ Creative &bull; Fast &bull; Personalised Education</span>
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-gray-900 mb-6 leading-tight tracking-tight">
                    Learning, <span class="bg-gradient-to-r from-[#1b2299] to-[#e4306d] bg-clip-text text-transparent">Elevated by Creativity.</span>
                </h1>
                <p class="text-lg text-gray-600 mb-8 max-w-2xl leading-relaxed">
                    Unlock your potential with Learnerium — powered by <a href="https://jlm.com.ng" target="_blank" rel="noopener" class="text-primary-jlm font-bold hover:underline">JLM</a>'s innovative, personalised approach to education and skill mastery.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ url('/courses') }}" class="bg-gradient-to-r from-[#1b2299] to-[#e4306d] text-white px-8 py-3.5 rounded-xl text-base font-bold shadow-lg transition-all duration-200 hover:scale-105 hover:shadow-xl flex items-center justify-center gap-2">
                        <i class="fas fa-compass"></i> Explore Courses
                    </a>
                    <a href="{{ route('instructor.apply') }}" class="border-2 border-[#1b2299] text-[#1b2299] px-8 py-3.5 text-base rounded-xl font-bold transition-all duration-200 hover:scale-105 hover:shadow-xl hover:bg-[#1b2299] hover:text-white flex items-center justify-center gap-2">
                        <i class="fas fa-chalkboard-teacher"></i> Teach on Learnerium
                    </a>
                </div>
            </div>
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800&auto=format&fit=crop&q=80" alt="Learnerium online learning platform" class="w-full h-[450px] object-cover rounded-3xl shadow-2xl border border-gray-100" />
                <div class="absolute inset-0 bg-gradient-to-tr from-[#1b2299]/20 to-[#e4306d]/20 rounded-3xl pointer-events-none"></div>
                <div class="absolute top-4 left-4 bg-white/80 backdrop-blur-md border border-white/40 p-4 rounded-2xl shadow-xl flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></div>
                    <div>
                        <p class="text-xs font-bold text-gray-900">Interactive Lessons</p>
                        <p class="text-[10px] text-gray-500">Self-paced learning</p>
                    </div>
                </div>
                <div class="absolute bottom-6 right-6 bg-white/90 backdrop-blur-md border border-white/40 p-4 rounded-2xl shadow-xl flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-jlm text-accent-jlm rounded-xl flex items-center justify-center text-lg font-bold">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <div>
                        <p class="text-xs font-extrabold text-gray-900">Verified Certificates</p>
                        <p class="text-[10px] text-gray-500">Recognized worldwide</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search Bar Section -->
<section class="py-8 bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-4xl mx-auto px-4">
        <form action="{{ route('courses') }}" method="GET" class="relative flex items-center">
            <input type="text" name="search" placeholder="Search for courses, skills, or topics..." 
                   class="w-full pl-12 pr-32 py-3.5 border-2 border-gray-200 rounded-full focus:outline-none focus:border-primary-jlm transition text-base shadow-sm">
            <i class="fas fa-search absolute left-4 text-gray-400 text-lg"></i>
            <button type="submit" class="absolute right-2 bg-gradient-to-r from-[#1b2299] to-[#e4306d] text-white px-6 py-2 rounded-full font-bold text-sm hover:scale-105 transition shadow">
                Search
            </button>
        </form>
    </div>
</section>

<!-- Featured Courses Section -->
<section class="py-16 px-4 bg-gray-50">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-3">
                Featured <span class="bg-gradient-to-r from-[#1b2299] to-[#e4306d] bg-clip-text text-transparent">Courses</span>
            </h2>
            <p class="text-gray-600 text-base max-w-2xl mx-auto">Master high-demand skills with expert-led courses designed for your career growth.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($featuredCourses as $course)
                <div class="bg-white/80 backdrop-blur-md rounded-3xl border border-gray-100 shadow-sm hover:shadow-2xl hover:scale-[1.02] transition-all duration-300 overflow-hidden flex flex-col justify-between group">
                    <div>
                        <div class="relative">
                            <img src="{{ $course->thumbnailUrl() }}" alt="{{ $course->title }}" class="w-full h-48 object-cover group-hover:scale-105 transition duration-300" onerror="this.onerror=null;this.src='https://placehold.co/600x400/1b2299/f7de7a?text={{ urlencode($course->title) }}';">
                            <span class="absolute top-3 left-3 bg-white/90 backdrop-blur text-gray-800 font-bold text-xs px-3 py-1 rounded-full shadow-sm capitalize">
                                {{ $course->level ?? 'Beginner' }}
                            </span>
                        </div>
                        <div class="p-6">
                            @if($course->category)
                                <span class="inline-block bg-[#1b2299]/10 text-[#1b2299] font-extrabold text-[11px] uppercase tracking-wider px-2.5 py-0.5 rounded-full mb-2">
                                    {{ $course->category }}
                                </span>
                            @endif
                            <h3 class="font-extrabold text-xl text-gray-900 mb-2 leading-snug hover:text-primary-jlm transition">
                                <a href="{{ route('course.detail', $course->slug) }}">{{ $course->title }}</a>
                            </h3>
                            <p class="text-gray-500 text-sm mb-4 leading-relaxed line-clamp-2">
                                {{ \Illuminate\Support\Str::limit($course->description, 110) }}
                            </p>
                        </div>
                    </div>

                    <div class="px-6 pb-6 pt-0 flex justify-between items-center border-t border-gray-50 pt-4">
                        <span class="text-2xl font-black bg-gradient-to-r from-[#1b2299] to-[#e4306d] bg-clip-text text-transparent">
                            {{ $course->price > 0 ? '₦' . number_format($course->price, 0) : 'Free' }}
                        </span>
                        <a href="{{ route('course.detail', $course->slug) }}" 
                           class="bg-gradient-to-r from-[#1b2299] to-[#e4306d] text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md hover:scale-105 transition">
                            Details
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-sm">
                    <p class="text-gray-500">No featured courses available at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

@endsection