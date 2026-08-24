@extends('layouts.app')

@section('title', 'My Messages — Learnerium')
@section('meta_description', 'View all messages, announcements, and support replies from the Learnerium team.')

@push('head')
<style>
    .msg-card { transition: box-shadow 0.2s, transform 0.15s; }
    .msg-card:hover { box-shadow: 0 8px 30px rgba(27,34,153,0.10); transform: translateY(-1px); }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-purple-50/20 py-10 px-4">
    <div class="max-w-3xl mx-auto">

        {{-- Page Header --}}
        <div class="mb-8 flex items-center justify-between gap-4 flex-wrap">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight flex items-center gap-3">
                    <span class="w-10 h-10 bg-gradient-to-br from-primary-jlm to-secondary-jlm rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0">
                        <i class="fas fa-inbox text-white text-sm"></i>
                    </span>
                    My Messages
                </h1>
                <p class="text-sm text-gray-500 mt-1 ml-14">Announcements, replies, and support messages from Learnerium.</p>
            </div>
            <a href="{{ route('contact') }}" class="btn-jlm-primary px-5 py-2.5 rounded-xl text-xs font-bold flex items-center gap-2 shadow">
                <i class="fas fa-paper-plane"></i> Contact Support
            </a>
        </div>

        @if(session('status'))
        <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-5 py-3 text-sm font-semibold flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('status') }}
        </div>
        @endif

        {{-- Tabs --}}
        <div class="flex gap-2 mb-6 bg-white rounded-2xl border border-gray-200 p-1.5 shadow-sm" id="inboxTabBar">
            <button onclick="switchInboxTab('announcements')" id="inbox-tab-announcements"
                class="inbox-tab flex-1 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 bg-gradient-to-r from-blue-800 to-pink-600 text-white shadow-md flex items-center justify-center gap-2">
                <i class="fas fa-bullhorn"></i> Announcements
                <span class="bg-white/30 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $broadcasts->count() }}</span>
            </button>
            <button onclick="switchInboxTab('my-replies')" id="inbox-tab-my-replies"
                class="inbox-tab flex-1 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 text-gray-600 hover:bg-gray-100 flex items-center justify-center gap-2">
                <i class="fas fa-reply-all"></i> My Messages & Replies
                <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $myMessages->count() }}</span>
            </button>
        </div>

        {{-- PANEL 1: Announcements (Broadcast Emails) --}}
        <div id="inbox-panel-announcements" class="space-y-4">
            @if($broadcasts->isEmpty())
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-14 text-center">
                <i class="fas fa-bullhorn text-5xl text-gray-200 mb-4 block"></i>
                <p class="text-gray-400 font-semibold">No announcements yet.</p>
                <p class="text-xs text-gray-300 mt-1">Platform announcements from the Learnerium team will appear here.</p>
            </div>
            @else
                @foreach($broadcasts as $bc)
                <div class="msg-card bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    {{-- Card Header --}}
                    <div class="bg-gradient-to-r from-primary-jlm/5 to-secondary-jlm/5 px-6 py-4 border-b border-gray-100 flex items-start justify-between gap-3">
                        <div class="flex items-start gap-3 flex-1 min-w-0">
                            <div class="w-9 h-9 bg-gradient-to-br from-primary-jlm to-secondary-jlm rounded-xl flex items-center justify-center flex-shrink-0 shadow">
                                <i class="fas fa-bullhorn text-white text-xs"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="font-extrabold text-gray-900 text-sm leading-snug truncate">{{ $bc->subject }}</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">
                                    From <strong>{{ $bc->sender->name ?? 'Learnerium Admin' }}</strong> &bull; {{ $bc->created_at->format('d M Y, g:ia') }}
                                </p>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-700 whitespace-nowrap flex-shrink-0 self-center">Announcement</span>
                    </div>

                    {{-- Body --}}
                    <div class="px-6 py-5 text-sm text-gray-700 leading-relaxed">
                        {!! nl2br(e($bc->message)) !!}
                    </div>

                    {{-- Reply Section --}}
                    <div class="px-6 pb-5">
                        <button onclick="toggleBroadcastReply('bcReply-{{ $bc->id }}')"
                            class="text-xs font-bold text-primary-jlm hover:text-secondary-jlm transition flex items-center gap-1.5">
                            <i class="fas fa-reply"></i> Reply to Learnerium Team
                        </button>
                        <div id="bcReply-{{ $bc->id }}" class="hidden mt-3">
                            <form action="{{ route('user.reply', $bc) }}" method="POST" class="space-y-2.5">
                                @csrf
                                <textarea name="reply_text" rows="3" required
                                    placeholder="Write your reply to the Learnerium team..."
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-jlm/30 resize-none"></textarea>
                                <div class="flex gap-2 justify-end">
                                    <button type="button" onclick="toggleBroadcastReply('bcReply-{{ $bc->id }}')"
                                        class="text-xs font-bold text-gray-500 border border-gray-200 px-4 py-2 rounded-xl hover:bg-gray-50 transition">Cancel</button>
                                    <button type="submit"
                                        class="bg-gradient-to-r from-primary-jlm to-secondary-jlm text-white text-xs font-bold px-5 py-2 rounded-xl shadow hover:opacity-90 transition flex items-center gap-1.5">
                                        <i class="fas fa-paper-plane"></i> Send Reply
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>

        {{-- PANEL 2: My Messages & Replies --}}
        <div id="inbox-panel-my-replies" class="hidden space-y-4">
            @if($myMessages->isEmpty())
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-14 text-center">
                <i class="fas fa-comments text-5xl text-gray-200 mb-4 block"></i>
                <p class="text-gray-400 font-semibold">No messages yet.</p>
                <p class="text-xs text-gray-300 mt-1">Messages you send via Contact Support will appear here with any replies from the team.</p>
                <a href="{{ route('contact') }}" class="inline-block mt-5 text-xs font-bold text-primary-jlm underline underline-offset-2">
                    Send your first message →
                </a>
            </div>
            @else
                @foreach($myMessages as $msg)
                <div class="msg-card bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="bg-gray-50/80 px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-3 flex-wrap">
                        <div>
                            <p class="font-extrabold text-gray-900 text-sm leading-snug">{{ $msg->subject }}</p>
                            <p class="text-[11px] text-gray-400 mt-0.5">Sent {{ $msg->created_at->format('d M Y, g:ia') }}</p>
                        </div>
                        <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full flex-shrink-0 {{ $msg->status === 'replied' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $msg->status === 'replied' ? '✅ Replied' : '⏳ Awaiting Reply' }}
                        </span>
                    </div>

                    {{-- User's original message --}}
                    <div class="px-6 pt-4 pb-2">
                        <p class="text-[11px] font-bold text-gray-400 uppercase mb-2">Your Message:</p>
                        <div class="bg-blue-50/70 border border-blue-100 rounded-xl px-4 py-3 text-sm text-gray-700 leading-relaxed">
                            {!! nl2br(e($msg->message)) !!}
                        </div>
                    </div>

                    {{-- Admin reply --}}
                    @if($msg->admin_reply)
                    <div class="px-6 pt-2 pb-5">
                        <p class="text-[11px] font-bold text-emerald-600 uppercase mb-2 flex items-center gap-1.5">
                            <i class="fas fa-check-circle"></i>
                            Learnerium Team Reply &bull; {{ $msg->replied_at ? $msg->replied_at->format('d M Y, g:ia') : '' }}:
                        </p>
                        <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 text-sm text-emerald-900 leading-relaxed">
                            {!! nl2br(e($msg->admin_reply)) !!}
                        </div>
                    </div>
                    @else
                    <div class="px-6 pb-5 pt-2">
                        <p class="text-xs text-gray-400 italic">Our team will reply via email and it will also appear here.</p>
                    </div>
                    @endif
                </div>
                @endforeach
            @endif
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
window.switchInboxTab = function(tab) {
    ['announcements', 'my-replies'].forEach(t => {
        const panel = document.getElementById('inbox-panel-' + t);
        const btn = document.getElementById('inbox-tab-' + t);
        if (panel && btn) {
            if (t === tab) {
                panel.classList.remove('hidden');
                btn.className = 'inbox-tab flex-1 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 bg-gradient-to-r from-blue-800 to-pink-600 text-white shadow-md flex items-center justify-center gap-2';
            } else {
                panel.classList.add('hidden');
                btn.className = 'inbox-tab flex-1 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 text-gray-600 hover:bg-gray-100 flex items-center justify-center gap-2';
            }
        }
    });
};

window.toggleBroadcastReply = function(id) {
    const el = document.getElementById(id);
    if (el) {
        el.classList.toggle('hidden');
        if (!el.classList.contains('hidden')) {
            const textarea = el.querySelector('textarea');
            if (textarea) textarea.focus();
        }
    }
};
</script>
@endpush
