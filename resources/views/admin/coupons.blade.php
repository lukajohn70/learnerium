@extends('layouts.app')

@section('title', 'Manage Coupons — Admin Learnerium')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-ticket-alt text-amber-500"></i> Coupon Management
                </h1>
                <p class="text-xs text-gray-500 mt-1">Create and manage global or course-specific promotional discount coupons.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-xl text-xs font-bold shadow-sm hover:bg-gray-50 transition">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        @if(session('status'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                <i class="fas fa-check-circle text-emerald-500"></i>{{ session('status') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Create Form -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-4">
                <h3 class="font-extrabold text-gray-900 text-base flex items-center gap-2">
                    <i class="fas fa-plus-circle text-pink-600"></i> Create Coupon
                </h3>
                <form action="{{ route('admin.coupons.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Coupon Code</label>
                        <input type="text" name="code" required placeholder="e.g. SAVE20"
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-xl px-3 py-2 text-sm font-bold uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-pink-500/30">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Discount Type</label>
                        <select name="discount_type" class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-xl px-3 py-2 text-sm font-semibold focus:outline-none">
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed Amount (₦)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Discount Value</label>
                        <input type="number" name="discount_value" required min="1" step="0.01" placeholder="e.g. 20"
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-xl px-3 py-2 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-pink-500/30">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Applicable Course</label>
                        <select name="course_id" class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-xl px-3 py-2 text-sm font-semibold focus:outline-none">
                            <option value="">All Courses (Global)</option>
                            @foreach($courses as $c)
                                <option value="{{ $c->id }}">{{ $c->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Max Uses Limit (Optional)</label>
                        <input type="number" name="max_uses" min="1" placeholder="e.g. 50 (Leave empty for unlimited)"
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-xl px-3 py-2 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-pink-500/30">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Expiry Date (Optional)</label>
                        <input type="date" name="expires_at" class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-xl px-3 py-2 text-sm focus:outline-none">
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-800 to-pink-600 text-white py-2.5 rounded-xl font-extrabold text-sm shadow-md hover:opacity-90 transition">
                        <i class="fas fa-plus mr-1"></i> Create Coupon
                    </button>
                </form>
            </div>

            <!-- List -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-extrabold text-gray-900 text-base">Active Coupons</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($coupons as $coupon)
                    <div class="p-5 flex items-center justify-between gap-4 hover:bg-gray-50 transition">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="bg-amber-100 text-amber-800 font-black text-xs px-2.5 py-0.5 rounded-md uppercase tracking-wider">
                                    {{ $coupon->code }}
                                </span>
                                <span class="text-xs font-bold text-gray-800">
                                    {{ $coupon->discount_type === 'percentage' ? $coupon->discount_value.'%' : '₦'.number_format($coupon->discount_value,2) }} OFF
                                </span>
                            </div>
                            <p class="text-xs text-gray-500">
                                Scope: {{ $coupon->course ? $coupon->course->title : 'All Courses (Global)' }}
                                &bull; Uses: <span class="font-bold text-gray-700">{{ $coupon->used_count }} / {{ $coupon->max_uses ?? '∞' }}</span>
                                @if($coupon->expires_at)
                                    &bull; Expires: {{ $coupon->expires_at->format('d M Y') }}
                                @endif
                            </p>
                        </div>
                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Delete this coupon?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-bold hover:underline">Delete</button>
                        </form>
                    </div>
                    @empty
                    <div class="p-12 text-center text-gray-400">No coupons created yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
