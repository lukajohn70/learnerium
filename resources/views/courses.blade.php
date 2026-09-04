@extends('layouts.app')

@section('title', 'Explore Courses — Learnerium')
@section('meta_description', 'Browse all available courses on Learnerium. Find expert-led courses in web development, design, business, and more — with verified certificates upon completion.')
@section('og_title', 'Explore Courses — Learnerium')
@section('og_description', 'Discover and enrol in top-quality online courses across technology, business, and creative skills. Earn verified certificates on Learnerium.')

@section('content')

<!-- Header Banner -->
<header class="bg-gradient-to-br from-primary-jlm via-indigo-900 to-secondary-jlm text-white py-14 md:py-20 px-4 text-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image: url('https://image.pollinations.ai/prompt/abstract%20education%20learning%20pattern'); background-size: cover; background-position: center;"></div>
    <div class="max-w-4xl mx-auto relative z-10">
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold leading-tight mb-4">
            Discover Your Next Skill
        </h1>
        <p class="text-lg sm:text-xl md:text-2xl mb-8 opacity-90 max-w-2xl mx-auto font-light">
            Explore thousands of courses designed to empower your learning journey.
        </p>

        <!-- Search Bar -->
        <div class="relative max-w-2xl mx-auto">
            <form action="{{ route('courses') }}" method="GET" class="relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search for courses..." 
                       class="w-full p-4 pl-12 pr-28 rounded-full border-0 focus:ring-4 focus:ring-accent-jlm/50 shadow-lg text-gray-800 text-base">
                <i class="fas fa-search absolute left-4.5 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-accent-jlm text-primary-jlm px-6 py-2.5 rounded-full font-bold text-sm hover:bg-yellow-300 transition shadow">
                    Search
                </button>
            </form>
        </div>
    </div>
</header>

