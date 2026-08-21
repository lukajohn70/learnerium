@extends('layouts.app')

@section('title', 'About Us — Learnerium')

@section('content')

<!-- Header Banner -->
<header class="bg-gradient-to-br from-primary-jlm via-indigo-900 to-secondary-jlm text-white py-16 md:py-24 px-4 text-center relative overflow-hidden">
    <div class="max-w-4xl mx-auto relative z-10">
        <span class="inline-block px-4 py-1.5 rounded-full bg-white/10 backdrop-blur border border-white/20 text-accent-jlm font-bold text-xs uppercase tracking-wider mb-6">
            ✨ Creative · Fast · Personalised
        </span>
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold leading-tight mb-4">
            About Learnerium
        </h1>
        <p class="text-lg sm:text-xl md:text-2xl opacity-90 max-w-2xl mx-auto font-light leading-relaxed">
            Elevating education through creative, fast, and personalized learning experiences powered by <a href="https://jlm.com.ng" target="_blank" rel="noopener" class="text-accent-jlm font-extrabold hover:underline">JLM</a>.
        </p>
    </div>
</header>

<main class="py-16 px-4 bg-gray-50">
    <div class="max-w-6xl mx-auto space-y-16">

        <!-- Mission & Vision -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-8 sm:p-10 rounded-3xl shadow-sm border border-gray-100">
                <div class="w-14 h-14 rounded-2xl bg-primary-jlm/10 text-primary-jlm flex items-center justify-center text-2xl mb-6">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h2 class="text-2xl font-extrabold text-gray-900 mb-3">Our Mission</h2>
                <p class="text-gray-600 text-base leading-relaxed">
                    To democratize quality education across Africa and globally by pairing high-caliber video instruction with interactive task gates, peer reviews, and verifiable completion credentials.
                </p>
            </div>

            <div class="bg-white p-8 sm:p-10 rounded-3xl shadow-sm border border-gray-100">
                <div class="w-14 h-14 rounded-2xl bg-secondary-jlm/10 text-secondary-jlm flex items-center justify-center text-2xl mb-6">
                    <i class="fas fa-eye"></i>
                </div>
                <h2 class="text-2xl font-extrabold text-gray-900 mb-3">Our Vision</h2>
                <p class="text-gray-600 text-base leading-relaxed">
                    To build a vibrant learning ecosystem where students do not just passively watch videos, but active construct real-world projects and get peer-validated feedback before earning certificates.
                </p>
            </div>
        </div>

        <!-- Core Pillars -->
        <div class="bg-white p-8 sm:p-12 rounded-3xl shadow-sm border border-gray-100 text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Why Choose Learnerium?</h2>
            <p class="text-gray-500 max-w-2xl mx-auto text-sm mb-12">Designed from the ground up for modern learners and ambitious educators.</p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                <div>
                    <i class="fas fa-layer-group text-3xl text-primary-jlm mb-4"></i>
                    <h3 class="font-extrabold text-lg text-gray-900 mb-2">Task-Gated Learning</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Students submit links, files, or surveys before moving to the next section.</p>
                </div>
                <div>
                    <i class="fas fa-users text-3xl text-secondary-jlm mb-4"></i>
                    <h3 class="font-extrabold text-lg text-gray-900 mb-2">Peer Evaluation</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Optional peer reviews let classmates rate and review real submissions.</p>
                </div>
                <div>
                    <i class="fas fa-award text-3xl text-amber-500 mb-4"></i>
                    <h3 class="font-extrabold text-lg text-gray-900 mb-2">Multi-Currency</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Supports NGN, GHS, KES, ZAR, USD, and more with instant local displays.</p>
                </div>
            </div>
        </div>

    </div>
</main>

@endsection
