@extends('layouts.app')

@section('title', 'Certificate Verification — Learnerium Academy')
@section('og_title', 'Certificate Verification — Learnerium Academy')
@section('og_description', 'Verify the authenticity and accreditation of credentials issued by Learnerium Academy.')

@section('content')
<div class="min-h-[80vh] bg-slate-50/70 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">

        {{-- Top Header / Hero --}}
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-[#1b2299] to-[#2a35d4] text-white shadow-xl shadow-blue-900/10 mb-4 ring-4 ring-blue-50">
                <i class="fas fa-certificate text-2xl text-amber-400"></i>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
                Official Credential Verification
            </h1>
            <p class="mt-2.5 text-sm sm:text-base text-gray-600 max-w-lg mx-auto">
                Authenticate certificates issued by Learnerium Academy. Confirm student achievement, course completion, and institutional accreditation.
            </p>
        </div>

        {{-- Verification Search Bar --}}
        <div class="bg-white rounded-3xl p-4 sm:p-5 shadow-lg border border-gray-100 mb-8">
            <form action="{{ route('certificate.verify.search') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                @csrf
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-barcode"></i>
                    </div>
                    <input 
                        type="text" 
                        name="code" 
                        id="serialInput"
                        value="{{ old('code', $code) }}" 
                        placeholder="Enter Serial (e.g. LNR-CERT-2413 or 2413)"
                        class="w-full pl-11 pr-4 py-3.5 bg-gray-50/80 border border-gray-200 rounded-2xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1b2299] focus:bg-white text-sm font-semibold uppercase tracking-wider transition"
                        required
                    >
                </div>
                <button 
                    type="submit" 
                    class="bg-gradient-to-r from-[#1b2299] to-[#141a73] hover:from-[#141a73] hover:to-[#0f1454] text-white font-bold px-7 py-3.5 rounded-2xl text-sm shadow-md hover:shadow-lg transition duration-200 flex items-center justify-center gap-2 group flex-shrink-0"
                >
                    <i class="fas fa-search text-amber-400 group-hover:scale-110 transition-transform"></i>
                    <span>Verify Credential</span>
                </button>
            </form>
        </div>

        {{-- RESULTS DISPLAY --}}
        @if($code && $isValid && $enrollment)
            {{-- Verified Certificate Card --}}
            <div class="bg-white rounded-3xl shadow-xl border border-emerald-100 overflow-hidden relative">
                {{-- Status Banner --}}
                <div class="bg-gradient-to-r from-emerald-600 via-emerald-500 to-teal-600 px-6 py-3.5 text-white flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2.5">
                        <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-xs">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-black uppercase tracking-wider">
                            Officially Verified & Authentic Credential
                        </span>
                    </div>
                    <span class="text-[11px] font-bold bg-white/20 px-2.5 py-0.5 rounded-full backdrop-blur-xs">
                        ACCREDITED
                    </span>
                </div>

                <div class="p-6 sm:p-8">
                    {{-- Student & Course Details --}}
                    <div class="border-b border-gray-100 pb-6 mb-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <span class="text-[11px] font-extrabold uppercase tracking-widest text-gray-400">
                                    Recipient Name
                                </span>
                                <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mt-0.5">
                                    {{ $student->name ?? 'Student' }}
                                </h2>
                            </div>
                            <div class="sm:text-right">
                                <span class="text-[11px] font-extrabold uppercase tracking-widest text-gray-400">
                                    Serial Number
                                </span>
                                <p class="text-sm font-mono font-bold text-[#1b2299] bg-blue-50 px-3 py-1 rounded-lg inline-block mt-0.5">
                                    {{ $serialCode }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Course Information Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">
                        <div class="bg-slate-50/80 p-4 rounded-2xl border border-gray-100">
                            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block mb-1">
                                Course Completed
                            </span>
                            <p class="font-extrabold text-gray-900 text-base leading-snug">
                                {{ $course->title ?? 'Course' }}
                            </p>
                            @if(!empty($course->category))
                                <span class="inline-block mt-2 text-[10px] font-bold bg-[#1b2299]/10 text-[#1b2299] px-2 py-0.5 rounded-md">
                                    {{ $course->category }}
                                </span>
                            @endif
                        </div>

                        <div class="bg-slate-50/80 p-4 rounded-2xl border border-gray-100">
                            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block mb-1">
                                Instructed By
                            </span>
                            <p class="font-extrabold text-gray-900 text-base leading-snug">
                                {{ $course->instructor?->name ?? 'Learnerium Instructor' }}
                            </p>
                            <span class="text-xs text-gray-500 mt-1 block">
                                Lead Academic Faculty
                            </span>
                        </div>

                        <div class="bg-slate-50/80 p-4 rounded-2xl border border-gray-100">
                            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block mb-1">
                                Date of Issue
                            </span>
                            <p class="font-bold text-gray-900 text-sm">
                                <i class="far fa-calendar-alt text-[#1b2299] mr-1.5"></i>
                                {{ $enrollment->updated_at ? $enrollment->updated_at->format('F d, Y') : date('F d, Y') }}
                            </p>
                        </div>

                        <div class="bg-slate-50/80 p-4 rounded-2xl border border-gray-100">
                            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block mb-1">
                                Status & Requirements
                            </span>
                            <p class="font-bold text-emerald-600 text-sm flex items-center gap-1.5">
                                <i class="fas fa-circle-check"></i>
                                100% Curriculum Completed & Graded
                            </p>
                        </div>
                    </div>

                    {{-- Verification Statement --}}
                    <div class="bg-blue-50/50 rounded-2xl p-4 border border-blue-100/60 flex items-start gap-3 mb-6">
                        <i class="fas fa-shield-halved text-[#1b2299] text-xl mt-0.5 flex-shrink-0"></i>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            This digital credential confirms that the individual named above has successfully fulfilled all educational criteria, continuous assessments, tasks, and examinations in accordance with the standards established by Learnerium Academy.
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
                        <button 
                            onclick="copyVerificationLink()" 
                            id="copyBtn"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-xs font-bold text-gray-700 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 px-5 py-2.5 rounded-xl transition shadow-xs"
                        >
                            <i class="fas fa-link text-[#1b2299]"></i>
                            <span>Copy Verification Link</span>
                        </button>

                        @if(Auth::check() && $course)
                            <a 
                                href="{{ route('student.certificate.view', $course) }}" 
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-[#1b2299] hover:bg-[#141a73] text-white text-xs font-black uppercase tracking-wider px-6 py-2.5 rounded-xl transition shadow-md hover:shadow-lg"
                            >
                                <i class="fas fa-award text-amber-400"></i>
                                <span>Open Full Certificate</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

        @elseif($code && !$isValid)
            {{-- Not Found Card --}}
            <div class="bg-white rounded-3xl shadow-lg border border-amber-200 p-8 text-center">
                <div class="w-16 h-16 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-4 text-2xl border border-amber-100">
                    <i class="fas fa-triangle-exclamation"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-2">
                    Credential Record Not Found
                </h2>
                <p class="text-sm text-gray-600 max-w-md mx-auto leading-relaxed mb-6">
                    We could not locate an active, 100% completed certificate matching the serial: <strong class="text-gray-900 font-mono">{{ $code }}</strong>.
                </p>
                <div class="bg-slate-50 rounded-2xl p-4 text-xs text-gray-500 max-w-md mx-auto text-left leading-relaxed space-y-2 border border-gray-100">
                    <p class="font-bold text-gray-700">Possible reasons:</p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>The serial number was entered with a typo. Please check the code at the bottom of the certificate.</li>
                        <li>The course has not reached 100% completion in the learner's dashboard.</li>
                        <li>The certificate was issued under an alternative format.</li>
                    </ul>
                </div>
            </div>

        @else
            {{-- Blank / Welcome Guide --}}
            <div class="bg-white rounded-3xl shadow-md border border-gray-100 p-8 text-center">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 text-[#1b2299] flex items-center justify-center mx-auto mb-4 text-2xl border border-blue-100">
                    <i class="fas fa-magnifying-glass"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">
                    How to verify a certificate
                </h3>
                <p class="text-sm text-gray-500 max-w-md mx-auto leading-relaxed">
                    Locate the <strong>Verification Serial</strong> or scan the QR Code found at the bottom of any official Learnerium Certificate, then enter it into the search box above.
                </p>
            </div>
        @endif

        {{-- Trust & Accreditation Footer --}}
        <div class="mt-12 text-center text-xs text-gray-400 flex items-center justify-center gap-6">
            <span class="flex items-center gap-1.5">
                <i class="fas fa-lock text-emerald-500"></i> Tamper-Proof Cryptographic Verification
            </span>
            <span class="hidden sm:inline">&bull;</span>
            <span class="flex items-center gap-1.5">
                <i class="fas fa-graduation-cap text-[#1b2299]"></i> Learnerium Academy
            </span>
        </div>

    </div>
</div>

<script>
    function copyVerificationLink() {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            const btn = document.getElementById('copyBtn');
            if (btn) {
                const original = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check text-emerald-500"></i><span class="text-emerald-600 font-bold">Link Copied!</span>';
                setTimeout(() => btn.innerHTML = original, 2500);
            }
        });
    }
</script>
@endsection
