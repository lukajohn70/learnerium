@extends('layouts.app')

@section('title', 'Instructor Applications — Admin Portal')

@section('content')

<div class="bg-gray-900 text-white py-12 px-4">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold">Instructor Verification Requests</h1>
            <p class="text-gray-400 text-sm mt-1">Review applicant qualifications, approve new instructors, or send feedback.</p>
        </div>
        <div class="bg-white/10 px-4 py-2 rounded-xl border border-white/20 text-xs font-semibold">
            Total Requests: {{ $applications->count() }}
        </div>
    </div>
</div>

<main class="py-12 px-4 bg-gray-50 min-h-[65vh]">
    <div class="max-w-7xl mx-auto space-y-6">

        @if(session('status'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl text-sm font-semibold shadow-sm flex items-center gap-2">
                <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-gray-800 text-base">Applications List</h2>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($applications as $app)
                    <div class="p-6 md:p-8 hover:bg-gray-50/50 transition">
                        <div class="flex flex-col md:flex-row justify-between md:items-start gap-6">

                            <!-- Applicant Info -->
                            <div class="space-y-3 flex-1">
                                <div class="flex items-center gap-3">
                                    <img src="https://placehold.co/40x40/1b2299/f7de7a?text={{ urlencode(substr($app->user->name ?? 'AP', 0, 2)) }}" class="w-10 h-10 rounded-full">
                                    <div>
                                        <h3 class="font-bold text-gray-900 text-lg leading-tight">{{ $app->user->name ?? 'Applicant' }}</h3>
                                        <p class="text-xs text-gray-400">{{ $app->user->email ?? '' }} · Submitted {{ $app->created_at->diffForHumans() }}</p>
                                    </div>

                                    <!-- Status Badge -->
                                    <div class="ml-auto md:ml-2">
                                        @if($app->isPending())
                                            <span class="bg-amber-100 text-amber-800 text-xs font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">Pending</span>
                                        @elseif($app->isApproved())
                                            <span class="bg-emerald-100 text-emerald-800 text-xs font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">Approved</span>
                                        @else
                                            <span class="bg-red-100 text-red-800 text-xs font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">Rejected</span>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <p class="font-bold text-sm text-primary-jlm">{{ $app->headline }}</p>
                                    <span class="inline-block bg-primary-jlm/5 text-primary-jlm text-xs font-semibold px-2.5 py-0.5 rounded-lg mt-1">
                                        {{ $app->expertise_area }}
                                    </span>
                                </div>

                                <p class="text-sm text-gray-600 leading-relaxed bg-gray-50 p-4 rounded-2xl border border-gray-100">
                                    {{ $app->bio }}
                                </p>

                                <div class="flex flex-wrap items-center gap-4 text-xs">
                                    @if($app->portfolio_url)
                                        <a href="{{ $app->portfolio_url }}" target="_blank" class="text-primary-jlm font-bold hover:underline flex items-center gap-1">
                                            <i class="fas fa-external-link-alt"></i> Portfolio / LinkedIn
                                        </a>
                                    @endif
                                    @if($app->sample_video_url)
                                        <a href="{{ $app->sample_video_url }}" target="_blank" class="text-secondary-jlm font-bold hover:underline flex items-center gap-1">
                                            <i class="fas fa-play-circle"></i> Sample Video
                                        </a>
                                    @endif
                                </div>

                                @if($app->isRejected() && $app->rejection_reason)
                                    <p class="text-xs text-red-600 font-medium">Rejection Reason: {{ $app->rejection_reason }}</p>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex md:flex-col gap-2.5 flex-shrink-0">
                                @if(!$app->isApproved())
                                    <form action="{{ route('admin.instructor.applications.approve', $app) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl font-bold text-xs shadow-md transition flex items-center justify-center gap-1.5">
                                            <i class="fas fa-check-circle"></i> Approve Instructor
                                        </button>
                                    </form>
                                @endif

                                @if(!$app->isRejected())
                                    <form action="{{ route('admin.instructor.applications.reject', $app) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full border border-red-300 text-red-600 hover:bg-red-50 px-5 py-2.5 rounded-xl font-bold text-xs transition flex items-center justify-center gap-1.5">
                                            <i class="fas fa-times-circle"></i> Reject Application
                                        </button>
                                    </form>
                                @endif
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-gray-400">
                        <i class="fas fa-inbox text-4xl mb-3"></i>
                        <p class="font-bold text-gray-600">No Applications Found</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</main>

@endsection
