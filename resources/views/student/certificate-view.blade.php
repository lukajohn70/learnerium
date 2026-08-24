<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Completion — {{ $user->name }} — {{ $course->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700;800;900&family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,400;1,600&family=Great+Vibes&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    
    <style>
        .font-cinzel { font-family: 'Cinzel', Georgia, serif; }
        .font-cormorant { font-family: 'Cormorant Garamond', Garamond, serif; }
        .font-signature { font-family: 'Great Vibes', cursive; }
        .font-mono-code { font-family: 'JetBrains Mono', monospace; }
        
        /* Ornamental guilloche background pattern */
        .cert-bg-pattern {
            background-color: #ffffff;
            background-image: radial-gradient(#1b2299 0.45px, transparent 0.45px), radial-gradient(#e4306d 0.45px, #ffffff 0.45px);
            background-size: 18px 18px;
            background-position: 0 0, 9px 9px;
            background-opacity: 0.03;
        }

        /* Gold Foil Gradient */
        .gold-foil {
            background: linear-gradient(135deg, #bf953f 0%, #fcf6ba 25%, #b38728 50%, #fbf5b7 75%, #aa771c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .gold-foil-bg {
            background: linear-gradient(135deg, #d4af37 0%, #f9e8a2 25%, #c59b27 50%, #fbf5b7 75%, #996515 100%);
        }

        .gold-border {
            border-image: linear-gradient(135deg, #bf953f, #fcf6ba, #b38728, #fbf5b7, #aa771c) 1;
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 0;
            }
            body {
                background: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print {
                display: none !important;
            }
            .cert-wrapper {
                box-shadow: none !important;
                max-width: 100vw !important;
                width: 100vw !important;
                height: 100vh !important;
                border-radius: 0 !important;
                margin: 0 !important;
                padding: 30px !important;
            }
        }
    </style>
</head>
<body class="bg-slate-900 min-h-screen py-8 px-4 flex flex-col items-center justify-center font-sans antialiased selection:bg-amber-400 selection:text-slate-900">

    {{-- Top Action Bar --}}
    <div class="no-print max-w-5xl w-full flex items-center justify-between mb-6 gap-4">
        <a href="{{ route('student.certificates') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-300 hover:text-white bg-slate-800/80 hover:bg-slate-800 border border-slate-700 px-4 py-2.5 rounded-xl transition shadow">
            <i class="fas fa-arrow-left text-amber-400"></i> Back to My Certificates
        </a>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="inline-flex items-center gap-2 bg-gradient-to-r from-amber-500 via-amber-400 to-yellow-500 hover:from-amber-600 hover:to-yellow-600 text-slate-950 font-black py-2.5 px-6 rounded-xl shadow-lg transition transform hover:scale-105 text-xs uppercase tracking-wider">
                <i class="fas fa-print text-sm"></i> Print / Download PDF
            </button>
        </div>
    </div>

    {{-- Certificate Outer Frame (A4 Landscape Proportions) --}}
    <div class="cert-wrapper relative w-full max-w-5xl aspect-[1.414/1] bg-white text-slate-900 shadow-2xl rounded-sm overflow-hidden p-6 sm:p-10 flex flex-col justify-between border-[14px] border-[#1b2299] box-border">

        {{-- Inner Gold Geometric Border --}}
        <div class="relative w-full h-full border-2 border-amber-500/60 p-6 sm:p-8 flex flex-col justify-between cert-bg-pattern">
            
            {{-- Corner Ornaments (Classic Rosettes) --}}
            <div class="absolute -top-3.5 -left-3.5 w-7 h-7 bg-white border-2 border-amber-600 rounded-full flex items-center justify-center text-amber-600 text-xs shadow-sm">
                <i class="fas fa-certificate"></i>
            </div>
            <div class="absolute -top-3.5 -right-3.5 w-7 h-7 bg-white border-2 border-amber-600 rounded-full flex items-center justify-center text-amber-600 text-xs shadow-sm">
                <i class="fas fa-certificate"></i>
            </div>
            <div class="absolute -bottom-3.5 -left-3.5 w-7 h-7 bg-white border-2 border-amber-600 rounded-full flex items-center justify-center text-amber-600 text-xs shadow-sm">
                <i class="fas fa-certificate"></i>
            </div>
            <div class="absolute -bottom-3.5 -right-3.5 w-7 h-7 bg-white border-2 border-amber-600 rounded-full flex items-center justify-center text-amber-600 text-xs shadow-sm">
                <i class="fas fa-certificate"></i>
            </div>

            {{-- Inner Thin Navy Hairline --}}
            <div class="absolute inset-2.5 border border-[#1b2299]/15 pointer-events-none"></div>

            {{-- HEADER SECTION --}}
            <div class="text-center relative z-10 pt-1">
                <div class="inline-flex items-center justify-center gap-2 mb-1.5">
                    <span class="w-10 h-0.5 bg-gradient-to-r from-transparent to-[#e4306d]"></span>
                    <h2 class="text-[11px] sm:text-xs font-extrabold uppercase tracking-[0.4em] text-[#e4306d]">
                        LEARNERIUM ACADEMY
                    </h2>
                    <span class="w-10 h-0.5 bg-gradient-to-l from-transparent to-[#e4306d]"></span>
                </div>
                
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-cinzel font-black text-[#1b2299] tracking-wider uppercase leading-none drop-shadow-xs">
                    Certificate of Completion
                </h1>
                
                <p class="text-[10px] sm:text-xs font-cinzel uppercase tracking-[0.25em] text-slate-500 font-bold mt-2">
                    This is to formally certify that
                </p>
            </div>

            {{-- RECIPIENT NAME SECTION --}}
            <div class="text-center my-auto py-2 relative z-10">
                <div class="inline-block relative">
                    <h2 class="text-3xl sm:text-4xl md:text-5xl font-cormorant font-bold text-slate-900 px-8 pb-1.5 tracking-tight border-b-2 border-amber-500/80">
                        {{ $user->name }}
                    </h2>
                </div>
                
                <p class="text-xs sm:text-sm text-slate-600 max-w-2xl mx-auto mt-3 leading-relaxed font-cormorant text-base sm:text-lg">
                    has successfully fulfilled all curriculum requirements, continuous assessments, task gates, and examinations for the accredited course:
                </p>

                {{-- COURSE TITLE --}}
                <h3 class="text-xl sm:text-2xl md:text-3xl font-cinzel font-extrabold text-[#1b2299] mt-2 tracking-wide max-w-3xl mx-auto leading-snug">
                    “{{ $course->title }}”
                </h3>
            </div>

            {{-- FOOTER / SIGNATURES & OFFICIAL EMBLEM --}}
            <div class="relative z-10 pt-2 border-t border-slate-200/80">
                <div class="grid grid-cols-3 items-end gap-4">
                    
                    {{-- 1. Instructor Signature --}}
                    <div class="text-center">
                        <div class="font-signature text-2xl sm:text-3xl text-slate-800 h-10 flex items-center justify-center leading-none">
                            {{ $course->instructor->name ?? 'Course Instructor' }}
                        </div>
                        <div class="border-t border-slate-400/80 pt-1.5 font-cinzel font-bold text-slate-800 text-[11px] sm:text-xs tracking-wider">
                            {{ $course->instructor->name ?? 'Instructor' }}
                        </div>
                        <div class="text-[9px] sm:text-[10px] text-slate-400 uppercase tracking-wider font-semibold">
                            Lead Instructor
                        </div>
                    </div>

                    {{-- 2. Official Gold Medal Seal --}}
                    <div class="flex flex-col items-center justify-center -mb-2">
                        <div class="relative">
                            {{-- Ribbon Tails --}}
                            <div class="absolute -bottom-3 left-2 w-4 h-8 bg-[#1b2299] transform -rotate-12 rounded-b-sm shadow-md"></div>
                            <div class="absolute -bottom-3 right-2 w-4 h-8 bg-[#e4306d] transform rotate-12 rounded-b-sm shadow-md"></div>

                            {{-- Seal Body --}}
                            <div class="relative w-20 h-20 sm:w-24 sm:h-24 gold-foil-bg rounded-full border-4 border-amber-600/90 shadow-xl flex flex-col items-center justify-center text-amber-950 p-2 text-center">
                                <div class="w-full h-full border border-dashed border-amber-900/40 rounded-full flex flex-col items-center justify-center">
                                    <i class="fas fa-award text-xl sm:text-2xl text-amber-950 mb-0.5"></i>
                                    <span class="text-[8px] sm:text-[9px] font-cinzel font-black uppercase tracking-widest leading-none">OFFICIAL</span>
                                    <span class="text-[7px] font-bold tracking-tight text-amber-900 uppercase">SEAL</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Verification & Date Details --}}
                    <div class="text-center">
                        <div class="font-mono-code text-xs sm:text-sm font-bold text-slate-800 h-10 flex items-center justify-center">
                            {{ $enrollment->updated_at ? $enrollment->updated_at->format('d F Y') : date('d F Y') }}
                        </div>
                        <div class="border-t border-slate-400/80 pt-1.5 font-cinzel font-bold text-slate-800 text-[11px] sm:text-xs tracking-wider">
                            Date of Issue
                        </div>
                        <div class="text-[9px] sm:text-[10px] text-slate-400 font-mono-code font-bold uppercase tracking-wider mt-0.5">
                            Certificate ID: <span class="text-[#1b2299]">LNR-{{ strtoupper(substr(md5($enrollment->id . $course->id . $user->id), 0, 8)) }}</span>
                        </div>
                    </div>

                </div>

                {{-- Bottom Micro Verification Line --}}
                <div class="mt-4 pt-2 border-t border-dashed border-slate-200 flex items-center justify-between text-[8px] text-slate-400 uppercase tracking-widest font-mono-code">
                    <span>Verified Academic Credential</span>
                    <span>learnerium.com.ng &bull; Verification Serial #{{ $enrollment->id }}{{ $course->id }}{{ $user->id }}</span>
                    <span>Accredited E-Learning Platform</span>
                </div>
            </div>

        </div>

    </div>

</body>
</html>
