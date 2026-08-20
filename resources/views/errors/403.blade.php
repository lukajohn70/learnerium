@extends('layouts.app')

@section('title')Access Restricted — Learnerium@endsection

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-16">
    <div class="max-w-lg w-full text-center">

        {{-- Big number --}}
        <div class="relative mb-8 inline-block">
            <span class="text-[9rem] font-extrabold leading-none text-primary-jlm/10 select-none">403</span>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-24 h-24 rounded-full bg-secondary-jlm/10 flex items-center justify-center">
                    <i class="fas fa-lock text-4xl text-secondary-jlm"></i>
                </div>
            </div>
        </div>

        <h1 class="text-3xl font-extrabold text-gray-900 mb-3">Access Restricted</h1>
        <p class="text-gray-500 text-lg mb-2">{{ $exception->getMessage() ?: 'You do not have permission to access this page.' }}</p>

        @guest
            <p class="text-gray-400 text-sm mb-8">Please log in with a student account to enrol in courses.</p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('login.student') }}"
                   class="inline-flex items-center justify-center gap-2 bg-primary-jlm text-white px-7 py-3 rounded-xl font-bold hover:bg-primary-jlm-dark transition shadow-md">
                    <i class="fas fa-user-graduate"></i> Student Sign In
                </a>
                <a href="{{ route('register') }}"
                   class="inline-flex items-center justify-center gap-2 border border-primary-jlm text-primary-jlm px-7 py-3 rounded-xl font-bold hover:bg-primary-jlm/5 transition">
                    <i class="fas fa-user-plus"></i> Register
                </a>
            </div>
        @else
            <p class="text-gray-400 text-sm mb-8">
                @if(auth()->user()->isInstructor())
                    Instructors cannot enrol in courses. Please use a student account.
                @else
                    You don't have permission to perform this action.
                @endif
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('courses') }}"
                   class="inline-flex items-center justify-center gap-2 bg-primary-jlm text-white px-7 py-3 rounded-xl font-bold hover:bg-primary-jlm-dark transition shadow-md">
                    <i class="fas fa-book-open"></i> Browse Courses
                </a>
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center justify-center gap-2 border border-gray-300 text-gray-600 px-7 py-3 rounded-xl font-bold hover:bg-gray-50 transition">
                    <i class="fas fa-home"></i> Go to Dashboard
                </a>
            </div>
        @endguest

    </div>
</div>
@endsection
