@extends('emails.layout')

@section('content')

<p class="greeting">Reset Your Password 🔐</p>

<p class="body-text">
    Hello <strong>{{ $userName ?? 'there' }}</strong>,
</p>

<p class="body-text">
    We received a request to reset the password for your Learnerium account.
    Click the button below to set a new password. This link is valid for <strong>60 minutes</strong>.
</p>

<div class="btn-wrap">
    <a href="{{ $resetUrl }}" class="btn">
        🔑 &nbsp;Reset My Password
    </a>
</div>

<div class="info-box">
    <strong>🛡️ Security notice:</strong> If you did not request a password reset, please ignore this email.
    Your password will not be changed unless you click the link above and complete the process.
</div>

<p class="body-text">
    Or copy and paste this URL into your browser:
</p>
<p style="font-size:12px;color:#6b7280;word-break:break-all;background:#f8fafc;padding:10px 14px;border-radius:8px;">
    {{ $resetUrl }}
</p>

@endsection
