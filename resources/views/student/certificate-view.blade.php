<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Achievement — {{ $user->name }} — {{ $course->title }}</title>
    <meta name="description" content="Official certificate of achievement from Learnerium Academy awarded to {{ $user->name }} for completing {{ $course->title }}.">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

    {{-- Typography --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Great+Vibes&family=Inter:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --jlm-blue:    #1b2299;
            --jlm-blue-dk: #10155b;
            --jlm-magenta: #e4306d;
            --jlm-gold:    #f59e0b;
            --jlm-gold-dk: #d97706;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #080d1f;
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
            max-width: 1024px;
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
            color: #ffffff; padding: 10px 22px; border-radius: 12px; border: none; cursor: pointer;
            background: linear-gradient(135deg, #1b2299 0%, #e4306d 100%);
            box-shadow: 0 4px 18px rgba(228,48,109,.35);
            transition: all .2s;
        }
        .btn-print:hover { transform: translateY(-1px); box-shadow: 0 6px 24px rgba(228,48,109,.45); }

        /* ── Certificate Canvas Shell ────────────────── */
        .cert-container {
            width: 100%;
            max-width: 1024px;
            position: relative;
            box-shadow: 0 32px 100px rgba(0,0,0,.75), 0 0 0 1px rgba(255,255,255,.08);
            border-radius: 4px;
            overflow: hidden;
            background: #ffffff;
            aspect-ratio: 1024 / 660;
        }

        /* ── Vector Background Frame Layer (Zero Halos, Zero Green Outlines) ─ */
        .cert-bg-layer {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: fill;
            pointer-events: none;
            z-index: 1;
        }

        /* ── Banknote-Grade Guilloche Security Watermark ─ */
        .cert-watermark-layer {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 48%;
            max-width: 440px;
            opacity: 0.16;
            pointer-events: none;
            z-index: 2;
        }

        /* ── Content Layer (Safely Padded ABOVE all graphics) ── */
        .cert-content {
            position: absolute;
            inset: 0;
            z-index: 5;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            /* Top 5.5%, Left/Right 7.5%, Bottom 7.8% (ensures text sits cleanly ABOVE bottom graphic bar) */
            padding: 5.2% 7.8% 7.5% 7.8%;
            text-align: center;
        }

        /* ── Top Header ──────────────────────────────── */
        .cert-top-block {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .cert-academy-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 2px;
        }
        .cert-academy-brand .h-line {
            width: 32px;
            height: 1.5px;
            background: linear-gradient(to right, transparent, var(--jlm-magenta));
        }
        .cert-academy-brand .h-line.r {
            background: linear-gradient(to left, transparent, var(--jlm-magenta));
        }
        .cert-academy-text {
            font-family: 'Montserrat', sans-serif;
            font-size: clamp(7.5px, 1.05vw, 10.5px);
            font-weight: 800;
            letter-spacing: 0.35em;
            text-transform: uppercase;
            color: var(--jlm-magenta);
        }

        .cert-title-main {
            font-family: 'Cinzel', serif;
            font-size: clamp(22px, 4.2vw, 42px);
            font-weight: 700;
            color: var(--jlm-blue);
            letter-spacing: 0.30em;
            text-transform: uppercase;
            line-height: 1.1;
            margin-right: -0.30em;
        }

        .cert-title-sub {
            font-family: 'Cinzel', serif;
            font-size: clamp(9.5px, 1.7vw, 16px);
            font-weight: 700;
            color: var(--jlm-blue-dk);
            letter-spacing: 0.22em;
            text-transform: uppercase;
            margin-top: 3px;
            margin-right: -0.22em;
        }

        /* 3 Diamonds Divider */
        .diamonds-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 6px;
            margin-bottom: 8px;
        }
        .diamond-gold {
            width: clamp(5px, 0.75vw, 7.5px);
            height: clamp(5px, 0.75vw, 7.5px);
            background: var(--jlm-gold);
            transform: rotate(45deg);
            display: inline-block;
        }
        .diamond-blue {
            width: clamp(8px, 1.1vw, 12px);
            height: clamp(8px, 1.1vw, 12px);
            background: var(--jlm-blue);
            transform: rotate(45deg);
            display: inline-block;
        }
        .diamond-magenta {
            width: clamp(5px, 0.75vw, 7.5px);
            height: clamp(5px, 0.75vw, 7.5px);
            background: var(--jlm-magenta);
            transform: rotate(45deg);
            display: inline-block;
        }

        .cert-presented-to {
            font-family: 'Montserrat', sans-serif;
            font-size: clamp(9px, 1.15vw, 12px);
            font-weight: 500;
            color: #475569;
            letter-spacing: 0.03em;
        }

        /* ── Recipient Name in Flowing Calligraphy ─────── */
        .cert-recipient-name {
            font-family: 'Great Vibes', cursive;
            font-size: clamp(32px, 5.2vw, 54px);
            font-weight: 400;
            color: #0f172a;
            line-height: 1.12;
            margin-top: 1px;
            margin-bottom: 4px;
        }

        /* ── Academic Body (British English) ─────────── */
        .cert-body-block {
            max-width: 640px;
            margin: 0 auto;
            padding: 0 6px;
        }

        .cert-statement {
            font-family: 'Inter', sans-serif;
            font-size: clamp(8px, 1.05vw, 11px);
            line-height: 1.54;
            color: #334155;
            font-weight: 400;
        }

        /* ── Prominent Course Title ──────────────────── */
        .cert-course-wrap {
            margin: 4px 0;
            display: inline-flex;
            flex-direction: column;
            align-items: center;
        }
        .cert-course-name {
            font-family: 'Cinzel', serif;
            font-size: clamp(12px, 1.8vw, 18.5px);
            font-weight: 800;
            color: var(--jlm-blue);
            letter-spacing: 0.06em;
            line-height: 1.25;
            text-transform: uppercase;
        }
        .cert-course-rule {
            width: 85%;
            height: 1.5px;
            background: linear-gradient(90deg, transparent, var(--jlm-gold), var(--jlm-magenta), transparent);
            margin-top: 2px;
        }

        .cert-conferral {
            font-family: 'Inter', sans-serif;
            font-size: clamp(7.5px, 0.95vw, 10px);
            line-height: 1.48;
            color: #64748b;
        }

        /* ── Bottom Section: Facilitator Signature | Medallion Seal | Date & QR ── */
        .cert-footer-row {
            display: grid;
            grid-template-columns: 1fr 140px 1fr;
            align-items: flex-end;
            width: 100%;
            margin-top: auto;
            padding-bottom: 2px;
            gap: 16px;
        }

        .sig-col {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .sig-left {
            align-items: flex-start;
            padding-left: 14px;
        }

        .sig-right {
            align-items: flex-end;
            padding-right: 14px;
        }

        /* Left: Facilitator Cursive Signature */
        .facilitator-sig-cursive {
            font-family: 'Great Vibes', cursive;
            font-size: clamp(24px, 3.4vw, 34px);
            color: var(--jlm-blue);
            height: clamp(28px, 3.8vw, 38px);
            display: flex;
            align-items: flex-end;
            line-height: 1;
            padding-bottom: 1px;
            width: 100%;
            max-width: 190px;
        }

        .sig-line {
            width: 100%;
            max-width: 190px;
            border-top: 1.5px solid var(--jlm-blue);
            margin-bottom: 4px;
        }

        .sig-label {
            font-family: 'Montserrat', sans-serif;
            font-size: clamp(8.5px, 1.1vw, 11px);
            font-weight: 700;
            color: var(--jlm-blue-dk);
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .sig-role {
            font-family: 'Inter', sans-serif;
            font-size: clamp(7px, 0.9vw, 9.5px);
            color: #64748b;
            margin-top: 1px;
            font-weight: 500;
        }

        /* Center: 3D Embossed Luxury Academic Seal (Clean, Self-Contained) */
        .seal-medallion-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: clamp(80px, 10vw, 105px);
            height: clamp(80px, 10vw, 105px);
            margin: 0 auto;
        }

        .seal-svg {
            width: 100%;
            height: 100%;
            filter: drop-shadow(0 4px 12px rgba(20,26,115,.22));
        }

        /* Right: Formatted Date of Issue & Structured Verification Credential Box */
        .date-section {
            width: 100%;
            max-width: 220px;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            margin-bottom: 6px;
        }

        .date-val {
            font-family: 'Montserrat', sans-serif;
            font-size: clamp(9px, 1.15vw, 12px);
            font-weight: 700;
            color: #0f172a;
            letter-spacing: 0.02em;
            line-height: 1;
            margin-bottom: 4px;
        }

        .date-line {
            width: 100%;
            border-top: 1.5px solid var(--jlm-blue);
            margin-bottom: 4px;
        }

        .date-label {
            font-family: 'Montserrat', sans-serif;
            font-size: clamp(8px, 1.0vw, 10.5px);
            font-weight: 700;
            color: var(--jlm-blue-dk);
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        /* Official Verification Credential Box with Clear Context */
        .verify-card {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 8px;
            background: #ffffff;
            border: 1px solid rgba(27,34,153,.18);
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,.04);
            max-width: 220px;
            width: 100%;
            text-align: left;
        }

        .qr-frame {
            width: clamp(34px, 4.4vw, 44px);
            height: clamp(34px, 4.4vw, 44px);
            flex-shrink: 0;
            border-radius: 4px;
            overflow: hidden;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .qr-frame img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .verify-text-wrap {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .verify-badge-tag {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            font-size: clamp(5.5px, 0.72vw, 7.5px);
            font-weight: 800;
            color: #059669;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .verify-serial-tag {
            font-family: 'JetBrains Mono', monospace;
            font-size: clamp(6.5px, 0.8vw, 8.5px);
            font-weight: 700;
            color: var(--jlm-blue);
            letter-spacing: 0.02em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .verify-hint {
            font-size: clamp(5px, 0.65vw, 7px);
            color: #64748b;
            line-height: 1.1;
            margin-top: 1px;
        }

        /* ── Under-Certificate Verification Bar ──────── */
        .verify-info-bar {
            width: 100%;
            max-width: 1024px;
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
                padding: 4.8% 7.8% 6.5% 7.8% !important;
            }
        }
    </style>
</head>
<body>

    @php
        $composite = $enrollment->id . $enrollment->course_id . $enrollment->user_id;
        $serial    = 'LNR-CERT-' . $composite;
        $verifyUrl = url('/verify/certificate/' . $serial);
        $qrApiUrl  = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($verifyUrl) . '&size=140x140&ecc=H&margin=1&color=1b2299';
        $issueDate = $enrollment->updated_at ? $enrollment->updated_at->format('d F Y') : date('d F Y');
        $facilitatorName = optional($course->instructor)->name ?? 'Course Facilitator';
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

        {{-- Pure Vector Brand Frame Layer (Zero Halos, Zero Green Outlines) --}}
        <img src="{{ asset('images/certificate-frame.svg') }}" alt="Certificate Frame" class="cert-bg-layer" aria-hidden="true">

        {{-- Banknote-Grade Guilloche Security Watermark --}}
        <img src="{{ asset('images/certificate-watermark.svg') }}" alt="Learnerium Security Watermark" class="cert-watermark-layer" aria-hidden="true">

        {{-- Dynamic Certificate Content (Positioned Safely Above All Frame Graphics) --}}
        <div class="cert-content">

            {{-- Top Header Block --}}
            <div class="cert-top-block">
                <div class="cert-academy-brand">
                    <span class="h-line"></span>
                    <span class="cert-academy-text">Learnerium Academy</span>
                    <span class="h-line r"></span>
                </div>

                <h1 class="cert-title-main">CERTIFICATE</h1>
                <h2 class="cert-title-sub">OF ACHIEVEMENT</h2>

                {{-- 3 Diamonds Divider --}}
                <div class="diamonds-row" aria-hidden="true">
                    <span class="diamond-gold"></span>
                    <span class="diamond-blue"></span>
                    <span class="diamond-magenta"></span>
                </div>

                <p class="cert-presented-to">This certificate is proudly presented to</p>
            </div>

            {{-- Recipient Name in Calligraphy --}}
            <div class="cert-recipient-name">
                {{ $user->name }}
            </div>

            {{-- Professional Academic Certificate Statement (British English) --}}
            <div class="cert-body-block">
                <p class="cert-statement">
                    In recognition of academic dedication, demonstrated excellence, and the successful completion of all prescribed curriculum requirements and comprehensive assessments for the accredited programme in
                </p>

                {{-- Prominent Course Title --}}
                <div class="cert-course-wrap">
                    <h3 class="cert-course-name">{{ $course->title }}</h3>
                    <div class="cert-course-rule"></div>
                </div>

                <p class="cert-conferral">
                    conferring this credential with all honours, rights, and privileges pertaining thereto, as authenticated and recorded in the official registry of Learnerium Academy.
                </p>
            </div>

            {{-- Bottom Section: Facilitator Signature | Embossed Medallion Seal | Date & Verification --}}
            <div class="cert-footer-row">

                {{-- Left: Course Facilitator Signature --}}
                <div class="sig-col sig-left">
                    <div class="facilitator-sig-cursive">
                        {{ $facilitatorName }}
                    </div>
                    <div class="sig-line"></div>
                    <div class="sig-label">Course Facilitator</div>
                    <div class="sig-role">{{ $facilitatorName }}</div>
                </div>

                {{-- Centre: Luxury 3D Embossed Academic Medallion Seal (Self-Contained) --}}
                <div class="sig-col">
                    <div class="seal-medallion-wrap" title="Learnerium Academy Verified Academic Credential">
                        <svg class="seal-svg" viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <radialGradient id="goldPlate" cx="40%" cy="35%" r="65%">
                                    <stop offset="0%" stop-color="#fffbeb"/>
                                    <stop offset="30%" stop-color="#fde047"/>
                                    <stop offset="60%" stop-color="#f59e0b"/>
                                    <stop offset="100%" stop-color="#b45309"/>
                                </radialGradient>
                                <radialGradient id="blueCore" cx="40%" cy="35%" r="65%">
                                    <stop offset="0%" stop-color="#2a35d4"/>
                                    <stop offset="70%" stop-color="#1b2299"/>
                                    <stop offset="100%" stop-color="#10155b"/>
                                </radialGradient>
                                <filter id="sealDepth" x="-10%" y="-10%" width="120%" height="120%">
                                    <feDropShadow dx="0" dy="2" stdDeviation="2" flood-color="#78350f" flood-opacity="0.3"/>
                                </filter>
                            </defs>

                            <!-- Outer 32-point Scalloped Gold Gear Rim -->
                            <g fill="url(#goldPlate)" filter="url(#sealDepth)">
                                @for ($i = 0; $i < 32; $i++)
                                    <circle cx="{{ 60 + 52 * cos(deg2rad($i * 11.25)) }}" cy="{{ 60 + 52 * sin(deg2rad($i * 11.25)) }}" r="4.5"/>
                                @endfor
                                <circle cx="60" cy="60" r="50"/>
                            </g>

                            <!-- Outer Gold Relief Ring -->
                            <circle cx="60" cy="60" r="47" fill="none" stroke="#fef08a" stroke-width="1.2"/>
                            <circle cx="60" cy="60" r="44" fill="none" stroke="#92400e" stroke-width="0.8"/>

                            <!-- Royal Blue Core Disc -->
                            <circle cx="60" cy="60" r="42" fill="url(#blueCore)"/>

                            <!-- Inner Gold Beaded Dashed Ring -->
                            <circle cx="60" cy="60" r="39" fill="none" stroke="#fbbf24" stroke-width="1.2" stroke-dasharray="2.5 2.5"/>

                            <!-- Circular Academic Seal Inscription -->
                            <path id="sealTextTop" d="M 23 60 A 37 37 0 1 1 97 60" fill="none"/>
                            <path id="sealTextBot" d="M 97 60 A 37 37 0 0 1 23 60" fill="none"/>

                            <text font-family="'Cinzel', serif" font-size="5.2" font-weight="900" fill="#ffffff" letter-spacing="1.2">
                                <textPath href="#sealTextTop" startOffset="50%" text-anchor="middle">LEARNERIUM ACADEMY</textPath>
                            </text>
                            <text font-family="'Montserrat', sans-serif" font-size="4.4" font-weight="800" fill="#fbbf24" letter-spacing="1.2">
                                <textPath href="#sealTextBot" startOffset="50%" text-anchor="middle">OFFICIAL CREDENTIAL</textPath>
                            </text>

                            <!-- Center Gold 5-Point Star with Academic Laurel Flares -->
                            <polygon points="60,47 62.5,53.5 69.5,53.5 64,57.5 66.5,64 60,60 53.5,64 56,57.5 50.5,53.5 57.5,53.5" fill="#fde047" stroke="#b45309" stroke-width="0.5"/>
                            <circle cx="60" cy="60" r="13" fill="none" stroke="#fbbf24" stroke-width="0.8" opacity="0.6"/>
                            <circle cx="60" cy="60" r="1.5" fill="#ffffff"/>
                        </svg>
                    </div>
                </div>

                {{-- Right: Date of Issuance & Structured Verification Credential Box --}}
                <div class="sig-col sig-right">
                    <div class="date-section">
                        <div class="date-val">{{ $issueDate }}</div>
                        <div class="date-line"></div>
                        <div class="date-label">Date of Issuance</div>
                    </div>

                    {{-- Structured Verification Box with Clear Purpose & Context --}}
                    <div class="verify-card" title="Official digital verification barcode">
                        <div class="qr-frame">
                            <img src="{{ $qrApiUrl }}" alt="Scan QR Code to Verify Certificate">
                        </div>
                        <div class="verify-text-wrap">
                            <span class="verify-badge-tag">
                                <i class="fas fa-shield-check"></i> Verified Record
                            </span>
                            <span class="verify-serial-tag">{{ $serial }}</span>
                            <span class="verify-hint">Scan to verify authenticity</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>{{-- /cert-content --}}

    </div>{{-- /cert-container --}}

    {{-- ── Verification Metadata Bar Underneath ────────── --}}
    <div class="verify-info-bar no-print">
        <div style="display:flex;align-items:center;gap:8px">
            <i class="fas fa-shield-halved" style="color:#10b981"></i>
            <span>Verified Credential: <strong style="color:#fff;font-family:'JetBrains Mono',monospace">{{ $serial }}</strong></span>
        </div>
        <div style="display:flex;align-items:center;gap:6px">
            <i class="fas fa-link" style="color:#38bdf8"></i>
            <span>Public Verification URL:</span>
            <a href="{{ $verifyUrl }}" target="_blank">{{ $verifyUrl }}</a>
        </div>
    </div>

</body>
</html>
