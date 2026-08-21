@extends('emails.layout')

@section('content')

<h2 style="font-size:22px;font-weight:800;color:#1b2299;margin-top:0;margin-bottom:16px;">Reset Your Password 🔐</h2>

<p style="font-size:15px;color:#374151;line-height:1.75;margin-bottom:16px;">
    Hello <strong>{{ $userName ?? 'there' }}</strong>,
</p>

<p style="font-size:15px;color:#374151;line-height:1.75;margin-bottom:20px;">
    We received a request to reset the password for your Learnerium account.
    Click the button below to set a new password. This link is valid for <strong>60 minutes</strong>.
</p>

<div style="text-align:center;margin:32px 0;">
    <a href="{{ $resetUrl }}" style="display:inline-block;background:linear-gradient(135deg,#1b2299 0%,#e4306d 100%);color:#ffffff;text-decoration:none;padding:14px 36px;border-radius:50px;font-size:15px;font-weight:700;letter-spacing:0.3px;box-shadow:0 4px 16px rgba(27,34,153,0.35);">
        🔑 &nbsp;Reset My Password
    </a>
</div>

<div style="background:#f8faff;border-left:4px solid #1b2299;border-radius:0 8px 8px 0;padding:14px 18px;font-size:13px;color:#4b5563;margin:24px 0;line-height:1.6;">
    <strong style="color:#1b2299;">🛡️ Security notice:</strong> If you did not request a password reset, please ignore this email. Your password will not be changed unless you click the link above.
</div>

<p style="font-size:13px;color:#6b7280;margin-bottom:8px;">
    Or copy and paste this link into your browser:
</p>
<p style="font-size:12px;color:#4b5563;word-break:break-all;background:#f1f5f9;padding:10px 14px;border-radius:8px;font-family:monospace;">
    {{ $resetUrl }}
</p>

@endsection
