@extends('layouts.app')

@section('title', 'My Wishlist — Learnerium')

@section('content')
<div class="min-h-screen bg-gray-50 py-10 px-4">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 flex items-center gap-3">
                    <div class="w-10 h-10 bg-pink-600 text-white rounded-xl flex items-center justify-center shadow">
                        <i class="fas fa-heart"></i>
                    </div>
                    My Wishlist
                </h1>
                <p class="text-gray-500 text-sm mt-1">Saved courses you're interested in taking later.</p>
            </div>
            <a href="{{ route('courses') }}" class="inline-flex items-center gap-2 bg-accent-jlm hover:bg-yellow-400 text-primary-jlm px-4 py-2 rounded-full text-xs font-extrabold shadow transition">
                <i class="fas fa-search text-[10px]"></i> Discover More
            </a>
        </div>

        @if(session('status'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl text-sm font-medium flex items-center gap-2">
                <i class="fas fa-check-circle text-emerald-500"></i>{{ session('status') }}
            </div>
        @endif

        @if($wishlistCourses->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($wishlistCourses as $course)
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col group hover:shadow-md transition">
                        <div class="relative">
                            <img src="{{ $course->thumbnailUrl() }}" alt="{{ $course->title }}" class="w-full h-44 object-cover group-hover:scale-105 transition duration-300">
                            <form action="{{ route('wishlist.destroy', $course) }}" method="POST" class="absolute top-3 right-3">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-full bg-white/90 hover:bg-red-500 hover:text-white text-gray-600 flex items-center justify-center shadow transition" title="Remove from wishlist">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </form>
                        </div>
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-2">
                                    <span class="text-[10px] bg-primary-jlm/10 text-primary-jlm px-2.5 py-0.5 rounded-full font-extrabold">
                                        {{ ucfirst($course->category ?? 'General') }}
                                    </span>
                                    <span class="text-base font-black text-primary-jlm">
                                        ₦{{ number_format($course->price, 2) }}
                                    </span>
                                </div>
                                <a href="{{ route('course.detail', $course->slug) }}" class="font-extrabold text-gray-800 text-base hover:text-primary-jlm transition line-clamp-2 block mb-2">
                                    {{ $course->title }}
                                </a>
                                <p class="text-xs text-gray-500">By {{ $course->instructor->name ?? 'Instructor' }}</p>
                            </div>
                            <div class="mt-5 pt-4 border-t border-gray-100 flex items-center gap-2">
                                <form action="{{ route('wishlist.move-to-cart', $course) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full bg-primary-jlm hover:bg-primary-jlm-dark text-white py-2.5 px-3 rounded-full text-xs font-bold transition flex items-center justify-center gap-1.5 shadow">
                                        <i class="fas fa-shopping-cart text-[10px]"></i> Move to Cart
                                    </button>
                                </form>
                                <a href="{{ route('course.detail', $course->slug) }}" class="p-2.5 text-gray-500 hover:text-primary-jlm rounded-full hover:bg-gray-100 transition" title="View Course">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-sm max-w-xl mx-auto my-12">
                <div class="w-20 h-20 bg-pink-50 text-pink-500 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                    <i class="fas fa-heart"></i>
                </div>
                <h3 class="text-2xl font-extrabold text-gray-800 mb-2">Your Wishlist is Empty</h3>
                <p class="text-gray-500 text-sm mb-6">Explore our catalog and click the heart icon on courses you want to save for later.</p>
                <a href="{{ route('courses') }}" class="inline-flex items-center gap-2 bg-accent-jlm hover:bg-yellow-400 text-primary-jlm px-8 py-3.5 rounded-full font-extrabold text-sm shadow-lg transition transform hover:scale-105">
                    <i class="fas fa-compass"></i> Explore Courses
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