<!-- Main Course Directory -->
<main class="py-12 px-4 bg-gray-50">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">

        <!-- Mobile Filter Toggle Button -->
        <div class="lg:hidden">
            <button onclick="document.getElementById('filterSidebar').classList.toggle('hidden')" 
                    class="w-full bg-white border border-gray-200 text-gray-800 font-bold px-4 py-3 rounded-2xl shadow-sm flex items-center justify-between">
                <span><i class="fas fa-filter text-primary-jlm mr-2"></i>Filter & Categories</span>
                <i class="fas fa-chevron-down text-xs text-gray-400"></i>
            </button>
        </div>

        <!-- Left Sidebar: Filters -->
        <aside id="filterSidebar" class="hidden lg:block lg:w-1/4 bg-white p-6 rounded-3xl shadow-sm border border-gray-100 h-fit sticky top-24">
            <h2 class="text-xl font-extrabold text-gray-900 mb-6 flex items-center justify-between">
                <span>Filter Courses</span>
                <i class="fas fa-sliders-h text-primary-jlm text-sm"></i>
            </h2>

            <!-- Categories -->
            <div class="mb-6">
                <h3 class="font-bold text-sm uppercase tracking-wider text-primary-jlm mb-3">Categories</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li>
                        <a href="{{ route('courses') }}" class="hover:text-secondary-jlm transition font-medium flex justify-between items-center {{ !request('category') ? 'text-secondary-jlm font-extrabold' : '' }}">
                            <span>All Categories</span>
                        </a>
                    </li>
                    @foreach($categories as $cat)
                        <li>
                            <a href="{{ route('courses', ['category' => $cat]) }}" class="hover:text-secondary-jlm transition font-medium flex justify-between items-center {{ request('category') === $cat ? 'text-secondary-jlm font-extrabold' : '' }}">
                                <span>{{ $cat }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Level -->
            <div class="mb-6">
                <h3 class="font-bold text-sm uppercase tracking-wider text-primary-jlm mb-3">Level</h3>
                <div class="space-y-2 text-sm text-gray-600">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="rounded text-primary-jlm"> <span>Beginner</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="rounded text-primary-jlm"> <span>Intermediate</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="rounded text-primary-jlm"> <span>Advanced</span>
                    </label>
                </div>
            </div>

            <!-- Price -->
            <div>
                <h3 class="font-bold text-sm uppercase tracking-wider text-primary-jlm mb-3">Price</h3>
                <div class="space-y-2 text-sm text-gray-600">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="rounded text-primary-jlm"> <span>Free Courses</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="rounded text-primary-jlm"> <span>Paid Courses</span>
                    </label>
                </div>
            </div>
        </aside>

        <!-- Right Content: Course Cards -->
        <section class="lg:w-3/4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900">All Published Courses</h2>
                    <p class="text-gray-500 text-sm mt-0.5">Showing {{ $courses->count() }} {{ Str::plural('course', $courses->count()) }}</p>
                </div>
                <div class="relative w-full sm:w-auto">
                    <select class="w-full sm:w-auto bg-white border border-gray-200 text-gray-700 text-sm font-semibold py-2.5 px-4 pr-10 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-jlm/20">
                        <option>Sort by: Popularity</option>
                        <option>Sort by: Newest</option>
                        <option>Sort by: Price (Low to High)</option>
                        <option>Sort by: Price (High to Low)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @forelse ($courses as $course)
                    <div class="bg-white rounded-3xl shadow-sm hover:shadow-xl border border-gray-100 overflow-hidden transition-all duration-300 transform hover:-translate-y-1 flex flex-col justify-between">
                        <div>
                            <div class="relative">
                                <img src="{{ $course->thumbnailUrl() }}" alt="{{ $course->title }}" class="w-full h-48 object-cover" onerror="this.onerror=null;this.src='https://placehold.co/600x400/1b2299/f7de7a?text={{ urlencode($course->title) }}';">
                                <span class="absolute top-3 left-3 bg-white/90 backdrop-blur text-gray-800 font-bold text-xs px-3 py-1 rounded-full shadow-sm capitalize">
                                    {{ $course->level ?? 'Beginner' }}
                                </span>
                                @auth
                                    <form action="{{ route('wishlist.toggle', $course) }}" method="POST" class="absolute top-3 right-3">
                                        @csrf
                                        <button type="submit" class="w-9 h-9 rounded-full bg-white/90 backdrop-blur flex items-center justify-center shadow-md transition hover:scale-110 {{ Auth::user()->inWishlist($course->id) ? 'text-pink-600' : 'text-gray-400 hover:text-pink-600' }}" title="Wishlist">
                                            <i class="fas fa-heart text-sm"></i>
                                        </button>
                                    </form>
                                @endauth
                            </div>

                            <div class="p-6">
                                @if($course->category)
                                    <span class="inline-block bg-primary-jlm/10 text-primary-jlm font-extrabold text-[11px] uppercase tracking-wider px-2.5 py-0.5 rounded-full mb-2">
                                        {{ $course->category }}
                                    </span>
                                @endif
                                <h3 class="font-extrabold text-xl text-gray-900 mb-2 leading-snug hover:text-primary-jlm transition">
                                    <a href="{{ route('course.detail', $course->slug) }}">{{ $course->title }}</a>
                                </h3>
                                <p class="text-gray-500 text-sm mb-4 leading-relaxed line-clamp-2">
                                    {{ \Illuminate\Support\Str::limit($course->description, 110) }}
                                </p>
                                <div class="flex items-center gap-2.5 text-xs font-semibold text-gray-600 mb-4">
                                    <img src="{{ $course->instructor ? $course->instructor->avatarUrl() : 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMjgiIGhlaWdodD0iMTI4IiB2aWV3Qm94PSIwIDAgMTI4IDEyOCI+PHJlY3Qgd2lkdGg9IjEyOCIgaGVpZ2h0PSIxMjgiIHJ4PSI2NCIgZmlsbD0iIzFiMjI5OSIvPjx0ZXh0IHg9IjUwJSIgeT0iNTQlIiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmaWxsPSIjZjdkZTdhIiBmb250LWZhbWlseT0ic2Fucy1zZXJpZiIgZm9udC1zaXplPSI0NiIgZm9udC13ZWlnaHQ9ImJvbGQiPklSPC90ZXh0Pjwvc3ZnPg==' }}" class="w-6 h-6 rounded-full object-cover" width="24" height="24" loading="lazy">
                                    <span>{{ $course->instructor?->name ?? 'Instructor' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 pb-6 pt-0 flex justify-between items-center border-t border-gray-50 pt-4">
                            <span class="text-2xl font-extrabold text-primary-jlm">
                                {{ $course->price > 0 ? '₦' . number_format($course->price, 0) : 'Free' }}
                            </span>
                            <div class="flex items-center gap-2">
                                @auth
                                    @if($course->price > 0 && !Auth::user()->enrolledIn($course->id))
                                        <form action="{{ route('cart.store', $course) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="p-2.5 bg-gray-100 hover:bg-primary-jlm hover:text-white text-gray-700 rounded-xl transition" title="Add to Cart">
                                                <i class="fas fa-shopping-cart text-sm"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                                <a href="{{ route('course.detail', $course->slug) }}" 
                                   class="bg-secondary-jlm hover:bg-secondary-jlm/90 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-md hover:shadow-secondary-jlm/30">
                                    Details
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-3xl shadow-sm p-12 text-center border border-gray-100">
                        <i class="fas fa-folder-open text-5xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-800 mb-1">No Published Courses Yet</h3>
                        <p class="text-gray-500 text-sm max-w-md mx-auto">Check back soon! Expert instructors are creating new courses.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</main>

@endsection
