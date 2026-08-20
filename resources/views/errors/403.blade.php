@extends('layouts.app')
@section('title', 'Access Restricted — Learnerium')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-16 relative overflow-hidden bg-gray-50">

    {{-- Decorative background blobs --}}
    <div class="absolute top-0 left-0 w-80 h-80 bg-primary-jlm/5 rounded-full -translate-x-1/2 -translate-y-1/2 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-secondary-jlm/5 rounded-full translate-x-1/3 translate-y-1/3 blur-3xl pointer-events-none"></div>

    <div class="relative max-w-2xl w-full">

        {{-- Card --}}
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">

            {{-- Top accent bar --}}
            <div class="h-1.5 w-full bg-gradient-to-r from-primary-jlm via-secondary-jlm to-accent-jlm"></div>

            <div class="p-10 md:p-14">

                {{-- Icon + error code --}}
                <div class="flex flex-col items-center mb-10">
                    <div class="relative mb-6">
                        {{-- Outer ring --}}
                        <div class="w-32 h-32 rounded-full bg-gradient-to-br from-secondary-jlm/10 to-primary-jlm/10 flex items-center justify-center">
                            {{-- Inner ring --}}
                            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-secondary-jlm/20 to-primary-jlm/20 flex items-center justify-center">
                                <i class="fas fa-lock text-3xl text-secondary-jlm"></i>
                            </div>
                        </div>
                        {{-- Error badge --}}
                        <span class="absolute -top-1 -right-1 bg-secondary-jlm text-white text-xs font-extrabold px-2.5 py-1 rounded-full shadow-md tracking-wide">403</span>
                    </div>

                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 text-center mb-3 leading-tight">
                        Access Restricted
                    </h1>

                    {{-- Dynamic context message --}}
                    @auth
                        @if(auth()->user()->isInstructor())
                            <p class="text-gray-500 text-center text-base leading-relaxed max-w-md">
                                You're logged in as an <span class="font-semibold text-primary-jlm">Instructor</span>.
                                Instructors cannot enrol in courses on their own account.
                                To take a course, please use a <span class="font-semibold text-secondary-jlm">student account</span>.
                            </p>
                        @else
                            <p class="text-gray-500 text-center text-base leading-relaxed max-w-md">
                                You don't have permission to perform this action.
                                If you believe this is a mistake, please contact support.
                            </p>
                        @endif
                    @else
                        <p class="text-gray-500 text-center text-base leading-relaxed max-w-md">
                            You need to be logged in as a <span class="font-semibold text-secondary-jlm">student</span> to enrol in courses.
                            Create an account or sign in to get started.
                        </p>
                    @endauth
                </div>

                {{-- Divider --}}
                <div class="flex items-center gap-4 mb-8">
                    <div class="flex-1 h-px bg-gray-100"></div>
                    <span class="text-xs text-gray-400 font-semibold uppercase tracking-widest">What would you like to do?</span>
                    <div class="flex-1 h-px bg-gray-100"></div>
                </div>

                {{-- Action buttons --}}
                @guest
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <a href="{{ route('login.student') }}"
                           class="group flex flex-col items-center gap-2 bg-primary-jlm hover:bg-primary-jlm-dark text-white p-5 rounded-2xl font-bold transition shadow-lg hover:shadow-primary-jlm/30 hover:-translate-y-0.5 transform duration-200">
                            <i class="fas fa-user-graduate text-2xl mb-1 text-accent-jlm group-hover:scale-110 transition-transform"></i>
                            <span class="text-base">Student Sign In</span>
                            <span class="text-xs text-white/70 font-normal text-center">Already have an account? Log in here</span>
                        </a>
                        <a href="{{ route('register') }}"
                           class="group flex flex-col items-center gap-2 border-2 border-primary-jlm text-primary-jlm hover:bg-primary-jlm/5 p-5 rounded-2xl font-bold transition hover:-translate-y-0.5 transform duration-200">
                            <i class="fas fa-user-plus text-2xl mb-1 text-secondary-jlm group-hover:scale-110 transition-transform"></i>
                            <span class="text-base">Create Account</span>
                            <span class="text-xs text-gray-400 font-normal text-center">New to Learnerium? Join for free</span>
                        </a>
                    </div>
                    <a href="{{ route('login.instructor') }}"
                       class="block text-center text-sm text-gray-400 hover:text-secondary-jlm transition font-medium">
                        <i class="fas fa-chalkboard-teacher mr-1"></i> Instructor? Sign in to your portal
                    </a>

                @elseif(auth()->user()->isInstructor())
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <a href="{{ route('instructor.dashboard') }}"
                           class="group flex flex-col items-center gap-2 bg-primary-jlm hover:bg-primary-jlm-dark text-white p-5 rounded-2xl font-bold transition shadow-lg hover:-translate-y-0.5 transform duration-200">
                            <i class="fas fa-tachometer-alt text-2xl mb-1 text-accent-jlm group-hover:scale-110 transition-transform"></i>
                            <span class="text-base">My Dashboard</span>
                            <span class="text-xs text-white/70 font-normal text-center">Go back to your instructor panel</span>
                        </a>
                        <a href="{{ route('courses') }}"
                           class="group flex flex-col items-center gap-2 border-2 border-primary-jlm text-primary-jlm hover:bg-primary-jlm/5 p-5 rounded-2xl font-bold transition hover:-translate-y-0.5 transform duration-200">
                            <i class="fas fa-book-open text-2xl mb-1 text-secondary-jlm group-hover:scale-110 transition-transform"></i>
                            <span class="text-base">Browse Courses</span>
                            <span class="text-xs text-gray-400 font-normal text-center">Explore the course catalogue</span>
                        </a>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="text-center">
                        @csrf
                        <button type="submit" class="text-sm text-gray-400 hover:text-red-500 transition font-medium">
                            <i class="fas fa-sign-out-alt mr-1"></i> Log out and switch to a student account
                        </button>
                    </form>

                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <a href="{{ route('dashboard') }}"
                           class="group flex flex-col items-center gap-2 bg-primary-jlm hover:bg-primary-jlm-dark text-white p-5 rounded-2xl font-bold transition shadow-lg hover:-translate-y-0.5 transform duration-200">
                            <i class="fas fa-home text-2xl mb-1 text-accent-jlm group-hover:scale-110 transition-transform"></i>
                            <span class="text-base">Go to Dashboard</span>
                            <span class="text-xs text-white/70 font-normal text-center">Return to your student area</span>
                        </a>
                        <a href="{{ route('courses') }}"
                           class="group flex flex-col items-center gap-2 border-2 border-primary-jlm text-primary-jlm hover:bg-primary-jlm/5 p-5 rounded-2xl font-bold transition hover:-translate-y-0.5 transform duration-200">
                            <i class="fas fa-search text-2xl mb-1 text-secondary-jlm group-hover:scale-110 transition-transform"></i>
                            <span class="text-base">Browse Courses</span>
                            <span class="text-xs text-gray-400 font-normal text-center">Find your next course</span>
                        </a>
                    </div>
                @endauth

                {{-- Help text --}}
                <div class="mt-8 bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 flex items-start gap-3">
                    <i class="fas fa-info-circle text-primary-jlm mt-0.5 flex-shrink-0"></i>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold mb-0.5">Need help?</p>
                        <p class="text-xs text-gray-400 leading-relaxed">
                            If you think this is an error, please
                            <a href="{{ route('contact') }}" class="text-primary-jlm font-semibold hover:underline">contact our support team</a>.
                            We're happy to assist you.
                        </p>
                    </div>
                </div>

            </div>
        </div>

        {{-- Footer breadcrumb --}}
        <p class="text-center text-xs text-gray-400 mt-6">
            <a href="{{ url('/') }}" class="hover:text-primary-jlm transition font-semibold">Learnerium Home</a>
            <span class="mx-2">›</span>
            <span>Error 403</span>
        </p>
    </div>
</div>
@endsection
