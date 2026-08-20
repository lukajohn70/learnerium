@extends('layouts.app')

@section('title', 'Apply to Become an Instructor — Learnerium')

@section('content')

<!-- Header Banner -->
<header class="bg-gradient-to-br from-primary-jlm via-indigo-900 to-secondary-jlm text-white py-16 md:py-20 px-4 text-center relative overflow-hidden">
    <div class="max-w-4xl mx-auto relative z-10">
        <span class="inline-block px-4 py-1.5 rounded-full bg-white/10 backdrop-blur border border-white/20 text-accent-jlm font-bold text-xs uppercase tracking-wider mb-6">
            🎓 Teach & Earn on Learnerium
        </span>
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold leading-tight mb-4">
            Become an Instructor
        </h1>
        <p class="text-lg sm:text-xl md:text-2xl opacity-90 max-w-2xl mx-auto font-light">
            Share your expertise, inspire thousands of students, and build a rewarding teaching career.
        </p>
    </div>
</header>

<main class="py-14 px-4 bg-gray-50">
    <div class="max-w-3xl mx-auto">

        <!-- Status Cards if Existing Application -->
        @if($existingApplication)
            @if($existingApplication->isPending())
                <div class="bg-amber-50 border-2 border-amber-300 rounded-3xl p-8 mb-8 text-center shadow-sm">
                    <div class="w-16 h-16 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-2xl mx-auto mb-4">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Application Under Review</h2>
                    <p class="text-gray-600 text-sm max-w-md mx-auto mb-4">
                        Thank you for applying! Our verification team is currently reviewing your profile and credentials.
                    </p>
                    <span class="inline-block bg-amber-200 text-amber-900 font-bold text-xs px-4 py-1.5 rounded-full uppercase tracking-wider">
                        Status: Pending Verification
                    </span>
                </div>
            @elseif($existingApplication->isRejected())
                <div class="bg-red-50 border-2 border-red-200 rounded-3xl p-8 mb-8 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-xl flex-shrink-0">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 mb-1">Application Status Update</h2>
                            <p class="text-gray-600 text-sm mb-3">Your previous application was not approved.</p>
                            @if($existingApplication->rejection_reason)
                                <div class="bg-white p-4 rounded-2xl border border-red-100 text-xs text-red-800 mb-3">
                                    <strong>Feedback:</strong> {{ $existingApplication->rejection_reason }}
                                </div>
                            @endif
                            <p class="text-xs text-gray-500">You may update your details below and re-submit your application for review.</p>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <!-- Application Form Card -->
        <div class="bg-white rounded-3xl shadow-xl p-8 sm:p-12 border border-gray-100">
            <div class="mb-8">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-2">
                    Instructor Verification Form
                </h2>
                <p class="text-gray-500 text-sm">
                    Please provide accurate information about your professional experience and teaching goals.
                </p>
            </div>

            @guest
                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 text-center">
                    <p class="text-blue-900 font-semibold text-sm mb-4">You need an account before submitting an instructor application.</p>
                    <div class="flex justify-center gap-3">
                        <a href="{{ route('login.student') }}" class="bg-primary-jlm text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow">Sign In</a>
                        <a href="{{ route('register') }}" class="border border-primary-jlm text-primary-jlm px-6 py-2.5 rounded-xl text-sm font-bold">Register Account</a>
                    </div>
                </div>
            @else
                <form action="{{ route('instructor.apply.submit') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Professional Headline -->
                    <div>
                        <label for="headline" class="block text-sm font-bold text-gray-700 mb-2">
                            Professional Headline <span class="text-secondary-jlm">*</span>
                        </label>
                        <input id="headline" name="headline" type="text" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-primary-jlm text-sm"
                               placeholder="e.g., Senior Full-Stack Developer & Lead Instructor"
                               value="{{ old('headline', $existingApplication->headline ?? '') }}">
                        @error('headline')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category / Area of Expertise -->
                    <div>
                        <label for="expertise_area" class="block text-sm font-bold text-gray-700 mb-2">
                            Primary Teaching Category <span class="text-secondary-jlm">*</span>
                        </label>
                        <select id="expertise_area" name="expertise_area" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-primary-jlm text-sm bg-white">
                            <option value="">Select Category...</option>
                            @foreach(['Technology & Software Development', 'Business & Entrepreneurship', 'Arts & Graphic Design', 'Media Production & Film', 'Science & Data Analytics', 'Personal Development'] as $cat)
                                <option value="{{ $cat }}" {{ old('expertise_area', $existingApplication->expertise_area ?? '') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        @error('expertise_area')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Biography / Teaching Experience -->
                    <div>
                        <label for="bio" class="block text-sm font-bold text-gray-700 mb-2">
                            Biography & Teaching Experience <span class="text-secondary-jlm">*</span>
                        </label>
                        <textarea id="bio" name="bio" rows="5" required
                                  class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-primary-jlm text-sm"
                                  placeholder="Describe your background, years of industry experience, and why you want to teach on Learnerium...">{{ old('bio', $existingApplication->bio ?? '') }}</textarea>
                        @error('bio')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Portfolio / LinkedIn URL -->
                    <div>
                        <label for="portfolio_url" class="block text-sm font-bold text-gray-700 mb-2">
                            Website or LinkedIn Profile URL <span class="text-gray-400 font-normal">(Optional)</span>
                        </label>
                        <input id="portfolio_url" name="portfolio_url" type="url"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-primary-jlm text-sm"
                               placeholder="https://linkedin.com/in/yourprofile"
                               value="{{ old('portfolio_url', $existingApplication->portfolio_url ?? '') }}">
                        @error('portfolio_url')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sample Video URL -->
                    <div>
                        <label for="sample_video_url" class="block text-sm font-bold text-gray-700 mb-2">
                            Sample Video or Intro Lesson URL <span class="text-gray-400 font-normal">(Optional YouTube/Vimeo Link)</span>
                        </label>
                        <input id="sample_video_url" name="sample_video_url" type="url"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-primary-jlm text-sm"
                               placeholder="https://youtube.com/watch?v=sample"
                               value="{{ old('sample_video_url', $existingApplication->sample_video_url ?? '') }}">
                        @error('sample_video_url')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-secondary-jlm hover:bg-secondary-jlm/90 text-white py-4 rounded-2xl font-bold text-base transition shadow-lg hover:shadow-secondary-jlm/30">
                        <i class="fas fa-paper-plane mr-2"></i>Submit Application for Verification
                    </button>
                </form>
            @endguest
        </div>

    </div>
</main>

@endsection
