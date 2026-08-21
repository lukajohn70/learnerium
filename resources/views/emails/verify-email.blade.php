@extends('emails.layout')

@section('content')

<h2 style="font-size:22px;font-weight:800;color:#1b2299;margin-top:0;margin-bottom:16px;">Verify Your Email Address 🎓</h2>

<p style="font-size:15px;color:#374151;line-height:1.75;margin-bottom:16px;">
    Hello <strong>{{ $userName ?? 'there' }}</strong>,
</p>

<p style="font-size:15px;color:#374151;line-height:1.75;margin-bottom:20px;">
    Welcome to <strong>Learnerium</strong>! We're excited to have you join Africa's premier learning platform — powered by JLM.
    To get started, please verify your email address by clicking the button below.
</p>

<div style="text-align:center;margin:32px 0;">
    <a href="{{ $verificationUrl }}" style="display:inline-block;background:linear-gradient(135deg,#1b2299 0%,#e4306d 100%);color:#ffffff;text-decoration:none;padding:14px 36px;border-radius:50px;font-size:15px;font-weight:700;letter-spacing:0.3px;box-shadow:0 4px 16px rgba(27,34,153,0.35);">
        ✅ &nbsp;Verify My Email Address
    </a>
</div>

<div style="background:#f8faff;border-left:4px solid #1b2299;border-radius:0 8px 8px 0;padding:14px 18px;font-size:13px;color:#4b5563;margin:24px 0;line-height:1.6;">
    <strong style="color:#1b2299;">⏰ Notice:</strong> This verification link is valid for 60 minutes. If it expires, log in and request a new link from your dashboard.
</div>

<p style="font-size:13px;color:#6b7280;margin-bottom:8px;">
    Or copy and paste this link into your browser:
</p>
<p style="font-size:12px;color:#4b5563;word-break:break-all;background:#f1f5f9;padding:10px 14px;border-radius:8px;font-family:monospace;">
    {{ $verificationUrl }}
</p>

@endsection
