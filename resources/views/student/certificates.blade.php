@extends('layouts.app')

@section('title', 'My Certificates — Learnerium')
@section('og_title', 'My Certificates — Learnerium Academy')
@section('og_description', 'Download and share your verified Learnerium Academy certificates of achievement.')

@section('content')
<div class="min-h-[80vh] bg-slate-50/60 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">

        {{-- Page Header --}}
        <div class="mb-10 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-[#1b2299] to-[#141a73] flex items-center justify-center shadow-md shadow-blue-900/20">
                        <i class="fas fa-award text-amber-400"></i>
                    </div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">My Certificates</h1>
                </div>
                <p class="text-gray-500 text-sm">Verified academic credentials you've earned. Download, print, or share them anytime.</p>
            </div>
            <a href="{{ route('student.dashboard') }}"
               class="inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-[#1b2299] transition self-start sm:self-auto">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        @php $completedCourses = $courses->where('pivot.progress_percentage', 100); @endphp

        @if($completedCourses->isEmpty())
            {{-- Empty State --}}
            <div class="bg-white rounded-3xl shadow-md border border-gray-100 p-16 text-center max-w-xl mx-auto">
                <div class="relative inline-flex items-center justify-center w-24 h-24 mb-6">
                    <div class="absolute inset-0 rounded-3xl bg-gradient-to-br from-amber-100 to-yellow-50 border border-amber-100"></div>
                    <i class="fas fa-medal text-5xl text-amber-300 relative z-10"></i>
                </div>
                <h2 class="text-xl font-extrabold text-gray-800 mb-2">No certificates yet</h2>
                <p class="text-gray-400 text-sm leading-relaxed mb-8 max-w-sm mx-auto">
                    Complete 100% of a course — including all lessons, tasks, and quizzes — to earn your verified certificate of achievement.
                </p>
                <a href="{{ route('student.courses') }}"
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-[#1b2299] to-[#141a73] text-white font-bold px-8 py-3.5 rounded-2xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition text-sm">
                    <i class="fas fa-graduation-cap text-amber-400"></i>
                    Continue Learning
                </a>
            </div>
        @else
            {{-- Certificates Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach ($completedCourses as $course)
                @php
                    $enrollment = Auth::user()->enrollments()->where('course_id', $course->id)->first();
                    $composite = $enrollment ? ($enrollment->id . $course->id . Auth::id()) : '---';
                    $serial = 'LNR-CERT-' . $composite;
                    $issueDate = $enrollment?->updated_at?->format('d M Y') ?? date('d M Y');
                @endphp
                <div class="group bg-white rounded-3xl shadow-md border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition duration-300">

                    {{-- Certificate Thumbnail Preview --}}
                    <div class="relative h-40 overflow-hidden" style="background: linear-gradient(135deg, #1b2299 0%, #141a73 50%, #0f1454 100%);">

                        {{-- Geometric corner accents --}}
                        <div class="absolute top-0 left-0 w-0 h-0" style="border-style:solid;border-width:56px 0 0 56px;border-color:transparent transparent transparent #e4306d;opacity:.8"></div>
                        <div class="absolute top-0 right-0 w-0 h-0" style="border-style:solid;border-width:56px 56px 0 0;border-color:transparent #d97706 transparent transparent;opacity:.8"></div>
                        <div class="absolute bottom-0 left-0 w-0 h-0" style="border-style:solid;border-width:0 0 40px 40px;border-color:transparent transparent #d97706 transparent;opacity:.6"></div>
                        <div class="absolute bottom-0 right-0 w-0 h-0" style="border-style:solid;border-width:0 40px 40px 0;border-color:transparent transparent #e4306d transparent;opacity:.6"></div>

                        {{-- Logo watermark on preview --}}
                        <img src="{{ asset('logo-only.png') }}" alt="" aria-hidden="true"
                             class="absolute inset-0 w-full h-full object-contain opacity-[0.06] pointer-events-none"
                             style="padding: 16px; filter: grayscale(100%) brightness(200%);">

                        {{-- Preview text --}}
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-4 gap-1.5">
                            <div class="text-[9px] font-black uppercase tracking-[.3em] text-amber-400 mb-0.5">Learnerium Academy</div>
                            <div class="font-bold text-white text-xs uppercase tracking-widest opacity-70">Certificate of</div>
                            <div class="font-black text-white text-lg leading-tight px-6" style="font-family:'Georgia',serif">Achievement</div>
                            <div class="mt-2 h-px w-16 bg-amber-400/60"></div>
                            <div class="text-[10px] text-white/60 font-semibold max-w-[180px] leading-snug line-clamp-2">{{ $course->title }}</div>
                        </div>

                        {{-- Hover overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition duration-300"></div>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-5">
                        <h2 class="font-extrabold text-gray-900 text-sm leading-snug mb-1 line-clamp-2">
                            {{ $course->title }}
                        </h2>
                        <p class="text-xs text-gray-400 mb-3">
                            Instructed by <span class="font-semibold text-gray-600">{{ $course->instructor?->name ?? 'Learnerium Faculty' }}</span>
                        </p>

                        {{-- Meta badges --}}
                        <div class="flex items-center gap-2 flex-wrap mb-4">
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded-md">
                                <i class="fas fa-circle-check text-[9px]"></i> 100% Complete
                            </span>
                            <span class="text-[10px] text-gray-400 font-mono">{{ $issueDate }}</span>
                        </div>

                        {{-- Serial --}}
                        <div class="text-[10px] font-mono text-[#1b2299] bg-blue-50 px-2.5 py-1 rounded-lg inline-block mb-4 font-bold tracking-wide">
                            {{ $serial }}
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-2.5">
                            <a href="{{ route('student.certificate.view', $course) }}"
                               class="flex-1 inline-flex items-center justify-center gap-1.5 bg-gradient-to-r from-[#1b2299] to-[#141a73] text-white text-xs font-bold py-2.5 px-4 rounded-xl hover:shadow-md hover:-translate-y-0.5 transition shadow-sm">
                                <i class="fas fa-eye text-amber-400"></i> View & Print
                            </a>
                            <a href="{{ route('certificate.verify', ['code' => $serial]) }}" target="_blank"
                               title="Verify online"
                               class="inline-flex items-center justify-center w-9 h-9 bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-[#1b2299] rounded-xl transition text-xs">
                                <i class="fas fa-shield-halved"></i>
                            </a>
                            <button
                               onclick="navigator.clipboard.writeText('{{ url('/verify/certificate/' . $serial) }}').then(()=>window.showToast && window.showToast('Verification link copied!','success',2500))"
                               title="Copy verification link"
                               class="inline-flex items-center justify-center w-9 h-9 bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-[#1b2299] rounded-xl transition text-xs">
                                <i class="fas fa-link"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Info Footer --}}
            <div class="mt-10 bg-blue-50/60 border border-blue-100 rounded-2xl p-5 flex items-start gap-4 max-w-2xl">
                <i class="fas fa-circle-info text-[#1b2299] text-lg mt-0.5 flex-shrink-0"></i>
                <div>
                    <p class="text-sm font-bold text-gray-800 mb-0.5">Sharing your certificate</p>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Each certificate contains a unique serial number and a QR code that links to an official public verification page.
                        Employers or universities can visit <strong class="text-[#1b2299]">learnerium.jlm.com.ng/verify/certificate/[serial]</strong> to confirm authenticity.
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
