@extends('layouts.app')

@section('title', 'Manage Coupons — Instructor Portal')

@section('content')
<div class="container mx-auto px-4 py-10 max-w-6xl">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('instructor.dashboard') }}" class="text-gray-400 hover:text-primary-jlm transition"><i class="fas fa-arrow-left"></i></a>
                <h1 class="text-2xl font-extrabold text-gray-900">Course Discount Coupons</h1>
            </div>
            <p class="text-sm text-gray-500 mt-1">Create promotional discount codes for your courses to boost enrollment.</p>
        </div>
    </div>

    @if(session('status'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl text-sm font-medium flex items-center gap-2">
            <i class="fas fa-check-circle text-emerald-500"></i>{{ session('status') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-2xl text-sm font-medium">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Create Coupon Form --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 h-fit">
            <h2 class="font-extrabold text-gray-900 text-base mb-4 flex items-center gap-2">
                <i class="fas fa-plus-circle text-secondary-jlm"></i> Create New Coupon
            </h2>

            @if($myCourses->isEmpty())
                <div class="p-6 text-center text-gray-400 text-xs">
                    You need to create at least one course before creating coupons.
                </div>
            @else
            <form action="{{ route('instructor.coupons.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Coupon Code</label>
                    <input type="text" name="code" placeholder="e.g. FLASH50, SUMMER2026" required
                           class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs uppercase font-mono font-bold rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-secondary-jlm/30">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Applicable Course</label>
                    <select name="course_id" required class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-secondary-jlm/30">
                        @foreach($myCourses as $course)
                            <option value="{{ $course->id }}">{{ $course->title }} (₦{{ number_format($course->price, 2) }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Discount Type</label>
                        <select name="discount_type" required class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-secondary-jlm/30">
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed Amount (₦)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Discount Value</label>
                        <input type="number" step="0.01" min="1" name="discount_value" placeholder="e.g. 20 or 5000" required
                               class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs font-bold rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-secondary-jlm/30">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Usage Limit</label>
                        <input type="number" min="1" name="max_uses" placeholder="Unlimited if blank"
                               class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-secondary-jlm/30">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Expiration Date</label>
                        <input type="date" name="expires_at"
                               class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-secondary-jlm/30">
                    </div>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-secondary-jlm to-primary-jlm text-white text-xs font-bold py-3 rounded-xl transition shadow-md hover:opacity-90">
                    <i class="fas fa-tag mr-1.5"></i>Create Coupon
                </button>
            </form>
            @endif
        </div>

        {{-- Existing Coupons List --}}
        <div class="lg:col-span-2 bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-extrabold text-gray-900 text-sm flex items-center gap-2">
                    <i class="fas fa-tags text-primary-jlm"></i> Active & Past Coupons ({{ $coupons->count() }})
                </h2>
            </div>

            @if($coupons->isEmpty())
                <div class="p-16 text-center text-gray-400">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl text-gray-400">
                        <i class="fas fa-tag"></i>
                    </div>
                    <h3 class="text-base font-bold text-gray-700 mb-1">No coupons created yet</h3>
                    <p class="text-xs text-gray-400">Create discount codes to give promotional discounts to your prospective students.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-gray-600">
                        <thead class="bg-gray-50 uppercase font-semibold text-gray-500 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3.5">Code</th>
                                <th class="px-6 py-3.5">Course</th>
                                <th class="px-6 py-3.5">Discount</th>
                                <th class="px-6 py-3.5">Usage</th>
                                <th class="px-6 py-3.5">Expires</th>
                                <th class="px-6 py-3.5 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($coupons as $coupon)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4 font-mono font-bold text-primary-jlm text-sm">
                                    {{ $coupon->code }}
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-800 max-w-[180px] truncate">
                                    {{ $coupon->course->title ?? 'All Courses' }}
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900">
                                    {{ $coupon->discount_type === 'percentage' ? $coupon->discount_value . '%' : '₦' . number_format($coupon->discount_value, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-gray-800">{{ $coupon->used_count }}</span>
                                    <span class="text-gray-400">/ {{ $coupon->max_uses ?? '∞' }}</span>
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    {{ $coupon->expires_at ? $coupon->expires_at->format('d M Y') : 'Never' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('instructor.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Delete coupon {{ $coupon->code }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 p-1.5 rounded-lg hover:bg-rose-50 transition" title="Delete Coupon">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
