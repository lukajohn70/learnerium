@extends('layouts.app')

@section('title', 'Payments & Revenue — Admin Learnerium')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-credit-card text-emerald-600"></i> Payments & Revenue
                </h1>
                <p class="text-xs text-gray-500 mt-1">Transaction history and verified Paystack payments.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-xl text-xs font-bold shadow-sm hover:bg-gray-50 transition">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <!-- Total Revenue Card -->
        <div class="bg-gradient-to-r from-emerald-700 to-teal-500 text-white p-6 rounded-2xl shadow-sm mb-6 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-100 mb-1">Total Platform Revenue</p>
                <div class="text-3xl font-black">₦{{ number_format($totalRevenue, 2) }}</div>
            </div>
            <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center text-white text-2xl">
                <i class="fas fa-wallet"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead class="bg-gray-50 text-gray-600 uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-4 text-left">Student</th>
                            <th class="px-6 py-4 text-left">Course</th>
                            <th class="px-6 py-4 text-left">Amount</th>
                            <th class="px-6 py-4 text-left">Status</th>
                            <th class="px-6 py-4 text-left">Reference</th>
                            <th class="px-6 py-4 text-left">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($enrollments as $enrollment)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-bold text-gray-900">{{ $enrollment->user->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ Str::limit($enrollment->course->title ?? '—', 35) }}</td>
                            <td class="px-6 py-4 font-extrabold text-gray-900">₦{{ number_format($enrollment->amount_paid, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                                    {{ ucfirst($enrollment->payment_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-mono text-gray-500">{{ $enrollment->payment_reference ?? 'Free / Manual' }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $enrollment->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">No payment records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($enrollments->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $enrollments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
