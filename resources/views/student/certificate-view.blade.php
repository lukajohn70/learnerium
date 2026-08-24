<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Completion — {{ $course->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700;900&family=Plus+Jakarta+Sans:wght@400;600;700&family=Great+Vibes&display=swap" rel="stylesheet">
    <style>
        .font-serif-title { font-family: 'Cinzel', serif; }
        .font-signature { font-family: 'Great Vibes', cursive; }
        @media print {
            body { background: white; margin: 0; padding: 0; }
            .no-print { display: none !important; }
            .cert-container { box-shadow: none !important; border: 12px double #1b2299 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-10 px-4 flex flex-col items-center justify-center font-sans text-slate-800">

    {{-- Action Bar --}}
    <div class="no-print max-w-4xl w-full flex items-center justify-between mb-6">
        <a href="{{ route('student.certificates') }}" class="text-sm font-bold text-slate-600 hover:text-slate-900 transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Back to Certificates
        </a>
        <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-md transition flex items-center gap-2 text-sm">
            <i class="fas fa-print"></i> Print / Save as PDF
        </button>
    </div>

    {{-- Certificate Container (Landscape 4:3 / A4) --}}
    <div class="cert-container relative w-full max-w-4xl bg-white border-[12px] border-double border-[#1b2299] p-10 sm:p-14 shadow-2xl rounded-sm overflow-hidden text-center">
        
        {{-- Elegant Corner Flourishes --}}
        <div class="absolute top-2 left-2 text-[#e4306d] opacity-30 text-3xl"><i class="fas fa-certificate"></i></div>
        <div class="absolute top-2 right-2 text-[#e4306d] opacity-30 text-3xl"><i class="fas fa-certificate"></i></div>
        <div class="absolute bottom-2 left-2 text-[#e4306d] opacity-30 text-3xl"><i class="fas fa-certificate"></i></div>
        <div class="absolute bottom-2 right-2 text-[#e4306d] opacity-30 text-3xl"><i class="fas fa-certificate"></i></div>

        {{-- Top Brand --}}
        <div class="mb-4">
            <h2 class="text-xs uppercase tracking-[0.3em] font-extrabold text-[#e4306d]">LEARNERIUM ACADEMY</h2>
            <div class="w-16 h-0.5 bg-[#e4306d] mx-auto mt-1"></div>
        </div>

        <h1 class="text-3xl sm:text-4xl font-serif-title font-extrabold text-[#1b2299] tracking-wider uppercase mb-2">
            Certificate of Completion
        </h1>
        <p class="text-xs uppercase tracking-widest text-slate-500 font-semibold mb-8">
            This is proudly presented to
        </p>

        {{-- Student Name --}}
        <div class="border-b-2 border-slate-300 max-w-lg mx-auto pb-2 mb-6">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                {{ $user->name }}
            </h2>
        </div>

        <p class="text-sm text-slate-600 max-w-xl mx-auto mb-4 leading-relaxed">
            for successfully completing all curriculum requirements, practical assignments, and assessments for the course:
        </p>

        {{-- Course Title --}}
        <h3 class="text-2xl sm:text-3xl font-extrabold text-[#1b2299] mb-8 max-w-2xl mx-auto leading-tight">
            “{{ $course->title }}”
        </h3>

        {{-- Signatures & Gold Seal --}}
        <div class="grid grid-cols-3 items-end max-w-2xl mx-auto mt-10 pt-4 text-xs">
            {{-- Instructor Signature --}}
            <div class="text-center">
                <div class="font-signature text-3xl text-slate-800 h-10 flex items-center justify-center">
                    {{ $course->instructor->name ?? 'Course Instructor' }}
                </div>
                <div class="border-t border-slate-400 pt-1 font-semibold text-slate-700">
                    {{ $course->instructor->name ?? 'Instructor' }}
                </div>
                <div class="text-[10px] text-slate-400">Course Instructor</div>
            </div>

            {{-- Gold Seal --}}
            <div class="flex flex-col items-center justify-center">
                <div class="w-20 h-20 bg-gradient-to-tr from-amber-500 via-yellow-400 to-amber-300 rounded-full border-4 border-amber-600 shadow-inner flex flex-col items-center justify-center text-amber-950 font-black">
                    <i class="fas fa-medal text-xl"></i>
                    <span class="text-[9px] uppercase tracking-wider font-extrabold">VERIFIED</span>
                </div>
            </div>

            {{-- Date & Serial --}}
            <div class="text-center">
                <div class="font-mono text-sm text-slate-800 h-10 flex items-center justify-center font-bold">
                    {{ $enrollment->updated_at ? $enrollment->updated_at->format('d M Y') : date('d M Y') }}
                </div>
                <div class="border-t border-slate-400 pt-1 font-semibold text-slate-700">
                    Date of Issue
                </div>
                <div class="text-[9px] text-slate-400 font-mono mt-0.5">
                    ID: LNR-{{ strtoupper(substr(md5($enrollment->id . $course->id . $user->id), 0, 8)) }}
                </div>
            </div>
        </div>

    </div>

</body>
</html>
