@extends('layouts.app')

@section('title', 'Contact Us — Learnerium')
@section('meta_description', 'Get in touch with the Learnerium team. Have a question about courses, instructor applications, or partnerships? We\u2019re here to help.')
@section('og_title', 'Contact Learnerium')
@section('og_description', 'Reach out to the Learnerium support team. We\u2019d love to hear from you — whether you\u2019re a student, instructor, or potential partner.')

@section('content')

<!-- Header Banner -->
<header class="bg-gradient-to-br from-primary-jlm via-indigo-900 to-secondary-jlm text-white py-16 md:py-20 px-4 text-center relative overflow-hidden">
    <div class="max-w-4xl mx-auto relative z-10">
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold leading-tight mb-4">
            Get in Touch
        </h1>
        <p class="text-lg sm:text-xl md:text-2xl opacity-90 max-w-2xl mx-auto font-light">
            Have questions or need support? Our team is here to assist you.
        </p>
    </div>
</header>

<main class="py-16 px-4 bg-gray-50">
    <div class="max-w-5xl mx-auto">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Contact Info Sidebar -->
            <div class="bg-gradient-to-br from-primary-jlm to-primary-jlm-dark text-white p-8 rounded-3xl shadow-lg flex flex-col justify-between">
                <div>
                    <h2 class="text-2xl font-extrabold mb-4">Contact Information</h2>
                    <p class="text-white/70 text-sm leading-relaxed mb-8">Fill out the form or reach out directly using the details below.</p>

                    <div class="space-y-6 text-sm">
                        <div class="flex items-start gap-4">
                            <i class="fas fa-envelope text-accent-jlm text-lg mt-0.5"></i>
                            <div>
                                <p class="font-bold text-xs uppercase tracking-wider text-white/50">Email Us</p>
                                <p class="font-semibold text-white">learnerium@jlm.com.ng</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <i class="fas fa-globe-africa text-accent-jlm text-lg mt-0.5"></i>
                            <div>
                                <p class="font-bold text-xs uppercase tracking-wider text-white/50">Website</p>
                                <p class="font-semibold text-white">learnerium.jlm.com.ng</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <i class="fas fa-map-marker-alt text-accent-jlm text-lg mt-0.5"></i>
                            <div>
                                <p class="font-bold text-xs uppercase tracking-wider text-white/50">Location</p>
                                <p class="font-semibold text-white">Nigeria & Global</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-white/10 text-xs text-white/50">
                    Learnerium Support System
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2 bg-white p-8 sm:p-10 rounded-3xl shadow-sm border border-gray-100">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-6">Send us a Message</h2>

                @if(session('status'))
                    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl text-sm font-semibold flex items-center gap-2">
                        <i class="fas fa-check-circle text-emerald-500"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Your Name <span class="text-secondary-jlm">*</span></label>
                            <input id="name" name="name" type="text" value="{{ old('name', auth()->user()?->name) }}" required placeholder="Enter your full name" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-primary-jlm text-sm">
                        </div>
                        <div>
                            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Your Email <span class="text-secondary-jlm">*</span></label>
                            <input id="email" name="email" type="email" value="{{ old('email', auth()->user()?->email) }}" required placeholder="Enter your email address" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-primary-jlm text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="subject" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Subject <span class="text-secondary-jlm">*</span></label>
                        <input id="subject" name="subject" type="text" value="{{ old('subject') }}" required placeholder="How can we help?" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-primary-jlm text-sm">
                    </div>

                    <div>
                        <label for="message" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Message <span class="text-secondary-jlm">*</span></label>
                        <textarea id="message" name="message" rows="5" required placeholder="Write your message here..." class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-primary-jlm text-sm leading-relaxed">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-secondary-jlm text-white py-3.5 rounded-2xl font-bold text-sm hover:bg-secondary-jlm/90 transition shadow-lg hover:shadow-secondary-jlm/30">
                        <i class="fas fa-paper-plane mr-2"></i>Send Message
                    </button>
                </form>
            </div>

        </div>

    </div>
</main>

@endsection
