<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? $title ?? 'Learnerium' }}</title>
</head>
<body style="margin:0;padding:24px;background:#eef2f7;font-family:Arial,Helvetica,sans-serif;color:#111827;">
    <div style="max-width:640px;margin:0 auto;background:#ffffff;border-radius:18px;overflow:hidden;box-shadow:0 12px 30px rgba(15,23,42,0.08);">

        {{-- JLM GRADIENT HEADER WITH LOGO --}}
        <div style="background:linear-gradient(135deg,#1b2299 0%,#7b1fa2 50%,#e4306d 100%);padding:36px 24px 32px;text-align:center;color:#ffffff;">
            {{-- App Logo --}}
            <div style="margin-bottom:16px;">
                <img src="{{ config('app.url') }}/logo-only.png"
                     alt="Learnerium"
                     width="72" height="72"
                     style="display:inline-block;width:72px;height:72px;object-fit:contain;background:rgba(255,255,255,0.15);border-radius:50%;padding:10px;border:2px solid rgba(255,255,255,0.35);"
                >
            </div>
            <div style="display:inline-block;margin:0 auto 14px;padding:6px 20px;border-radius:999px;background:rgba(255,255,255,0.18);border:1px solid rgba(255,255,255,0.30);font-size:12px;letter-spacing:0.22em;font-weight:800;color:#ffffff;">
                LEARNERIUM
            </div>
            <div style="font-size:24px;line-height:1.25;font-weight:800;margin-bottom:8px;letter-spacing:-0.5px;margin-top:10px;">
                {{ $title ?? $subject ?? 'Learnerium' }}
            </div>
            <div style="font-size:13px;line-height:1.6;opacity:0.92;font-weight:500;">
                Bringing Your Creative Vision to Life — Powered by JLM
            </div>
        </div>

        {{-- EMAIL BODY --}}
        <div style="padding:36px 32px;line-height:1.8;font-size:15px;color:#111827;">
            @yield('content')
        </div>

        {{-- JLM BRANDED FOOTER --}}
        <div style="background:#f7f8fb;padding:30px 24px;border-top:1px solid #e5e7eb;">
            <div style="text-align:center;color:#4b5563;font-size:14px;line-height:1.9;">
                <div style="font-size:20px;font-weight:800;color:#111827;margin-bottom:4px;">Learnerium</div>
                <div style="font-size:12px;color:#9ca3af;margin-bottom:12px;">Bringing Your Creative Vision to Life · Powered by JLM</div>

                <div>Email: <a href="mailto:learnerium@jlm.com.ng" style="color:#1b2299;text-decoration:none;font-weight:600;">learnerium@jlm.com.ng</a></div>
                <div>Phone: <span style="color:#111827;font-weight:500;">+234 815 091 7741</span></div>
                <div>Website: <a href="{{ url('/') }}" style="color:#1b2299;text-decoration:none;font-weight:600;">learnerium.jlm.com.ng</a></div>
            </div>

            {{-- Social Media Links --}}
            <div style="text-align:center;margin-top:18px;padding-top:16px;border-top:1px solid #e5e7eb;">
                <a href="https://instagram.com/jlukamedia" target="_blank" style="display:inline-block;margin:4px 8px;color:#1b2299;text-decoration:none;font-weight:700;font-size:12px;">Instagram</a>
                <a href="https://facebook.com/jlukamedia" target="_blank" style="display:inline-block;margin:4px 8px;color:#1b2299;text-decoration:none;font-weight:700;font-size:12px;">Facebook</a>
                <a href="https://x.com/jlukamedia" target="_blank" style="display:inline-block;margin:4px 8px;color:#1b2299;text-decoration:none;font-weight:700;font-size:12px;">X (Twitter)</a>
                <a href="https://linkedin.com/in/jlukamedia" target="_blank" style="display:inline-block;margin:4px 8px;color:#1b2299;text-decoration:none;font-weight:700;font-size:12px;">LinkedIn</a>
                <a href="https://wa.me/2348150917741" target="_blank" style="display:inline-block;margin:4px 8px;color:#1b2299;text-decoration:none;font-weight:700;font-size:12px;">WhatsApp</a>
            </div>

            <div style="text-align:center;color:#9ca3af;font-size:11px;margin-top:18px;line-height:1.5;">
                &copy; {{ date('Y') }} Learnerium &bull; Powered by JLM Creative Media.<br>
                This email was sent from <strong>learnerium@jlm.com.ng</strong>.
            </div>
        </div>

    </div>
</body>
</html>
