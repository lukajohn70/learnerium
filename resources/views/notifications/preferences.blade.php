@extends('layouts.app')

@section('title', 'Notification Preferences — Learnerium')

@section('content')
<div class="min-h-screen bg-gray-50 py-10 px-4">
<div class="max-w-2xl mx-auto">

    <div class="mb-7 flex items-center gap-3">
        <a href="{{ route('settings') }}" class="text-gray-400 hover:text-primary-jlm transition"><i class="fas fa-arrow-left"></i></a>
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Notification Settings</h1>
            <p class="text-sm text-gray-500 mt-0.5">Choose which notifications you'd like to receive.</p>
        </div>
    </div>

    @if(session('status'))
        <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="fas fa-check-circle text-emerald-500"></i>{{ session('status') }}
        </div>
    @endif

    <form action="{{ route('notifications.save') }}" method="POST" class="space-y-5">
        @csrf

        {{-- EMAIL NOTIFICATIONS --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-envelope text-blue-600 text-sm"></i>
                </div>
                <div>
                    <h2 class="font-bold text-gray-900 text-sm">Email Notifications</h2>
                    <p class="text-xs text-gray-400">Sent to {{ Auth::user()->email }}</p>
                </div>
            </div>
            <div class="divide-y divide-gray-50">
                @php
                $emailPrefs = [
                    ['email_enrollment',     'Enrollment Confirmations',      'When you successfully enroll in a course'],
                    ['email_payment',        'Payment Receipts',              'When a payment is processed or confirmed'],
                    ['email_course_updates', 'Course Updates',                'When courses you\'re enrolled in are updated'],
                    ['email_announcements',  'Platform Announcements',        'Important updates about Learnerium'],
                    ['email_new_student',    'New Student Enrolled (Instructor)', 'When a student enrolls in your course'],
                    ['email_payout',         'Payout Notifications (Instructor)', 'When your earnings are processed'],
                    ['email_marketing',      'Promotional Emails',            'Offers, deals and course recommendations'],
                ];
                @endphp
                @foreach($emailPrefs as [$key, $label, $desc])
                <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50/50 transition">
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $label }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $desc }}</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 ml-4">
                        <input type="checkbox" name="{{ $key }}" value="1" class="sr-only peer"
                               {{ $prefs->{$key} ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer
                                    peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                    after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all
                                    peer-checked:bg-primary-jlm border border-gray-300"></div>
                    </label>
                </div>
                @endforeach
            </div>
        </div>

        {{-- IN-APP NOTIFICATIONS --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                <div class="w-9 h-9 bg-amber-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-bell text-amber-600 text-sm"></i>
                </div>
                <div>
                    <h2 class="font-bold text-gray-900 text-sm">In-App Notifications</h2>
                    <p class="text-xs text-gray-400">Shown in the notification bell inside the app</p>
                </div>
            </div>
            <div class="divide-y divide-gray-50">
                @php
                $inappPrefs = [
                    ['inapp_enrollment',     'Enrollment Confirmations',   'Bell alert when you enroll'],
                    ['inapp_payment',        'Payment Confirmations',      'Bell alert on successful payment'],
                    ['inapp_course_updates', 'Course Updates',             'Bell alert when courses change'],
                    ['inapp_announcements',  'Platform Announcements',     'Bell alert for system messages'],
                ];
                @endphp
                @foreach($inappPrefs as [$key, $label, $desc])
                <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50/50 transition">
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $label }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $desc }}</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 ml-4">
                        <input type="checkbox" name="{{ $key }}" value="1" class="sr-only peer"
                               {{ $prefs->{$key} ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer
                                    peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                    after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all
                                    peer-checked:bg-amber-500 border border-gray-300"></div>
                    </label>
                </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-gradient-to-r from-primary-jlm to-secondary-jlm text-white px-8 py-3 rounded-xl font-bold text-sm shadow hover:opacity-90 transition">
                <i class="fas fa-save mr-2"></i>Save Preferences
            </button>
        </div>
    </form>

</div>
</div>
@endsection
