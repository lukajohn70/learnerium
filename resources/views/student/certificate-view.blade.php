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

    {{-- Fonts: Cinzel for classical headers, Great Vibes for calligraphy names & facilitator signature, Montserrat & Inter for body --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Great+Vibes&family=Inter:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --jlm-blue:    #1b2299;
            --jlm-blue-dk: #141a73;
            --jlm-magenta: #e4306d;
            --jlm-gold:    #f59e0b;
            --jlm-gold-dk: #d97706;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #090f24;
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
            color: #ffffff; padding: 10px 22px; border-radius: 12px; border: none; cursor: pointer;
            background: linear-gradient(135deg, #1b2299 0%, #e4306d 100%);
            box-shadow: 0 4px 18px rgba(228,48,109,.35);
            transition: all .2s;
        }
        .btn-print:hover { transform: translateY(-1px); box-shadow: 0 6px 24px rgba(228,48,109,.45); }

        /* ── Certificate Canvas Shell ────────────────── */
        .cert-container {
            width: 100%;
            max-width: 1040px;
            position: relative;
            box-shadow: 0 32px 100px rgba(0,0,0,.7), 0 0 0 1px rgba(255,255,255,.08);
            border-radius: 4px;
            overflow: hidden;
            background: #ffffff;
            aspect-ratio: 1024 / 645;
        }

        /* ── Background Brand Frame Layer ───────────── */
        .cert-bg-layer {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: fill;
            pointer-events: none;
            z-index: 1;
        }

        /* ── Prominent Learnerium Brand Watermark ────── */
        .cert-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 35%;
            max-width: 320px;
            opacity: 0.12;
            pointer-events: none;
            z-index: 2;
            filter: saturate(1.2);
        }

        /* ── Content Layer Overlaid ──────────────────── */
        .cert-content {
            position: absolute;
            inset: 0;
            z-index: 5;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 6.5% 9% 4.5% 9%;
            text-align: center;
        }

        /* Top Header */
        .cert-top-block {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .cert-academy-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 4px;
        }
        .cert-academy-brand .h-line {
            width: 36px;
            height: 1.5px;
            background: linear-gradient(to right, transparent, var(--jlm-magenta));
        }
        .cert-academy-brand .h-line.r {
            background: linear-gradient(to left, transparent, var(--jlm-magenta));
        }
        .cert-academy-text {
            font-family: 'Montserrat', sans-serif;
            font-size: clamp(8px, 1.1vw, 11px);
            font-weight: 800;
            letter-spacing: 0.35em;
            text-transform: uppercase;
            color: var(--jlm-magenta);
        }

        .cert-title-main {
            font-family: 'Cinzel', serif;
            font-size: clamp(24px, 4.4vw, 44px);
            font-weight: 700;
            color: var(--jlm-blue);
            letter-spacing: 0.32em;
            text-transform: uppercase;
            line-height: 1.1;
            margin-right: -0.32em;
        }

        .cert-title-sub {
            font-family: 'Cinzel', serif;
            font-size: clamp(10px, 1.8vw, 17px);
            font-weight: 700;
            color: var(--jlm-blue-dk);
            letter-spacing: 0.22em;
            text-transform: uppercase;
            margin-top: 4px;
            margin-right: -0.22em;
        }

        /* 3 Diamonds Divider */
        .diamonds-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 8px;
            margin-bottom: 10px;
        }
        .diamond-gold {
            width: clamp(6px, 0.8vw, 8px);
            height: clamp(6px, 0.8vw, 8px);
            background: var(--jlm-gold);
            transform: rotate(45deg);
            display: inline-block;
        }
        .diamond-blue {
            width: clamp(9px, 1.2vw, 13px);
            height: clamp(9px, 1.2vw, 13px);
            background: var(--jlm-blue);
            transform: rotate(45deg);
            display: inline-block;
        }
        .diamond-magenta {
            width: clamp(6px, 0.8vw, 8px);
            height: clamp(6px, 0.8vw, 8px);
            background: var(--jlm-magenta);
            transform: rotate(45deg);
            display: inline-block;
        }

        .cert-presented-to {
            font-family: 'Montserrat', sans-serif;
            font-size: clamp(9.5px, 1.2vw, 13px);
            font-weight: 500;
            color: #475569;
            letter-spacing: 0.03em;
        }

        /* Recipient Name in Flowing Calligraphy */
        .cert-recipient-name {
            font-family: 'Great Vibes', cursive;
            font-size: clamp(34px, 5.6vw, 56px);
            font-weight: 400;
            color: #0f172a;
            line-height: 1.15;
            margin-top: 2px;
            margin-bottom: 6px;
        }

        /* Professional Academic Certificate Body (British English) */
        .cert-body-block {
            max-width: 660px;
            margin: 0 auto;
            padding: 0 8px;
        }

        .cert-statement {
            font-family: 'Inter', sans-serif;
            font-size: clamp(8.5px, 1.1vw, 11.5px);
            line-height: 1.58;
            color: #334155;
            font-weight: 400;
        }

        /* Prominent Course Title */
        .cert-course-wrap {
            margin: 6px 0;
            display: inline-flex;
            flex-direction: column;
            align-items: center;
        }
        .cert-course-name {
            font-family: 'Cinzel', serif;
            font-size: clamp(13px, 1.9vw, 20px);
            font-weight: 800;
            color: var(--jlm-blue);
            letter-spacing: 0.06em;
            line-height: 1.25;
            text-transform: uppercase;
        }
        .cert-course-rule {
            width: 80%;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--jlm-gold), var(--jlm-magenta), transparent);
            margin-top: 3px;
        }

        .cert-conferral {
            font-family: 'Inter', sans-serif;
            font-size: clamp(8px, 1.0vw, 10.5px);
            line-height: 1.52;
            color: #64748b;
        }

        /* ── Bottom Section: Facilitator Signature | Brand Seal | QR & Issue Date ── */
        .cert-footer-row {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: end;
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

        .sig-left { padding-right: 12px; }
        .sig-right { padding-left: 12px; }

        /* Left: Facilitator Cursive Signature */
        .facilitator-sig-cursive {
            font-family: 'Great Vibes', cursive;
            font-size: clamp(22px, 3.2vw, 32px);
            color: var(--jlm-blue);
            height: clamp(28px, 4vw, 40px);
            display: flex;
            align-items: flex-end;
            justify-content: center;
            line-height: 1;
            padding-bottom: 2px;
        }

        .sig-line {
            width: 100%;
            max-width: 185px;
            border-top: 1.5px solid var(--jlm-blue);
            margin-bottom: 4px;
        }

        .sig-label {
            font-family: 'Montserrat', sans-serif;
            font-size: clamp(9px, 1.15vw, 12px);
            font-weight: 700;
            color: var(--jlm-blue-dk);
            letter-spacing: 0.03em;
        }

        .sig-role {
            font-family: 'Inter', sans-serif;
            font-size: clamp(7.5px, 0.95vw, 10px);
            color: #64748b;
            margin-top: 1px;
            font-weight: 500;
        }

        /* Center: Bespoke Learnerium Official Academic Seal */
        .seal-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            position: relative;
            width: clamp(88px, 11vw, 115px);
            height: clamp(88px, 11vw, 115px);
            margin: 0 auto;
        }

        .seal-svg {
            width: 100%;
            height: 100%;
            filter: drop-shadow(0 6px 14px rgba(0,0,0,.22));
        }

        /* Right: QR Code + Issue Date */
        .qr-code-wrap {
            width: clamp(40px, 5.2vw, 54px);
            height: clamp(40px, 5.2vw, 54px);
            margin-bottom: 4px;
            padding: 3px;
            background: #ffffff;
            border: 1px solid rgba(27,34,153,.2);
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,.06);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .qr-code-wrap img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .cert-serial-tag {
            font-family: 'JetBrains Mono', monospace;
            font-size: clamp(6.5px, 0.8vw, 8.5px);
            font-weight: 700;
            color: var(--jlm-blue);
            background: rgba(27,34,153,.07);
            padding: 2px 7px;
            border-radius: 4px;
            letter-spacing: 0.06em;
            margin-top: 3px;
            display: inline-block;
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
                padding: 5.5% 8.5% 4% 8.5% !important;
            }
        }
    </style>
