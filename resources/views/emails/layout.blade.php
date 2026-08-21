<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Learnerium' }}</title>
</head>
<body style="margin:0;padding:24px;background:#eef2f7;font-family:'Segoe UI',Arial,sans-serif;color:#111827;">
    <div style="max-width:640px;margin:0 auto;background:#ffffff;border-radius:18px;overflow:hidden;box-shadow:0 12px 30px rgba(15,23,42,0.08);">
        
        {{-- JLM GRADIENT HEADER --}}
        <div style="background:linear-gradient(135deg,#1b2299 0%,#e4306d 100%);padding:36px 24px;text-align:center;color:#ffffff;">
            <div style="display:inline-block;margin:0 auto 12px;padding:8px 20px;border-radius:999px;background:rgba(255,255,255,0.16);border:1px solid rgba(255,255,255,0.28);font-size:14px;letter-spacing:0.18em;font-weight:800;color:#ffffff;">
                LEARNERIUM
            </div>
            <div style="font-size:28px;line-height:1.2;font-weight:800;margin-bottom:8px;letter-spacing:-0.5px;">
                {{ $title ?? $subject ?? 'Learnerium' }}
            </div>
            <div style="font-size:14px;line-height:1.6;opacity:0.95;font-weight:500;">
                Bringing Your Creative Vision to Life — Powered by JLM
            </div>
        </div>

        {{-- EMAIL BODY --}}
        <div style="padding:36px 28px;line-height:1.75;font-size:15px;color:#111827;">
            @yield('content')
        </div>

        {{-- JLM BRANDED FOOTER --}}
        <div style="background:#f7f8fb;padding:28px 24px;border-top:1px solid #e5e7eb;">
            <div style="text-align:center;color:#4b5563;font-size:14px;line-height:1.8;">
                <div style="font-size:20px;font-weight:800;color:#111827;margin-bottom:4px;">Learnerium</div>
                <div style="margin-bottom:14px;font-size:13px;color:#6b7280;">Bringing Your Creative Vision to Life · Powered by JLM</div>
                
                <div>Email: <a href="mailto:support@jlm.com.ng" style="color:#1b2299;text-decoration:none;font-weight:600;">support@jlm.com.ng</a></div>
                <div>Phone: <span style="color:#111827;font-weight:500;">+234 815 091 7741</span></div>
                <div>Website: <a href="{{ url('/') }}" style="color:#1b2299;text-decoration:none;font-weight:600;">learnerium.jlm.com.ng</a></div>
            </div>

            {{-- Social Media Links --}}
            <div style="text-align:center;margin-top:18px;">
                <a href="https://instagram.com/jlukamedia" target="_blank" style="display:inline-block;margin:4px 8px;color:#1b2299;text-decoration:none;font-weight:600;font-size:13px;">Instagram</a>
                <a href="https://facebook.com/jlukamedia" target="_blank" style="display:inline-block;margin:4px 8px;color:#1b2299;text-decoration:none;font-weight:600;font-size:13px;">Facebook</a>
                <a href="https://x.com/jlukamedia" target="_blank" style="display:inline-block;margin:4px 8px;color:#1b2299;text-decoration:none;font-weight:600;font-size:13px;">X (Twitter)</a>
                <a href="https://linkedin.com/in/jlukamedia" target="_blank" style="display:inline-block;margin:4px 8px;color:#1b2299;text-decoration:none;font-weight:600;font-size:13px;">LinkedIn</a>
                <a href="https://wa.me/2348150917741" target="_blank" style="display:inline-block;margin:4px 8px;color:#1b2299;text-decoration:none;font-weight:600;font-size:13px;">WhatsApp</a>
            </div>

            <div style="text-align:center;color:#9ca3af;font-size:12px;margin-top:20px;line-height:1.5;">
                &copy; {{ date('Y') }} Learnerium · Powered by JLM Creative Media.<br>
                This email was sent from <strong>support@jlm.com.ng</strong>.
            </div>
        </div>

    </div>
</body>
</html>
