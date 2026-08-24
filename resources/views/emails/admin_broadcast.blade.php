@extends('emails.layout')

@section('content')

<p style="font-size:15px;font-weight:700;color:#1b2299;margin:0 0 12px;">
    Hello {{ $recipient->name ?? 'there' }},
</p>

<p style="font-size:14px;color:#374151;line-height:1.8;margin:0 0 22px;">
    You have a new message from the <strong>Learnerium Team</strong>:
</p>

{{-- Message Card --}}
<div style="background:#f8f9ff;border:1.5px solid #e0e3ff;border-left:4px solid #1b2299;border-radius:0 12px 12px 0;padding:20px 22px;margin:0 0 28px;font-size:14px;color:#1f2937;line-height:1.8;">
    {!! nl2br(e($content)) !!}
</div>

<p style="font-size:13px;color:#6b7280;line-height:1.6;margin:0 0 24px;">
    You can reply directly to this email if you have any questions or feedback. Our team will respond promptly.
</p>

<div style="text-align:center;margin:32px 0;">
    <a href="{{ url('/') }}" style="display:inline-block;background:linear-gradient(135deg,#1b2299 0%,#e4306d 100%);color:#ffffff;text-decoration:none;padding:14px 36px;border-radius:50px;font-size:14px;font-weight:700;letter-spacing:0.3px;box-shadow:0 4px 16px rgba(27,34,153,0.30);">
        Visit Learnerium &rarr;
    </a>
</div>

<p style="font-size:12px;color:#9ca3af;border-top:1px solid #f1f5f9;padding-top:18px;margin-top:8px;line-height:1.6;">
    Best regards,<br>
    <strong style="color:#1b2299;">The Learnerium Team</strong>
</p>

@endsection
