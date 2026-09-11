<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Achievement — {{ $user->name }} — {{ $course->title }}</title>
    <meta name="description" content="Official certificate of achievement from Learnerium Academy awarded to {{ $user->name }} for completing {{ $course->title }}.">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

    {{-- Fonts matching reference --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Great+Vibes&family=Inter:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #0b1329;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 24px 16px 48px;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Action Toolbar ─────────────────────────── */
        .action-bar {
            width: 100%;
            max-width: 1040px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .btn-back {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 13px; font-weight: 600; color: #cbd5e1;
            background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12);
            padding: 10px 18px; border-radius: 12px; text-decoration: none;
            transition: all .2s;
        }
        .btn-back:hover { background: rgba(255,255,255,.14); color: #fff; }
        .btn-verify {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 12px; font-weight: 700; color: #10b981;
            background: rgba(16,185,129,.1); border: 1px solid rgba(16,185,129,.25);
            padding: 10px 18px; border-radius: 12px; text-decoration: none;
            transition: all .2s;
        }
        .btn-verify:hover { background: rgba(16,185,129,.2); }
        .btn-print {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: .06em;
            color: #1e1e1e; padding: 10px 22px; border-radius: 12px; border: none; cursor: pointer;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            box-shadow: 0 4px 18px rgba(245,158,11,.35);
            transition: all .2s;
        }
        .btn-print:hover { transform: translateY(-1px); box-shadow: 0 6px 24px rgba(245,158,11,.45); }

        /* ── Certificate Canvas Shell ────────────────── */
        .cert-container {
            width: 100%;
            max-width: 1040px;
            position: relative;
            box-shadow: 0 32px 100px rgba(0,0,0,.65), 0 0 0 1px rgba(255,255,255,.05);
            border-radius: 4px;
            overflow: hidden;
            background: #ffffff;
            aspect-ratio: 1024 / 645;
        }

        /* ── Background Frame (Exact 100% Visual Replica) ─ */
        .cert-bg-layer {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: fill;
            pointer-events: none;
            z-index: 1;
        }

        /* ── Subtle Center Watermark ───────────────── */
        .cert-watermark {
            position: absolute;
            top: 48%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 32%;
            opacity: 0.035;
            pointer-events: none;
            z-index: 2;
            filter: grayscale(100%) contrast(150%);
        }

        /* ── Content Layer Overlaid ──────────────────── */
        .cert-content {
            position: absolute;
            inset: 0;
            z-index: 5;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 6.8% 9.5% 6% 9.5%;
            text-align: center;
        }

        /* Top Header */
        .cert-top-block {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .cert-title-main {
            font-family: 'Cinzel', serif;
            font-size: clamp(24px, 4.3vw, 44px);
            font-weight: 700;
            color: #0b2546;
            letter-spacing: 0.32em;
            text-transform: uppercase;
            line-height: 1.1;
            margin-right: -0.32em;
        }

        .cert-title-sub {
            font-family: 'Cinzel', serif;
            font-size: clamp(10px, 1.8vw, 18px);
            font-weight: 700;
            color: #0b2546;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            margin-top: 6px;
            margin-right: -0.22em;
        }

        /* 3 Diamonds Divider */
        .diamonds-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 10px;
            margin-bottom: 12px;
        }
        .diamond-gold {
            width: clamp(6px, 0.8vw, 8px);
            height: clamp(6px, 0.8vw, 8px);
            background: #f59e0b;
            transform: rotate(45deg);
            display: inline-block;
        }
        .diamond-navy {
            width: clamp(9px, 1.2vw, 13px);
            height: clamp(9px, 1.2vw, 13px);
            background: #0b2546;
            transform: rotate(45deg);
            display: inline-block;
        }

        .cert-presented-to {
            font-family: 'Montserrat', sans-serif;
            font-size: clamp(10px, 1.25vw, 13px);
            font-weight: 500;
            color: #475569;
            letter-spacing: 0.02em;
        }

        /* Recipient Name in Calligraphy */
        .cert-recipient-name {
            font-family: 'Great Vibes', cursive;
            font-size: clamp(34px, 5.8vw, 58px);
            font-weight: 400;
            color: #0f172a;
            line-height: 1.15;
            margin-top: 4px;
            margin-bottom: 6px;
            text-shadow: 0 1px 1px rgba(0,0,0,.08);
        }

        /* Professional Academic Certificate Body */
        .cert-body-block {
            max-width: 660px;
            margin: 0 auto;
            padding: 0 12px;
        }

        .cert-statement {
            font-family: 'Inter', sans-serif;
            font-size: clamp(8.5px, 1.15vw, 12px);
            line-height: 1.62;
            color: #334155;
            font-weight: 400;
        }

        .cert-course-name {
            font-family: 'Cinzel', serif;
            font-size: clamp(11px, 1.6vw, 16px);
            font-weight: 800;
            color: #0b2546;
            margin: 4px 0;
            letter-spacing: 0.04em;
        }

        .cert-conferral {
            font-family: 'Inter', sans-serif;
            font-size: clamp(8px, 1.05vw, 11px);
            line-height: 1.55;
            color: #64748b;
        }

        /* Bottom Section: Signatures (Center Seal is already in background image) */
        .cert-footer-row {
            display: grid;
            grid-template-columns: 1fr 140px 1fr;
            align-items: end;
            width: 100%;
            margin-top: auto;
            padding-bottom: 6px;
        }

        .sig-col {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .sig-left { padding-right: 20px; }
        .sig-right { padding-left: 20px; }

        .sig-svg-wrap {
            height: clamp(24px, 3.8vw, 38px);
            display: flex;
            align-items: flex-end;
            justify-content: center;
            margin-bottom: 4px;
        }
        .sig-svg-wrap svg {
            height: 100%;
            max-width: 160px;
            fill: none;
            stroke: #0b2546;
            stroke-width: 2.2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .sig-line {
            width: 100%;
            max-width: 200px;
            border-top: 1.5px solid #0b2546;
            margin-bottom: 4px;
        }

        .sig-label {
            font-family: 'Montserrat', sans-serif;
            font-size: clamp(9px, 1.15vw, 12px);
            font-weight: 600;
            color: #0b2546;
            letter-spacing: 0.02em;
        }

        .sig-role {
            font-family: 'Inter', sans-serif;
            font-size: clamp(7.5px, 0.95vw, 10px);
            color: #64748b;
            margin-top: 1px;
        }

        /* Verification metadata badge in micro-footer */
        .cert-meta-tag {
            font-family: 'JetBrains Mono', monospace;
            font-size: clamp(6.5px, 0.8vw, 8.5px);
            color: #94a3b8;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-top: 1px;
        }

        /* ── Under-Certificate Verification Bar ──────── */
        .verify-info-bar {
            width: 100%;
            max-width: 1040px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 16px;
            padding: 12px 18px;
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 12px;
            font-size: 12px;
            color: #94a3b8;
            flex-wrap: wrap;
        }
        .verify-info-bar a {
            color: #38bdf8;
            text-decoration: none;
            font-weight: 600;
            word-break: break-all;
        }
        .verify-info-bar a:hover { text-decoration: underline; }

        /* ── Print Media Query ───────────────────────── */
        @media print {
            @page {
                size: A4 landscape;
                margin: 0;
            }
            html, body {
                background: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                display: block;
            }
            .no-print { display: none !important; }
            .cert-container {
                box-shadow: none !important;
                border-radius: 0 !important;
                width: 100vw !important;
                height: 100vh !important;
                max-width: none !important;
                aspect-ratio: unset !important;
            }
            .cert-content {
                padding: 6% 9.5% 5.5% 9.5% !important;
            }
        }
    </style>
</head>
<body>

    @php
        $composite = $enrollment->id . $enrollment->course_id . $enrollment->user_id;
        $serial    = 'LNR-CERT-' . $composite;
        $verifyUrl = url('/verify/certificate/' . $serial);
        $issueDate = $enrollment->updated_at ? $enrollment->updated_at->format('d F Y') : date('d F Y');
    @endphp

    {{-- ── Action Toolbar ─────────────────────────────── --}}
    <div class="action-bar no-print">
        <a href="{{ route('student.certificates') }}" class="btn-back">
            <i class="fas fa-arrow-left" style="color:#f59e0b"></i> Back to My Certificates
        </a>
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
            <a href="{{ $verifyUrl }}" target="_blank" class="btn-verify">
                <i class="fas fa-shield-halved"></i> Verify Online
            </a>
            <button onclick="window.print()" class="btn-print">
                <i class="fas fa-print"></i> Print / Download PDF
            </button>
        </div>
    </div>

    {{-- ── Certificate Canvas ─────────────────────────── --}}
    <div class="cert-container" id="certificate-node">

        {{-- Background Frame (100% Visual Replica of Reference) --}}
        <picture>
            <source srcset="{{ asset('images/certificate-frame.webp') }}" type="image/webp">
            <img src="{{ asset('images/certificate-frame.png') }}" alt="Certificate Frame" class="cert-bg-layer" aria-hidden="true">
        </picture>

        {{-- Subtle Learnerium Logo Watermark --}}
        <img src="{{ asset('logo-only.png') }}" alt="" class="cert-watermark" aria-hidden="true">

        {{-- Dynamic Content Layer --}}
        <div class="cert-content">

            {{-- Top Header Block --}}
            <div class="cert-top-block">
                <h1 class="cert-title-main">CERTIFICATE</h1>
                <h2 class="cert-title-sub">OF ACHIEVEMENT</h2>

                {{-- 3 Diamonds Divider --}}
                <div class="diamonds-row" aria-hidden="true">
                    <span class="diamond-gold"></span>
                    <span class="diamond-navy"></span>
                    <span class="diamond-gold"></span>
                </div>

                <p class="cert-presented-to">This certificate is proudly presented to</p>
            </div>

            {{-- Recipient Name in Calligraphy --}}
            <div class="cert-recipient-name">
                {{ $user->name }}
            </div>

            {{-- Professional Academic Certificate Statement --}}
            <div class="cert-body-block">
                <p class="cert-statement">
                    In recognition of academic dedication, demonstrated excellence, and the successful completion of all prescribed curriculum requirements and comprehensive assessments for the accredited course in
                </p>
                <h3 class="cert-course-name">{{ $course->title }}</h3>
                <p class="cert-conferral">
                    conferring this credential with all honors, rights, and privileges pertaining thereto, as authenticated and recorded in the official registry of Learnerium Academy.
                </p>
            </div>

            {{-- Signatures Row (Medallion Seal is in Center of Background Frame) --}}
            <div class="cert-footer-row">

                {{-- Left: Lead Instructor Signature --}}
                <div class="sig-col sig-left">
                    <div class="sig-svg-wrap">
                        {{-- Realistic Vector Handwriting Signature --}}
                        <svg viewBox="0 0 160 40">
                            <path d="M10 28 C 30 8, 40 32, 55 16 C 65 5, 75 35, 88 18 C 96 6, 108 30, 120 14 C 130 5, 140 24, 152 18" />
                            <path d="M35 24 C 60 22, 110 18, 145 20" stroke-width="1.4" opacity="0.6"/>
                        </svg>
                    </div>
                    <div class="sig-line"></div>
                    <div class="sig-label">Signature</div>
                    <div class="sig-role">{{ $course->instructor?->name ?? 'Course Instructor' }}</div>
                </div>

                {{-- Center Spacer for Medallion Seal (Already rendered in frame) --}}
                <div></div>

                {{-- Right: Academic Director Signature & Date --}}
                <div class="sig-col sig-right">
                    <div class="sig-svg-wrap">
                        {{-- Realistic Vector Handwriting Signature --}}
                        <svg viewBox="0 0 160 40">
                            <path d="M14 26 C 26 10, 38 34, 50 14 C 60 4, 70 32, 82 12 C 92 2, 104 28, 118 16 C 128 7, 138 22, 148 15" />
                            <path d="M28 20 C 55 18, 95 16, 135 18" stroke-width="1.4" opacity="0.6"/>
                        </svg>
                    </div>
                    <div class="sig-line"></div>
                    <div class="sig-label">Signature</div>
                    <div class="sig-role">Academic Director &bull; {{ $issueDate }}</div>
                </div>

            </div>

        </div>{{-- /cert-content --}}

    </div>{{-- /cert-container --}}

    {{-- ── Verification Metadata Bar Underneath ────────── --}}
    <div class="verify-info-bar no-print">
        <div style="display:flex;align-items:center;gap:8px">
            <i class="fas fa-shield-halved" style="color:#10b981"></i>
            <span>Verified Serial: <strong style="color:#fff;font-family:'JetBrains Mono',monospace">{{ $serial }}</strong></span>
        </div>
        <div style="display:flex;align-items:center;gap:6px">
            <i class="fas fa-link" style="color:#38bdf8"></i>
            <span>Public Verification URL:</span>
            <a href="{{ $verifyUrl }}" target="_blank">{{ $verifyUrl }}</a>
        </div>
    </div>

</body>
</html>
