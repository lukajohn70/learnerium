@extends('emails.layout')

@php $title = "Re: {$originalSubject}"; @endphp

@section('content')

<p style="font-size:15px;font-weight:700;color:#1b2299;margin:0 0 12px;">
    Hello {{ $recipientName }},
</p>

<p style="font-size:14px;color:#374151;line-height:1.8;margin:0 0 20px;">
    Thank you for reaching out to us. We have responded to your message regarding
    <strong style="color:#1b2299;">"{{ $originalSubject }}"</strong>:
</p>

{{-- Admin Reply Content --}}
<div style="background:#f0f4ff;border-left:4px solid #1b2299;border-radius:0 12px 12px 0;padding:20px 22px;margin:0 0 24px;font-size:14px;color:#1f2937;line-height:1.8;">
    {!! nl2br(e($replyText)) !!}
</div>

{{-- Original Message Quote --}}
<div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:12px;padding:16px 20px;margin:0 0 28px;">
    <div style="font-size:10px;letter-spacing:0.08em;text-transform:uppercase;color:#9ca3af;font-weight:700;margin-bottom:8px;">Your Original Message:</div>
    <p style="margin:0;font-size:13px;color:#4b5563;font-style:italic;line-height:1.7;">
        {!! nl2br(e($originalMessage)) !!}
    </p>
</div>

<div style="text-align:center;margin:28px 0;">
    <a href="{{ url('/') }}" style="display:inline-block;background:linear-gradient(135deg,#1b2299 0%,#e4306d 100%);color:#ffffff;text-decoration:none;padding:14px 36px;border-radius:50px;font-size:14px;font-weight:700;letter-spacing:0.3px;box-shadow:0 4px 16px rgba(27,34,153,0.30);">
        Go to Learnerium &rarr;
    </a>
</div>

<p style="font-size:12px;color:#9ca3af;border-top:1px solid #f1f5f9;padding-top:18px;margin-top:8px;line-height:1.6;">
    You can reply directly to this email to continue this conversation.<br>
    Best regards,<br>
    <strong style="color:#1b2299;">{{ $adminName }} &bull; Learnerium Support Team</strong>
</p>

@endsection
