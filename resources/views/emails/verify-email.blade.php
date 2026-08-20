@extends('emails.layout')

@section('content')

<p class="greeting">Verify Your Email Address 🎓</p>

<p class="body-text">
    Hello <strong>{{ $userName ?? 'there' }}</strong>,
</p>

<p class="body-text">
    Welcome to <strong>Learnerium</strong>! We're excited to have you join Africa's premier learning platform.
    To get started, please verify your email address by clicking the button below.
</p>

<div class="btn-wrap">
    <a href="{{ $verificationUrl }}" class="btn">
        ✅ &nbsp;Verify My Email Address
    </a>
</div>

<div class="info-box">
    <strong>⏰ This link expires in 60 minutes.</strong><br>
    If it expires, simply log in and request a new verification link from your dashboard.
</div>

<p class="body-text">
    Or copy and paste this URL into your browser:
</p>
<p style="font-size:12px;color:#6b7280;word-break:break-all;background:#f8fafc;padding:10px 14px;border-radius:8px;">
    {{ $verificationUrl }}
</p>

@endsection
