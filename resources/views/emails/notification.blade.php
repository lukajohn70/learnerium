@extends('emails.layout')

@php $title = $title ?? 'Notification'; @endphp

@section('content')

<h2 style="font-size:20px;font-weight:800;color:#1b2299;margin:0 0 16px;line-height:1.3;">
    {{ $title }}
</h2>

<p style="font-size:14px;color:#374151;line-height:1.8;margin:0 0 22px;">
    {{ $bodyMessage }}
</p>

@if(!empty($actionUrl))
<div style="text-align:center;margin:32px 0;">
    <a href="{{ $actionUrl }}" target="_blank" style="display:inline-block;background:linear-gradient(135deg,#1b2299 0%,#e4306d 100%);color:#ffffff;text-decoration:none;padding:14px 36px;border-radius:50px;font-size:14px;font-weight:700;letter-spacing:0.3px;box-shadow:0 4px 16px rgba(27,34,153,0.30);">
        View in Learnerium &rarr;
    </a>
</div>
@endif

<div style="background:#f8faff;border-left:4px solid #e0e3ff;border-radius:0 8px 8px 0;padding:14px 18px;font-size:12px;color:#6b7280;margin:24px 0;line-height:1.6;">
    You are receiving this notification because of your account activity on
    <a href="{{ url('/') }}" style="color:#1b2299;text-decoration:none;font-weight:600;">Learnerium</a>.
    Manage your preferences in your
    <a href="{{ url('/settings/notifications') }}" style="color:#1b2299;text-decoration:none;font-weight:600;">Notification Settings</a>.
</div>

@endsection
