@extends('layouts.app')

@section('title', 'Verify Your Email Address — Learnerium')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center px-4 py-16 bg-gray-50 relative overflow-hidden">
    
    <!-- Background Accents -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-primary-jlm/5 rounded-full -translate-y-1/2 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-secondary-jlm/5 rounded-full translate-y-1/3 blur-3xl pointer-events-none"></div>

    <div class="max-w-md w-full relative z-10">

        <!-- Main Card -->
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
            
            <!-- Top Gradient Bar -->
            <div class="h-2 w-full bg-gradient-to-r from-primary-jlm via-secondary-jlm to-accent-jlm"></div>

            <div class="p-8 sm:p-10 text-center">

                <!-- Envelope Icon with Pulse Effect -->
                <div class="relative inline-flex items-center justify-center mb-6">
                    <div class="w-24 h-24 rounded-full bg-primary-jlm/10 flex items-center justify-center animate-pulse"></div>
                    <div class="absolute w-16 h-16 rounded-full bg-primary-jlm text-white flex items-center justify-center shadow-lg text-2xl">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                </div>

                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-2">
                    Verify Your Email
                </h1>

                <p class="text-gray-500 text-sm leading-relaxed mb-6">
                    We've sent a verification link to 
                    <strong class="text-gray-800 font-semibold block mt-1 break-all">{{ auth()->user()->email ?? 'your email address' }}</strong>
                </p>

                <!-- Resent Notification Alert -->
                @if (session('resent'))
                    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center gap-3 text-xs font-semibold shadow-sm animate-fade-in">
                        <i class="fas fa-check-circle text-emerald-500 text-base flex-shrink-0"></i>
                        <span class="text-left">A fresh verification link has been sent to your inbox!</span>
                    </div>
                @endif

                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4 text-xs text-gray-600 mb-6 text-left space-y-2">
                    <div class="flex items-start gap-2.5">
                        <i class="fas fa-info-circle text-primary-jlm mt-0.5 flex-shrink-0"></i>
                        <span>Please click the link inside the email to complete your registration and unlock full course access.</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <i class="fas fa-folder-open text-amber-500 mt-0.5 flex-shrink-0"></i>
                        <span>Can't find it? Be sure to check your <strong>Spam</strong> or <strong>Junk</strong> folder.</span>
                    </div>
                </div>

                <!-- Resend Link Form -->
                <form method="POST" action="{{ route('verification.resend') }}" class="mb-4">
                    @csrf
                    <button type="submit" class="w-full bg-primary-jlm hover:bg-primary-jlm-dark text-white py-3.5 rounded-2xl font-bold text-sm transition shadow-lg hover:shadow-primary-jlm/30 flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane"></i>
                        <span>Resend Verification Email</span>
                    </button>
                </form>

                <!-- Logout Option -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs font-semibold text-gray-400 hover:text-secondary-jlm transition">
                        <i class="fas fa-sign-out-alt mr-1"></i> Log out or use a different email
                    </button>
                </form>

            </div>

            <!-- Footer Badge -->
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-100 text-center text-xs text-gray-400 font-medium">
                Learnerium Security & Verification System
            </div>

        </div>

    </div>
</div>
@endsection
