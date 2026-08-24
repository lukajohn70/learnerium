@extends('emails.layout')

@php $title = "⏰ Don't Miss Out!"; @endphp

@section('content')

{{-- Reminder Badge --}}
<div style="text-align:center;margin-bottom:20px;">
    <span style="display:inline-block;background:#fef3c7;color:#92400e;font-size:11px;font-weight:700;padding:4px 14px;border-radius:20px;text-transform:uppercase;letter-spacing:0.5px;">
        Payment Reminder
    </span>
</div>

<p style="font-size:15px;font-weight:700;color:#1b2299;margin:0 0 12px;">
    Hi {{ $student->name }},
</p>

<p style="font-size:14px;color:#374151;line-height:1.8;margin:0 0 24px;">
    We noticed you started enrolling in <strong>{{ $course->title }}</strong> but haven't completed your payment yet.
    Your spot is waiting! Complete your enrollment now to unlock all course materials, lessons, and your certificate.
</p>

{{-- Course Card --}}
<div style="background:#f8f9ff;border:1.5px solid #e0e3ff;border-radius:14px;padding:18px 20px;margin:0 0 28px;display:flex;gap:16px;align-items:flex-start;">
    <div style="flex:1;">
        <div style="font-size:15px;font-weight:800;color:#1b2299;margin-bottom:5px;">{{ $course->title }}</div>
        <div style="font-size:12px;color:#6b7280;margin-bottom:8px;">
            by {{ $course->instructor->name ?? 'Learnerium Instructor' }} &bull; {{ $course->level }}
        </div>
        <div style="font-size:20px;font-weight:900;color:#e4306d;">
            ₦{{ number_format($enrollment->amount_paid ?? $course->price, 2) }}
        </div>
    </div>
</div>

{{-- CTA Button --}}
<div style="text-align:center;margin:28px 0;">
    <a href="{{ $checkoutUrl }}" style="display:inline-block;background:linear-gradient(135deg,#e4306d 0%,#1b2299 100%);color:#ffffff;text-decoration:none;padding:15px 36px;border-radius:50px;font-size:15px;font-weight:800;letter-spacing:0.3px;box-shadow:0 4px 18px rgba(228,48,109,0.30);">
        ✅ Complete My Enrollment Now
    </a>
</div>

<p style="font-size:13px;color:#9ca3af;text-align:center;line-height:1.6;margin:0 0 24px;">
    This link takes you directly to the checkout page.<br>
    If you have any issues, reply to this email and we'll assist you promptly.
</p>

<div style="background:#f9fafb;border-radius:10px;padding:14px 18px;font-size:12px;color:#9ca3af;text-align:center;line-height:1.6;">
    If you no longer wish to enroll, simply ignore this email. No further action is needed.
</div>

@endsection
