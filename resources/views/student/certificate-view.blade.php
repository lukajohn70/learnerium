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

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700;800;900&family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,400;1,600&family=Great+Vibes&family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --blue:    #1b2299;
            --blue-dk: #141a73;
            --pink:    #e4306d;
            --gold:    #d97706;
            --gold-lt: #f9e8a2;
            --gold-dk: #92400e;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #0f172a;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 32px 16px 48px;
            -webkit-font-smoothing: antialiased;
        }

        /* ── UI Buttons ─────────────────────────────── */
        .action-bar {
            width: 100%;
            max-width: 980px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        .btn-back {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 12px; font-weight: 700; color: #cbd5e1;
            background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1);
            padding: 10px 18px; border-radius: 14px; text-decoration: none;
            transition: all .2s;
        }
        .btn-back:hover { background: rgba(255,255,255,.12); color: #fff; }
        .btn-print {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: .08em;
            color: #1c1917; padding: 10px 22px; border-radius: 14px; border: none; cursor: pointer;
            background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 40%, #d97706 100%);
            box-shadow: 0 4px 20px rgba(245,158,11,.4);
            transition: all .2s;
        }
        .btn-print:hover { transform: translateY(-1px); box-shadow: 0 6px 28px rgba(245,158,11,.5); }

        .verify-link-bar {
            width: 100%; max-width: 980px;
            display: flex; align-items: center; justify-content: center;
            gap: 8px; margin-top: 20px;
            font-size: 11px; color: #475569; flex-wrap: wrap;
        }
        .verify-link-bar a { color: #94a3b8; text-decoration: none; font-weight: 600; }
        .verify-link-bar a:hover { color: #f9e8a2; }

        /* ── Certificate Outer Shell ─────────────────── */
        .cert-shell {
            width: 100%;
            max-width: 980px;
            aspect-ratio: 1.414 / 1;
            position: relative;
            background: #ffffff;
            box-shadow: 0 32px 100px rgba(0,0,0,.6), 0 0 0 1px rgba(255,255,255,.04);
            overflow: hidden;
        }

        /* ── Geometric Corner Ribbons (CSS-only, no images) ─ */
        /* Top-left: Blue */
        .ribbon-tl {
            position: absolute; top: 0; left: 0; width: 0; height: 0;
            border-style: solid;
            border-width: 140px 0 0 140px;
            border-color: transparent transparent transparent var(--blue);
            z-index: 5;
        }
        .ribbon-tl::after {
            content: '';
            position: absolute;
            top: -110px; left: -140px;
            border-style: solid;
            border-width: 110px 0 0 110px;
            border-color: transparent transparent transparent var(--pink);
        }
        .ribbon-tl::before {
            content: '';
            position: absolute;
            top: -80px; left: -140px;
            border-style: solid;
            border-width: 80px 0 0 80px;
            border-color: transparent transparent transparent var(--gold);
        }

        /* Top-right: Blue */
        .ribbon-tr {
            position: absolute; top: 0; right: 0; width: 0; height: 0;
            border-style: solid;
            border-width: 140px 140px 0 0;
            border-color: transparent var(--blue) transparent transparent;
            z-index: 5;
        }
        .ribbon-tr::after {
            content: '';
            position: absolute;
            top: -140px; right: -110px;
            border-style: solid;
            border-width: 110px 110px 0 0;
            border-color: transparent var(--pink) transparent transparent;
        }
        .ribbon-tr::before {
            content: '';
            position: absolute;
            top: -140px; right: -80px;
            border-style: solid;
            border-width: 80px 80px 0 0;
            border-color: transparent var(--gold) transparent transparent;
        }

        /* Bottom-left: Blue */
        .ribbon-bl {
            position: absolute; bottom: 0; left: 0; width: 0; height: 0;
            border-style: solid;
            border-width: 0 0 140px 140px;
            border-color: transparent transparent var(--blue) transparent;
            z-index: 5;
        }
        .ribbon-bl::after {
            content: '';
            position: absolute;
            bottom: -140px; left: -110px;
            border-style: solid;
            border-width: 0 0 110px 110px;
            border-color: transparent transparent var(--pink) transparent;
        }
        .ribbon-bl::before {
            content: '';
            position: absolute;
            bottom: -140px; left: -80px;
            border-style: solid;
            border-width: 0 0 80px 80px;
            border-color: transparent transparent var(--gold) transparent;
        }

        /* Bottom-right: Blue */
        .ribbon-br {
            position: absolute; bottom: 0; right: 0; width: 0; height: 0;
            border-style: solid;
            border-width: 0 140px 140px 0;
            border-color: transparent transparent var(--blue) transparent;
            z-index: 5;
        }
        .ribbon-br::after {
            content: '';
            position: absolute;
            bottom: -140px; right: -110px;
            border-style: solid;
            border-width: 0 110px 110px 0;
            border-color: transparent transparent var(--pink) transparent;
        }
        .ribbon-br::before {
            content: '';
            position: absolute;
            bottom: -140px; right: -80px;
            border-style: solid;
            border-width: 0 80px 80px 0;
            border-color: transparent transparent var(--gold) transparent;
        }

        /* ── Logo Watermark ─────────────────────────── */
        .cert-watermark {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 44%;
            opacity: 0.055;
            pointer-events: none;
            z-index: 1;
            filter: grayscale(100%) contrast(200%);
        }

        /* ── Security Pattern ───────────────────────── */
        .cert-security-pattern {
            position: absolute; inset: 0; z-index: 2; pointer-events: none;
            background-image: radial-gradient(circle, rgba(27,34,153,.07) 1px, transparent 1px);
            background-size: 22px 22px;
        }

        /* ── Inner Gold Border ──────────────────────── */
        .cert-inner-border {
            position: absolute;
            inset: 18px;
            border: 1.5px solid rgba(217,119,6,.35);
            z-index: 3;
            pointer-events: none;
        }
        .cert-inner-border::after {
            content: '';
            position: absolute;
            inset: 5px;
            border: .5px solid rgba(217,119,6,.18);
        }

        /* ── Certificate Content ────────────────────── */
        .cert-content {
            position: relative; z-index: 10;
            width: 100%; height: 100%;
            padding: 44px 72px 36px;
            display: flex; flex-direction: column;
            justify-content: space-between;
        }

        /* Header */
        .cert-header { text-align: center; }
        .cert-academy-label {
            display: inline-flex; align-items: center; gap: 8px;
            margin-bottom: 8px;
        }
        .cert-academy-label .line { flex: 1; height: 1px; width: 48px;
            background: linear-gradient(to right, transparent, #e4306d); }
        .cert-academy-label .line.r { background: linear-gradient(to left, transparent, #e4306d); }
        .cert-academy-label span {
            font-size: 10px; letter-spacing: .42em; font-weight: 800;
            text-transform: uppercase; color: #e4306d;
        }
        .cert-title-main {
            font-family: 'Cinzel', Georgia, serif;
            font-size: clamp(24px, 4.2vw, 42px);
            font-weight: 900; color: #1b2299;
            letter-spacing: .14em; text-transform: uppercase;
            line-height: 1.05;
        }
        .cert-diamond-row {
            display: flex; align-items: center; justify-content: center; gap: 6px; margin: 4px 0;
        }
        .cert-diamond {
            width: 8px; height: 8px; background: var(--gold);
            transform: rotate(45deg); display: inline-block;
        }
        .cert-diamond.sm { width: 5px; height: 5px; background: var(--pink); }
        .cert-presented-to {
            font-family: 'Cinzel', serif;
            font-size: clamp(8px, 1vw, 11px);
            letter-spacing: .26em; text-transform: uppercase;
            color: #64748b; font-weight: 600;
        }

        /* Recipient Name */
        .cert-recipient-wrap { text-align: center; }
        .cert-recipient-name {
            font-family: 'Great Vibes', cursive;
            font-size: clamp(32px, 5.5vw, 58px);
            color: #1e293b; line-height: 1.15;
            display: inline-block;
            border-bottom: 2px solid rgba(217,119,6,.6);
            padding-bottom: 6px;
        }
        .cert-body-text {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: clamp(10px, 1.3vw, 14px);
            color: #475569; text-align: center;
            max-width: 580px; margin: 6px auto 0; line-height: 1.6;
        }
        .cert-course-title {
            font-family: 'Cinzel', serif;
            font-size: clamp(12px, 1.8vw, 19px);
            font-weight: 800; color: #1b2299;
            text-align: center; margin-top: 6px;
            letter-spacing: .05em; line-height: 1.3;
        }

        /* Footer */
        .cert-footer {
            border-top: 1px solid rgba(100,116,139,.2);
            padding-top: 12px;
        }
        .cert-footer-grid {
            display: grid; grid-template-columns: 1fr auto 1fr;
            align-items: end; gap: 16px;
        }

        /* Signatures */
        .cert-sig-block { text-align: center; }
        .cert-sig-name {
            font-family: 'Great Vibes', cursive;
            font-size: clamp(16px, 2.2vw, 24px);
            color: #1e293b; height: 36px;
            display: flex; align-items: center; justify-content: center;
        }
        .cert-sig-line {
            border-top: 1px solid rgba(100,116,139,.55);
            padding-top: 6px; margin-top: 2px;
        }
        .cert-sig-label {
            font-family: 'Cinzel', serif;
            font-size: clamp(7px, .85vw, 10px);
            font-weight: 800; letter-spacing: .2em; text-transform: uppercase; color: #1e293b;
        }
        .cert-sig-role {
            font-size: clamp(6px, .75vw, 9px);
            letter-spacing: .12em; text-transform: uppercase; color: #94a3b8; margin-top: 1px;
        }

        /* Center Medallion */
        .cert-medallion-wrap {
            display: flex; flex-direction: column; align-items: center; justify-content: flex-end;
            padding-bottom: 4px;
        }
        .cert-medallion {
            position: relative; width: clamp(72px, 9vw, 100px); height: clamp(72px, 9vw, 100px);
        }
        .cert-medal-ribbon-l {
            position: absolute; bottom: -14px; left: 6px;
            width: 14px; height: 22px;
            background: var(--blue);
            clip-path: polygon(0 0, 100% 0, 100% 80%, 50% 100%, 0 80%);
        }
        .cert-medal-ribbon-r {
            position: absolute; bottom: -14px; right: 6px;
            width: 14px; height: 22px;
            background: var(--pink);
            clip-path: polygon(0 0, 100% 0, 100% 80%, 50% 100%, 0 80%);
        }
        .cert-medal-body {
            width: 100%; height: 100%; border-radius: 50%;
            background: conic-gradient(#bf953f 0deg, #fcf6ba 60deg, #b38728 120deg, #fbf5b7 180deg, #aa771c 240deg, #fcf6ba 300deg, #bf953f 360deg);
            border: 3px solid rgba(180,130,0,.6);
            box-shadow: 0 4px 20px rgba(0,0,0,.25), inset 0 1px 4px rgba(255,255,255,.3);
            display: flex; align-items: center; justify-content: center;
        }
        .cert-medal-inner {
            width: 82%; height: 82%; border-radius: 50%;
            border: 1px dashed rgba(120,80,0,.4);
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            text-align: center; gap: 1px; padding: 4px;
        }
        .cert-medal-icon { font-size: clamp(14px, 2vw, 22px); color: #78350f; }
        .cert-medal-text-top {
            font-family: 'Cinzel', serif;
            font-size: clamp(5px, .65vw, 8px);
            font-weight: 900; text-transform: uppercase;
            letter-spacing: .15em; color: #78350f; line-height: 1.1;
        }
        .cert-medal-text-bot {
            font-size: clamp(4px, .55vw, 7px);
            text-transform: uppercase; font-weight: 700;
            letter-spacing: .1em; color: #92400e;
        }

        /* Right block: date + qr */
        .cert-right-block { text-align: center; }
        .cert-date-val {
            font-family: 'JetBrains Mono', monospace;
            font-size: clamp(9px, 1.1vw, 13px); font-weight: 700; color: #1e293b;
            height: 36px; display: flex; align-items: center; justify-content: center;
        }

        /* QR Code container */
        .cert-qr-wrap {
            width: clamp(48px, 6vw, 64px);
            height: clamp(48px, 6vw, 64px);
            margin: 0 auto 6px;
            border: 1.5px solid rgba(27,34,153,.2);
            border-radius: 6px; overflow: hidden;
            background: #fff;
        }
        .cert-qr-wrap img { width: 100%; height: 100%; display: block; }

        /* Serial */
        .cert-serial {
            font-family: 'JetBrains Mono', monospace;
            font-size: clamp(6px, .75vw, 9px);
            font-weight: 700; color: #1b2299;
            background: rgba(27,34,153,.06);
            padding: 2px 6px; border-radius: 4px;
            display: inline-block; margin-top: 4px; letter-spacing: .05em;
        }

        /* Verification micro strip */
        .cert-verify-strip {
            margin-top: 10px; padding-top: 8px;
            border-top: 1px dashed rgba(100,116,139,.2);
            display: flex; align-items: center; justify-content: space-between;
            font-family: 'JetBrains Mono', monospace;
            font-size: clamp(5px, .65vw, 7.5px);
            text-transform: uppercase; letter-spacing: .12em; color: #94a3b8;
        }

        /* ── Print Rules ────────────────────────────── */
        @media print {
            @page { size: A4 landscape; margin: 0; }
            body {
                background: #fff !important; margin: 0 !important; padding: 0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                display: block;
            }
            .no-print { display: none !important; }
            .cert-shell {
                box-shadow: none !important; max-width: 100vw !important;
                width: 100vw !important; height: 100vh !important;
                aspect-ratio: unset !important;
            }
            .cert-content { padding: 36px 64px 30px; }
        }

        /* ── Responsive Scale ───────────────────────── */
        @media (max-width: 680px) {
            .cert-content { padding: 22px 32px 18px; }
            .ribbon-tl, .ribbon-tr, .ribbon-bl, .ribbon-br {
                border-width: 90px 0 0 90px;
            }
            .ribbon-tl::after { border-width: 70px 0 0 70px; }
            .ribbon-tl::before { border-width: 50px 0 0 50px; }
            .ribbon-tr { border-width: 90px 90px 0 0; }
            .ribbon-tr::after { border-width: 70px 70px 0 0; }
            .ribbon-tr::before { border-width: 50px 50px 0 0; }
            .ribbon-bl { border-width: 0 0 90px 90px; }
            .ribbon-bl::after { border-width: 0 0 70px 70px; }
            .ribbon-bl::before { border-width: 0 0 50px 50px; }
            .ribbon-br { border-width: 0 90px 90px 0; }
            .ribbon-br::after { border-width: 0 70px 70px 0; }
            .ribbon-br::before { border-width: 0 50px 50px 0; }
        }
    </style>
</head>
<body>

    {{-- ── Action Bar ─────────────────────────────── --}}
    <div class="action-bar no-print">
        <a href="{{ route('student.certificates') }}" class="btn-back">
            <i class="fas fa-arrow-left" style="color:#f59e0b"></i> Back to My Certificates
        </a>
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
            @php
                $composite = $enrollment->id . $enrollment->course_id . $enrollment->user_id;
                $verifyUrl = route('certificate.verify', ['code' => 'LNR-CERT-' . $composite]);
            @endphp
            <a href="{{ $verifyUrl }}" target="_blank" class="btn-back" style="font-size:11px">
                <i class="fas fa-shield-halved" style="color:#10b981"></i> Verify Online
            </a>
            <button onclick="window.print()" class="btn-print">
                <i class="fas fa-print"></i> Print / Download PDF
            </button>
        </div>
    </div>

    {{-- ── Certificate ─────────────────────────────── --}}
    <div class="cert-shell">

        {{-- Geometric Corner Ribbons --}}
        <div class="ribbon-tl"></div>
        <div class="ribbon-tr"></div>
        <div class="ribbon-bl"></div>
        <div class="ribbon-br"></div>

        {{-- Logo Watermark --}}
        <img src="{{ asset('logo-only.png') }}" alt="" class="cert-watermark" aria-hidden="true">

        {{-- Security Guilloche Background --}}
        <div class="cert-security-pattern"></div>

        {{-- Inner Gold Frame --}}
        <div class="cert-inner-border"></div>

        {{-- Certificate Body --}}
        <div class="cert-content">

            {{-- HEADER --}}
            <div class="cert-header">
                <div class="cert-academy-label">
                    <div class="line"></div>
                    <span>Learnerium Academy</span>
                    <div class="line r"></div>
                </div>
                <h1 class="cert-title-main">Certificate of Achievement</h1>
                <div class="cert-diamond-row">
                    <div class="cert-diamond sm"></div>
                    <div class="cert-diamond"></div>
                    <div class="cert-diamond sm"></div>
                </div>
                <p class="cert-presented-to">This certificate is proudly presented to</p>
            </div>

            {{-- RECIPIENT --}}
            <div class="cert-recipient-wrap">
                <h2 class="cert-recipient-name">{{ $user->name }}</h2>
                <p class="cert-body-text">
                    for successfully fulfilling all curriculum requirements, continuous assessments,
                    task gates, and examinations for the accredited course:
                </p>
                <h3 class="cert-course-title">"{{ $course->title }}"</h3>
            </div>

            {{-- FOOTER --}}
            <div class="cert-footer">
                <div class="cert-footer-grid">

                    {{-- Instructor Signature --}}
                    <div class="cert-sig-block">
                        <div class="cert-sig-name">{{ $course->instructor?->name ?? 'Course Instructor' }}</div>
                        <div class="cert-sig-line">
                            <div class="cert-sig-label">{{ $course->instructor?->name ?? 'Instructor' }}</div>
                            <div class="cert-sig-role">Lead Instructor</div>
                        </div>
                    </div>

                    {{-- Center Gold Medallion --}}
                    <div class="cert-medallion-wrap">
                        <div class="cert-medallion">
                            <div class="cert-medal-ribbon-l"></div>
                            <div class="cert-medal-ribbon-r"></div>
                            <div class="cert-medal-body">
                                <div class="cert-medal-inner">
                                    <i class="fas fa-award cert-medal-icon"></i>
                                    <div class="cert-medal-text-top">Official</div>
                                    <div class="cert-medal-text-bot">Seal</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Date + QR + Serial --}}
                    <div class="cert-right-block">
                        @php
                            $composite = $enrollment->id . $enrollment->course_id . $enrollment->user_id;
                            $serial    = 'LNR-CERT-' . $composite;
                            $verifyUrl = url('/verify/certificate/LNR-CERT-' . $composite);
                            $qrApiUrl  = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($verifyUrl) . '&size=120x120&ecc=H&margin=3&color=1b2299';
                            $issueDate = $enrollment->updated_at ? $enrollment->updated_at->format('d F Y') : date('d F Y');
                        @endphp
                        <div class="cert-qr-wrap" title="Scan to verify this certificate">
                            <img src="{{ $qrApiUrl }}" alt="Verification QR Code">
                        </div>
                        <div class="cert-sig-line">
                            <div class="cert-sig-label">{{ $issueDate }}</div>
                            <div class="cert-sig-role">Date of Issue</div>
                        </div>
                        <div class="cert-serial">{{ $serial }}</div>
                    </div>

                </div>

                {{-- Micro Verification Strip --}}
                <div class="cert-verify-strip">
                    <span>Verified Academic Credential</span>
                    <span>learnerium.jlm.com.ng &bull; {{ $serial }}</span>
                    <span>Accredited E-Learning Platform</span>
                </div>
            </div>

        </div>{{-- /cert-content --}}
    </div>{{-- /cert-shell --}}

    <div class="verify-link-bar no-print">
        <i class="fas fa-qrcode" style="color:#475569"></i>
        <span>Scan QR or visit:</span>
        <a href="{{ $verifyUrl }}" target="_blank">{{ $verifyUrl }}</a>
    </div>

</body>
</html>
