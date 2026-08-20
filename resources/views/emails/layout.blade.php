<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $subject ?? 'Learnerium' }}</title>
<style>
    /* Reset */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { background-color: #f0f2f8; font-family: 'Segoe UI', Arial, sans-serif; }
    .wrapper { width: 100%; background-color: #f0f2f8; padding: 40px 16px; }
    .container { max-width: 600px; margin: 0 auto; }

    /* Header */
    .header {
        background: linear-gradient(135deg, #0f1566 0%, #1b2299 50%, #2a35c9 100%);
        border-radius: 16px 16px 0 0;
        padding: 36px 40px 28px;
        text-align: center;
    }
    .logo-text {
        font-size: 30px;
        font-weight: 900;
        color: #ffffff;
        letter-spacing: -0.5px;
    }
    .logo-dot {
        color: #f7de7a;
    }
    .header-tagline {
        color: rgba(255,255,255,0.65);
        font-size: 12px;
        margin-top: 4px;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    /* Gold accent bar */
    .accent-bar {
        height: 4px;
        background: linear-gradient(90deg, #f7de7a, #fbbf24, #f7de7a);
    }

    /* Body */
    .body {
        background: #ffffff;
        padding: 40px 44px;
    }

    /* Greeting */
    .greeting {
        font-size: 22px;
        font-weight: 800;
        color: #1b2299;
        margin-bottom: 14px;
    }
    .body-text {
        font-size: 15px;
        color: #374151;
        line-height: 1.75;
        margin-bottom: 18px;
    }

    /* CTA Button */
    .btn-wrap { text-align: center; margin: 32px 0; }
    .btn {
        display: inline-block;
        background: linear-gradient(135deg, #1b2299, #2a35c9);
        color: #ffffff !important;
        text-decoration: none;
        padding: 14px 36px;
        border-radius: 50px;
        font-size: 15px;
        font-weight: 700;
        letter-spacing: 0.3px;
        box-shadow: 0 4px 16px rgba(27, 34, 153, 0.35);
    }
    .btn:hover { background: #141a75; }

    /* Info box */
    .info-box {
        background: #f8faff;
        border-left: 4px solid #1b2299;
        border-radius: 0 8px 8px 0;
        padding: 14px 18px;
        font-size: 13px;
        color: #4b5563;
        margin: 24px 0;
        line-height: 1.6;
    }
    .info-box strong { color: #1b2299; }

    /* Divider */
    .divider {
        border: none;
        border-top: 1px solid #e5e7eb;
        margin: 28px 0;
    }

    /* Footer */
    .footer {
        background: #111827;
        border-radius: 0 0 16px 16px;
        padding: 28px 40px;
        text-align: center;
    }
    .footer-logo {
        font-size: 18px;
        font-weight: 900;
        color: #ffffff;
        margin-bottom: 10px;
    }
    .footer-logo span { color: #f7de7a; }
    .footer-links {
        margin: 10px 0;
        font-size: 12px;
    }
    .footer-links a {
        color: #9ca3af;
        text-decoration: none;
        margin: 0 8px;
    }
    .footer-links a:hover { color: #f7de7a; }
    .footer-copy {
        font-size: 11px;
        color: #4b5563;
        margin-top: 12px;
    }
    .footer-warn {
        background: #1f2937;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 11px;
        color: #6b7280;
        margin-top: 14px;
        line-height: 1.5;
    }
</style>
</head>
<body>
<div class="wrapper">
<div class="container">

    <!-- HEADER -->
    <div class="header">
        <div class="logo-text">Learneri<span class="logo-dot">u</span>m</div>
        <div class="header-tagline">Africa's Premier Learning Platform</div>
    </div>
    <div class="accent-bar"></div>

    <!-- BODY -->
    <div class="body">
        @yield('content')

        <hr class="divider">

        <p style="font-size:12px;color:#9ca3af;text-align:center;">
            If you did not create an account on Learnerium, no action is needed. You can safely ignore this email.
        </p>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <div class="footer-logo">Learneri<span>u</span>m</div>
        <div class="footer-links">
            <a href="{{ url('/') }}">Home</a>
            <a href="{{ url('/courses') }}">Courses</a>
            <a href="{{ url('/contact') }}">Support</a>
            <a href="{{ url('/about') }}">About</a>
        </div>
        <div class="footer-copy">
            &copy; {{ date('Y') }} Learnerium · learnerium.jlm.com.ng<br>
            Empowering African learners, one course at a time.
        </div>
        <div class="footer-warn">
            This email was sent from <strong style="color:#9ca3af">learnerium@jlm.com.ng</strong>.
            Please do not reply directly to this email.
        </div>
    </div>

</div>
</div>
</body>
</html>
