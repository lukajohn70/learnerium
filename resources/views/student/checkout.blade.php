@extends('layouts.app')

@section('title', 'Secure Checkout — ' . $course->title)

@section('content')
<div class="py-12 px-4 bg-gray-50 min-h-[80vh] text-gray-900">
    <div class="max-w-4xl mx-auto">
        
        <!-- Page Title & Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-200 pb-6">
            <div>
                <a href="{{ route('course.detail', $course->slug) }}" class="inline-flex items-center text-xs font-semibold text-primary-jlm hover:underline transition mb-2">
                    <i class="fas fa-arrow-left mr-1.5"></i> Back to Course Details
                </a>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-2.5">
                    <i class="fas fa-shopping-cart text-primary-jlm"></i> Secure Checkout
                </h1>
                <p class="text-gray-500 text-sm mt-1">Review your order details and complete payment securely.</p>
            </div>

            <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 px-4 py-2 rounded-2xl shadow-xs self-start sm:self-auto">
                <i class="fas fa-shield-alt text-emerald-600 text-sm"></i>
                <span class="text-xs font-bold text-emerald-800">256-Bit SSL Encrypted</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- Left Column: Course Summary & Optional Coupon -->
            <div class="md:col-span-2 space-y-6">
                
                {{-- Course Summary Box --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 flex flex-col sm:flex-row gap-5">
                        <img src="{{ $course->thumbnailUrl() }}" alt="{{ $course->title }}" class="w-full sm:w-36 h-28 object-cover rounded-xl border border-gray-100 flex-shrink-0">
                        <div class="space-y-1.5 min-w-0 flex-1">
                            @if($course->category)
                                <span class="inline-block bg-primary-jlm/10 text-primary-jlm text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded-md">
                                    {{ $course->category }}
                                </span>
                            @endif
                            <h2 class="text-lg font-extrabold text-gray-900 leading-snug">{{ $course->title }}</h2>
                            <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed">{{ Str::limit(strip_tags($course->description), 120) }}</p>
                            <div class="flex flex-wrap items-center gap-3 text-xs text-gray-400 pt-1 font-medium">
                                @if($course->duration_minutes)
                                    <span><i class="fas fa-clock mr-1 text-gray-400"></i> {{ round($course->duration_minutes / 60, 1) }} Hours</span>
                                    <span>·</span>
                                @endif
                                <span>Instructor: {{ $course->instructor->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Togglable Optional Coupon Box --}}
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                    <button type="button" id="toggle_coupon_btn" class="w-full flex items-center justify-between text-left font-bold text-gray-800 focus:outline-none group">
                        <span class="flex items-center gap-2 text-sm">
                            <i class="fas fa-tag text-secondary-jlm"></i> Have a Coupon Code?
                        </span>
                        <span class="text-xs font-semibold text-primary-jlm group-hover:underline flex items-center gap-1">
                            <span id="coupon_toggle_label">Enter code</span>
                            <i class="fas fa-chevron-down text-[10px] transition-transform duration-200" id="coupon_chevron"></i>
                        </span>
                    </button>

                    <div id="coupon_container" class="hidden pt-4 border-t border-gray-100 mt-4">
                        <div class="flex gap-3">
                            <input type="text" id="coupon_input" placeholder="ENTER CODE" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary-jlm/30 focus:border-secondary-jlm text-gray-800 font-bold tracking-widest uppercase text-sm bg-gray-50">
                            <button type="button" id="apply_coupon_btn" class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-2.5 rounded-xl text-sm font-bold transition flex-shrink-0">
                                Apply
                            </button>
                        </div>
                        <div id="coupon_msg" class="text-xs font-semibold hidden mt-2"></div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Order Summary Sidebar -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden sticky top-24">
                    <div class="bg-gray-50/80 px-6 py-4 border-b border-gray-200">
                        <h3 class="font-extrabold text-gray-900 text-base">Order Summary</h3>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        
                        {{-- Multi-Currency Selection --}}
                        <div class="space-y-1.5 pb-2 border-b border-gray-100">
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-500">Payment Currency</label>
                            <select id="currency_selector" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs font-bold text-gray-800 focus:outline-none focus:border-primary-jlm">
                                <option value="NGN" data-rate="1" data-symbol="₦">NGN — Nigerian Naira (₦)</option>
                                <option value="GHS" data-rate="0.00495" data-symbol="GH₵">GHS — Ghanaian Cedi (GH₵)</option>
                                <option value="USD" data-rate="0.000625" data-symbol="$">USD — US Dollar ($)</option>
                                <option value="KES" data-rate="0.105" data-symbol="KSh">KES — Kenyan Shilling (KSh)</option>
                                <option value="ZAR" data-rate="0.015" data-symbol="R">ZAR — South African Rand (R)</option>
                            </select>
                        </div>

                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Original Price</span>
                            <span class="font-bold text-gray-800" id="original_price_display">₦{{ number_format($course->price, 2) }}</span>
                        </div>
                        
                        <div class="flex justify-between text-sm text-emerald-600 font-bold hidden" id="discount_row">
                            <span>Discount Applied</span>
                            <span id="discount_val">-₦0.00</span>
                        </div>
                        
                        <hr class="border-gray-100">
                        
                        <div class="flex justify-between items-baseline text-base text-gray-900 font-extrabold">
                            <span>Total Price</span>
                            <span class="text-primary-jlm text-xl" id="total_val">₦{{ number_format($course->price, 2) }}</span>
                        </div>

                        <!-- Paystack Initialization Form -->
                        <form action="{{ route('courses.checkout.initialize', $course) }}" method="POST" class="pt-4" id="checkout_form">
                            @csrf
                            <input type="hidden" name="coupon_code" id="applied_coupon_code">
                            <input type="hidden" name="currency" id="selected_currency" value="NGN">
                            
                            <button type="submit" id="submit_pay_btn" class="w-full bg-secondary-jlm hover:bg-secondary-jlm/90 text-white py-3.5 rounded-xl font-extrabold text-sm transition shadow-md flex items-center justify-center gap-2 hover:scale-[1.01]">
                                <i class="fas fa-lock text-xs"></i> <span>Proceed to Payment</span>
                            </button>
                        </form>

                        <div class="pt-2 text-center">
                            <p class="text-[11px] text-gray-400">
                                <i class="fas fa-shield-alt mr-1"></i> Processed securely via Paystack.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleCouponBtn = document.getElementById('toggle_coupon_btn');
    const couponContainer = document.getElementById('coupon_container');
    const couponChevron = document.getElementById('coupon_chevron');
    const couponToggleLabel = document.getElementById('coupon_toggle_label');
    const couponInput = document.getElementById('coupon_input');
    const applyCouponBtn = document.getElementById('apply_coupon_btn');
    const couponMsg = document.getElementById('coupon_msg');
    
    const currencySelector = document.getElementById('currency_selector');
    const selectedCurrencyInput = document.getElementById('selected_currency');
    const originalPriceDisplay = document.getElementById('original_price_display');
    const discountRow = document.getElementById('discount_row');
    const discountVal = document.getElementById('discount_val');
    const totalVal = document.getElementById('total_val');
    const appliedCouponInput = document.getElementById('applied_coupon_code');

    let basePriceNgn = {{ (float) $course->price }};
    let currentNgnPrice = basePriceNgn;

    function updatePriceDisplays() {
        const opt = currencySelector.options[currencySelector.selectedIndex];
        const rate = parseFloat(opt.dataset.rate || 1);
        const symbol = opt.dataset.symbol || '₦';
        const code = opt.value;

        selectedCurrencyInput.value = code;

        const convertedBase = basePriceNgn * rate;
        const convertedTotal = currentNgnPrice * rate;
        const convertedDiscount = (basePriceNgn - currentNgnPrice) * rate;

        originalPriceDisplay.textContent = symbol + convertedBase.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        totalVal.textContent = symbol + convertedTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        if (convertedDiscount > 0) {
            discountVal.textContent = '-' + symbol + convertedDiscount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }

    currencySelector.addEventListener('change', updatePriceDisplays);

    // Toggle Coupon Drawer
    toggleCouponBtn.addEventListener('click', function () {
        const isHidden = couponContainer.classList.contains('hidden');
        if (isHidden) {
            couponContainer.classList.remove('hidden');
            couponChevron.style.transform = 'rotate(180deg)';
            couponToggleLabel.textContent = 'Hide code';
            couponInput.focus();
        } else {
            couponContainer.classList.add('hidden');
            couponChevron.style.transform = 'rotate(0deg)';
            couponToggleLabel.textContent = 'Enter code';
        }
    });

    // Apply Coupon
    applyCouponBtn.addEventListener('click', function () {
        const code = couponInput.value.trim();
        if (!code) {
            showCouponMsg('Please enter a coupon code.', 'text-red-500');
            return;
        }

        applyCouponBtn.disabled = true;
        applyCouponBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        fetch('{{ route("courses.checkout.coupon", $course) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ coupon_code: code })
        })
        .then(res => res.json().then(data => ({ status: res.status, data: data })))
        .then(res => {
            applyCouponBtn.disabled = false;
            applyCouponBtn.innerHTML = 'Apply';

            if (res.status === 200) {
                const data = res.data;
                appliedCouponInput.value = code;
                currentNgnPrice = parseFloat(data.final_price);

                showCouponMsg(data.message, 'text-emerald-600');
                discountRow.classList.remove('hidden');
                updatePriceDisplays();
            } else {
                showCouponMsg(res.data.message || 'Invalid coupon code.', 'text-red-500');
            }
        })
        .catch(err => {
            applyCouponBtn.disabled = false;
            applyCouponBtn.innerHTML = 'Apply';
            showCouponMsg('Unable to verify coupon code.', 'text-red-500');
        });
    });

    function showCouponMsg(msg, className) {
        couponMsg.textContent = msg;
        couponMsg.className = 'text-xs font-semibold mt-2 ' + className;
        couponMsg.classList.remove('hidden');
    }
});
</script>
@endsection
