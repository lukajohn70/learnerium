@extends('layouts.app')

@section('title', 'Shopping Cart — Learnerium')

@section('content')
<div class="min-h-screen bg-gray-50 py-10 px-4">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-jlm text-accent-jlm rounded-xl flex items-center justify-center shadow">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    Shopping Cart
                </h1>
                <p class="text-gray-500 text-sm mt-1">Review your selected courses before proceeding to payment.</p>
            </div>
            <a href="{{ route('courses') }}" class="inline-flex items-center gap-2 bg-accent-jlm hover:bg-yellow-400 text-primary-jlm px-4 py-2 rounded-full text-xs font-extrabold shadow transition">
                <i class="fas fa-arrow-left text-[10px]"></i> Keep Exploring
            </a>
        </div>

        @if(session('status'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl text-sm font-medium flex items-center gap-2">
                <i class="fas fa-check-circle text-emerald-500"></i>{{ session('status') }}
            </div>
        @endif
        @if(session('info'))
            <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-2xl text-sm font-medium flex items-center gap-2">
                <i class="fas fa-info-circle text-blue-500"></i>{{ session('info') }}
            </div>
        @endif

        @if($cartCourses->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items List -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden divide-y divide-gray-100">
                        @foreach($cartCourses as $course)
                            <div class="p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 hover:bg-gray-50/50 transition">
                                <div class="flex items-center gap-4 min-w-0">
                                    <img src="{{ $course->thumbnailUrl() }}" alt="{{ $course->title }}" class="w-20 h-14 rounded-xl object-cover border border-gray-200 flex-shrink-0">
                                    <div class="min-w-0">
                                        <a href="{{ route('course.detail', $course->slug) }}" class="font-extrabold text-gray-800 text-base hover:text-primary-jlm transition truncate block">
                                            {{ $course->title }}
                                        </a>
                                        <p class="text-xs text-gray-500 mt-0.5">By {{ $course->instructor->name ?? 'Instructor' }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-[10px] bg-primary-jlm/10 text-primary-jlm px-2 py-0.5 rounded-full font-bold">
                                                {{ ucfirst($course->category ?? 'General') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex sm:flex-col items-center sm:items-end justify-between w-full sm:w-auto border-t sm:border-t-0 pt-3 sm:pt-0 border-gray-100 gap-2 flex-shrink-0">
                                    <div class="text-lg font-extrabold text-primary-jlm">
                                        ₦{{ number_format($course->price, 2) }}
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('cart.move-to-wishlist', $course) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs text-pink-600 hover:text-pink-700 font-bold inline-flex items-center gap-1 hover:underline">
                                                <i class="fas fa-heart text-[10px]"></i> Wishlist
                                            </button>
                                        </form>
                                        <span class="text-gray-300">|</span>
                                        <form action="{{ route('cart.destroy', $course) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-bold inline-flex items-center gap-1 hover:underline">
                                                <i class="fas fa-trash-alt text-[10px]"></i> Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-gradient-to-br from-primary-jlm via-primary-jlm-dark to-secondary-jlm text-white rounded-3xl p-6 shadow-xl sticky top-8">
                        <h2 class="text-xl font-extrabold mb-4 pb-3 border-b border-white/10 flex items-center gap-2">
                            <i class="fas fa-receipt text-accent-jlm"></i> Order Summary
                        </h2>
                        <div class="space-y-3 text-sm text-white/80">
                            <div class="flex justify-between">
                                <span>Items ({{ $cartCourses->count() }})</span>
                                <span class="font-bold text-white">₦{{ number_format($totalPrice, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-xs text-emerald-300">
                                <span>Instant Access</span>
                                <span class="font-bold">Included</span>
                            </div>
                        </div>
                        <div class="mt-6 pt-4 border-t border-white/10 flex justify-between items-center">
                            <span class="text-base font-extrabold text-white">Total:</span>
                            <span class="text-2xl font-black text-accent-jlm">₦{{ number_format($totalPrice, 2) }}</span>
                        </div>

                        <!-- Direct Checkout button for first course or redirect -->
                        @if($cartCourses->count() === 1)
                            <a href="{{ route('courses.checkout', $cartCourses->first()) }}" class="mt-6 w-full bg-accent-jlm hover:bg-yellow-300 text-primary-jlm py-3.5 px-4 rounded-full font-black text-center text-sm shadow-lg hover:shadow-xl transition transform hover:scale-[1.02] block">
                                Proceed to Checkout <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        @else
                            <a href="{{ route('courses.checkout', $cartCourses->first()) }}" class="mt-6 w-full bg-accent-jlm hover:bg-yellow-300 text-primary-jlm py-3.5 px-4 rounded-full font-black text-center text-sm shadow-lg hover:shadow-xl transition transform hover:scale-[1.02] block">
                                Checkout First Course <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        @endif

                        <div class="mt-4 text-center">
                            <span class="text-[10px] text-white/60 flex items-center justify-center gap-1">
                                <i class="fas fa-shield-alt text-emerald-400"></i> Encrypted 256-Bit SSL Paystack Payment
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-sm max-w-xl mx-auto my-12">
                <div class="w-20 h-20 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3 class="text-2xl font-extrabold text-gray-800 mb-2">Your Cart is Empty</h3>
                <p class="text-gray-500 text-sm mb-6">Looks like you haven't added any courses to your shopping cart yet.</p>
                <a href="{{ route('courses') }}" class="inline-flex items-center gap-2 bg-accent-jlm hover:bg-yellow-400 text-primary-jlm px-8 py-3.5 rounded-full font-extrabold text-sm shadow-lg transition transform hover:scale-105">
                    <i class="fas fa-compass"></i> Explore Courses
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