</head>
<body>

    @php
        $composite = $enrollment->id . $enrollment->course_id . $enrollment->user_id;
        $serial    = 'LNR-CERT-' . $composite;
        $verifyUrl = url('/verify/certificate/' . $serial);
        $qrApiUrl  = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($verifyUrl) . '&size=140x140&ecc=H&margin=2&color=1b2299';
        $issueDate = $enrollment->updated_at ? $enrollment->updated_at->format('d F Y') : date('d F Y');
        $facilitatorName = $course->instructor?->name ?? 'Course Facilitator';
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

        {{-- Background Brand Frame (Learnerium Royal Blue, Magenta & Gold) --}}
        <picture>
            <source srcset="{{ asset('images/certificate-frame.webp') }}" type="image/webp">
            <img src="{{ asset('images/certificate-frame.png') }}" alt="Certificate Frame" class="cert-bg-layer" aria-hidden="true">
        </picture>

        {{-- Prominent Learnerium Brand Watermark in Centre --}}
        <img src="{{ asset('logo.png') }}" alt="Learnerium Logo" class="cert-watermark" aria-hidden="true">

        {{-- Dynamic Certificate Content --}}
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

            {{-- Bottom Section: Facilitator Signature | Bespoke Brand Seal | QR & Issue Date --}}
            <div class="cert-footer-row">

                {{-- Left: Facilitator's Signature (Cursive Script) --}}
                <div class="sig-col sig-left">
                    <div class="facilitator-sig-cursive">
                        {{ $facilitatorName }}
                    </div>
                    <div class="sig-line"></div>
                    <div class="sig-label">Facilitator's Signature</div>
                    <div class="sig-role">{{ $facilitatorName }} &bull; Lead Facilitator</div>
                </div>

                {{-- Centre: Bespoke Learnerium Verified Academic Seal --}}
                <div class="seal-wrap" title="Learnerium Academy Verified Academic Seal">
                    <svg class="seal-svg" viewBox="0 0 160 180" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="goldGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#fbbf24"/>
                                <stop offset="50%" stop-color="#f59e0b"/>
                                <stop offset="100%" stop-color="#d97706"/>
                            </linearGradient>
                            <linearGradient id="blueGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#222cb8"/>
                                <stop offset="100%" stop-color="#141a73"/>
                            </linearGradient>
                            <linearGradient id="magGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#f44582"/>
                                <stop offset="100%" stop-color="#b8164d"/>
                            </linearGradient>
                        </defs>

                        {{-- Ribbon Tails with Notched V-Ends --}}
                        {{-- Left Ribbon (Royal Blue) --}}
                        <polygon points="58,110 40,172 58,160 76,172 68,110" fill="url(#blueGrad)" stroke="#f59e0b" stroke-width="1.5"/>
                        {{-- Right Ribbon (Learnerium Magenta) --}}
                        <polygon points="102,110 84,172 102,160 120,172 92,110" fill="url(#magGrad)" stroke="#f59e0b" stroke-width="1.5"/>

                        {{-- 24-point Scalloped Outer Medallion Rim --}}
                        <circle cx="80" cy="74" r="54" fill="url(#goldGrad)" stroke="#d97706" stroke-width="1.5"/>

                        {{-- Scalloped Edge Teeth --}}
                        <g fill="url(#goldGrad)" stroke="#d97706" stroke-width="0.8">
                            @for ($i = 0; $i < 24; $i++)
                                <circle cx="{{ 80 + 53 * cos(deg2rad($i * 15)) }}" cy="{{ 74 + 53 * sin(deg2rad($i * 15)) }}" r="4"/>
                            @endfor
                        </g>

                        {{-- Outer Gold Ring --}}
                        <circle cx="80" cy="74" r="51" fill="none" stroke="#ffffff" stroke-width="1.2" opacity="0.8"/>

                        {{-- Deep Royal Blue Disc --}}
                        <circle cx="80" cy="74" r="46" fill="url(#blueGrad)"/>

                        {{-- Inner Beaded / Dashed Ring --}}
                        <circle cx="80" cy="74" r="42" fill="none" stroke="#fbbf24" stroke-width="1" stroke-dasharray="2.5 2.5"/>

                        {{-- Center Star & Emblems --}}
                        <polygon points="80,48 83,57 92,57 85,63 88,72 80,66 72,72 75,63 68,57 77,57" fill="#fbbf24"/>

                        {{-- Seal Typography --}}
                        <text x="80" y="80" font-family="'Cinzel', serif" font-size="6.5" font-weight="900" fill="#ffffff" text-anchor="middle" letter-spacing="1.5">LEARNERIUM</text>
                        <text x="80" y="88" font-family="'Montserrat', sans-serif" font-size="5.2" font-weight="800" fill="#fbbf24" text-anchor="middle" letter-spacing="1.2">ACADEMY</text>
                        <text x="80" y="96" font-family="'Cinzel', serif" font-size="5.5" font-weight="800" fill="#ffffff" text-anchor="middle" letter-spacing="2">OFFICIAL SEAL</text>
                    </svg>
                </div>

                {{-- Right: QR Code for Verification + Issue Date --}}
                <div class="sig-col sig-right">
                    <div class="qr-code-wrap" title="Scan to verify official credential">
                        <img src="{{ $qrApiUrl }}" alt="Verification QR Code">
                    </div>
                    <div class="sig-line"></div>
                    <div class="sig-label">Date of Issue</div>
                    <div class="sig-role">{{ $issueDate }}</div>
                    <div class="cert-serial-tag">{{ $serial }}</div>
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
