@extends('layouts.app')

@section('title', 'Secure Checkout — ' . $course->title)

@section('content')
<div class="py-12 px-4 bg-gray-50 min-h-[85vh] text-gray-900 relative">
    <div class="max-w-5xl mx-auto relative z-10">
        
        <!-- Header / Breadcrumb -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-gray-200 pb-6">
            <div>
                <a href="{{ route('course.detail', $course->slug) }}" class="inline-flex items-center text-xs font-semibold text-primary-jlm hover:text-secondary-jlm transition mb-2">
                    <i class="fas fa-arrow-left mr-1.5"></i> Back to Course Details
                </a>
                <h1 class="text-3xl font-black tracking-tight text-gray-900 flex items-center gap-3">
                    <i class="fas fa-shield-alt text-secondary-jlm"></i> Complete Your Enrollment
                </h1>
                <p class="text-gray-500 text-sm mt-1">100% Secure Checkout powered by Paystack</p>
            </div>

            <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 px-4 py-2 rounded-2xl shadow-xs">
                <i class="fas fa-lock text-emerald-600 text-sm"></i>
                <span class="text-xs font-bold text-emerald-800">256-Bit SSL Encrypted</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Column: Custom Native Payment Method Form -->
            <div class="lg:col-span-7 space-y-6">

                {{-- Course Detail Summary Box --}}
                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm flex items-center gap-4">
                    <img src="{{ $course->thumbnailUrl() }}" alt="{{ $course->title }}" class="w-24 h-20 object-cover rounded-xl border border-gray-100 flex-shrink-0">
                    <div class="space-y-1 min-w-0">
                        <span class="inline-block bg-primary-jlm/10 text-primary-jlm text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-md">
                            {{ $course->category ?? 'General' }}
                        </span>
                        <h2 class="text-base font-bold text-gray-900 leading-tight truncate">{{ $course->title }}</h2>
                        <p class="text-xs text-gray-500 flex items-center gap-3 pt-0.5">
                            <span><i class="fas fa-clock mr-1 text-gray-400"></i> {{ round($course->duration_minutes / 60, 1) }} Hours</span>
                            <span>·</span>
                            <span>Instructor: {{ $course->instructor->name }}</span>
                        </p>
                    </div>
                </div>

                {{-- CUSTOM LIGHT PAYMENT FORM CONTAINER --}}
                <div class="bg-white border border-gray-200 rounded-3xl p-6 sm:p-8 shadow-md space-y-6">
                    
                    {{-- Payment Method Tab Selector --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-3">Select Payment Method</label>
                        <div class="grid grid-cols-2 gap-3 p-1.5 bg-gray-100 rounded-2xl border border-gray-200">
                            <button type="button" id="tab_card" class="payment-tab active-tab flex items-center justify-center gap-2 py-3 rounded-xl text-xs font-extrabold transition-all duration-200 bg-primary-jlm text-white shadow-sm">
                                <i class="fas fa-credit-card"></i>
                                <span>Card Payment</span>
                            </button>
                            <button type="button" id="tab_transfer" class="payment-tab flex items-center justify-center gap-2 py-3 rounded-xl text-xs font-extrabold text-gray-600 hover:text-gray-900 transition-all duration-200">
                                <i class="fas fa-university"></i>
                                <span>Bank Transfer / USSD</span>
                            </button>
                        </div>
                    </div>

                    {{-- CARD FORM PANEL --}}
                    <div id="panel_card" class="space-y-4">
                        
                        {{-- Card Brands Detected Header --}}
                        <div class="flex items-center justify-between pt-1">
                            <span class="text-xs font-bold text-gray-700">Debit or Credit Card</span>
                            <div class="flex items-center gap-2 text-xl text-gray-400" id="card_brand_badges">
                                <i class="fab fa-cc-visa" id="badge_visa"></i>
                                <i class="fab fa-cc-mastercard" id="badge_mastercard"></i>
                                <i class="far fa-credit-card text-emerald-600" id="badge_verve" title="Verve"></i>
                                <i class="fab fa-cc-amex" id="badge_amex"></i>
                            </div>
                        </div>

                        {{-- Cardholder Name --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Cardholder Name</label>
                            <div class="relative">
                                <input type="text" id="card_name" value="{{ Auth::user()->name }}" placeholder="NAME ON CARD" class="w-full bg-gray-50 border border-gray-200 focus:border-primary-jlm focus:ring-2 focus:ring-primary-jlm/20 text-gray-900 rounded-xl px-4 py-3 text-sm font-semibold uppercase placeholder-gray-400 outline-none transition">
                                <i class="fas fa-user absolute right-4 top-3.5 text-gray-400 text-sm"></i>
                            </div>
                        </div>

                        {{-- Card Number --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Card Number</label>
                            <div class="relative">
                                <input type="text" id="card_number" maxlength="19" placeholder="0000 0000 0000 0000" class="w-full bg-gray-50 border border-gray-200 focus:border-primary-jlm focus:ring-2 focus:ring-primary-jlm/20 text-gray-900 rounded-xl px-4 py-3 text-sm font-mono tracking-widest placeholder-gray-400 outline-none transition">
                                <i class="fas fa-lock absolute right-4 top-3.5 text-gray-400 text-sm" id="card_icon"></i>
                            </div>
                        </div>

                        {{-- Expiry & CVV Grid --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Expiry Date</label>
                                <input type="text" id="card_expiry" maxlength="5" placeholder="MM / YY" class="w-full bg-gray-50 border border-gray-200 focus:border-primary-jlm focus:ring-2 focus:ring-primary-jlm/20 text-gray-900 rounded-xl px-4 py-3 text-sm font-mono text-center placeholder-gray-400 outline-none transition">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">CVV / CVC</label>
                                <div class="relative">
                                    <input type="password" id="card_cvv" maxlength="4" placeholder="123" class="w-full bg-gray-50 border border-gray-200 focus:border-primary-jlm focus:ring-2 focus:ring-primary-jlm/20 text-gray-900 rounded-xl px-4 py-3 text-sm font-mono text-center placeholder-gray-400 outline-none transition">
                                    <i class="fas fa-question-circle absolute right-3.5 top-3.5 text-gray-400 text-xs" title="3-digit code on back of card"></i>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- BANK TRANSFER / USSD PANEL --}}
                    <div id="panel_transfer" class="hidden space-y-4">
                        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 text-xs text-blue-900 leading-relaxed space-y-2">
                            <div class="flex items-center gap-2 font-bold text-blue-900 text-sm">
                                <i class="fas fa-info-circle text-primary-jlm"></i> Instant Bank Transfer Instructions
                            </div>
                            <p>When you click <strong>Complete Order</strong>, Paystack will generate a dedicated virtual bank account number or USSD code for instant transfer.</p>
                            <p>Your payment is verified automatically within 5 seconds of transfer!</p>
                        </div>
                    </div>

                    {{-- Togglable Optional Coupon Drawer --}}
                    <div class="pt-2 border-t border-gray-100">
                        <button type="button" id="toggle_coupon_btn" class="w-full flex items-center justify-between text-left font-semibold text-gray-700 focus:outline-none group">
                            <span class="flex items-center gap-2 text-xs">
                                <i class="fas fa-tag text-secondary-jlm"></i> Have a Promotional Coupon?
                            </span>
                            <span class="text-xs text-primary-jlm group-hover:underline flex items-center gap-1">
                                <span id="coupon_toggle_label">Enter code</span>
                                <i class="fas fa-chevron-down text-[10px] transition-transform duration-200" id="coupon_chevron"></i>
                            </span>
                        </button>

                        <div id="coupon_container" class="hidden pt-3 mt-3 border-t border-gray-100">
                            <div class="flex gap-2">
                                <input type="text" id="coupon_input" placeholder="ENTER CODE" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-gray-900 font-mono uppercase text-xs focus:outline-none focus:border-secondary-jlm">
                                <button type="button" id="apply_coupon_btn" class="bg-gray-800 hover:bg-gray-900 text-white px-5 py-2.5 rounded-xl text-xs font-bold transition">
                                    Apply
                                </button>
                            </div>
                            <div id="coupon_msg" class="text-xs font-semibold hidden mt-2"></div>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Right Column: Order Summary Sidebar -->
            <div class="lg:col-span-5">
                <div class="bg-white border border-gray-200 rounded-3xl p-6 shadow-md sticky top-24 space-y-6">
                    <h3 class="font-extrabold text-lg text-gray-900 border-b border-gray-100 pb-4 flex items-center justify-between">
                        <span>Order Summary</span>
                        <i class="fas fa-receipt text-gray-400"></i>
                    </h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Course Price</span>
                            <span class="font-bold text-gray-900">₦{{ number_format($course->price, 2) }}</span>
                        </div>

                        <div class="flex justify-between text-emerald-600 font-bold hidden" id="discount_row">
                            <span>Discount Applied</span>
                            <span id="discount_val">-₦0.00</span>
                        </div>

                        <div class="flex justify-between text-gray-500 text-xs">
                            <span>Platform Processing Fee</span>
                            <span class="text-emerald-600 font-semibold">FREE</span>
                        </div>

                        <div class="border-t border-gray-100 pt-4 flex justify-between items-baseline">
                            <span class="text-sm font-bold text-gray-700">Total Payable</span>
                            <span class="text-2xl font-black text-primary-jlm" id="total_val">
                                ₦{{ number_format($course->price, 2) }}
                            </span>
                        </div>
                    </div>

                    {{-- Main Pay Action Button --}}
                    <div class="pt-2">
                        <button type="button" id="pay_now_btn" class="w-full bg-secondary-jlm hover:bg-secondary-jlm/90 text-white py-4 rounded-2xl font-extrabold text-sm tracking-wide shadow-md hover:scale-[1.01] transition-all duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-lock text-xs"></i>
                            <span id="pay_btn_text">Pay ₦{{ number_format($course->price, 2) }} Securely</span>
                        </button>

                        <div id="payment_error_msg" class="mt-3 text-xs text-rose-600 text-center font-semibold hidden bg-rose-50 border border-rose-200 p-2.5 rounded-xl"></div>
                    </div>

                    {{-- Trust Badges --}}
                    <div class="pt-4 border-t border-gray-100 text-center space-y-2">
                        <div class="flex items-center justify-center gap-4 text-xs text-gray-500">
                            <span class="flex items-center gap-1"><i class="fas fa-check-circle text-emerald-600"></i> Lifetime Access</span>
                            <span class="flex items-center gap-1"><i class="fas fa-certificate text-amber-500"></i> Verified Certificate</span>
                        </div>
                        <p class="text-[11px] text-gray-400 pt-1">
                            <i class="fas fa-shield-alt mr-1"></i> Transactions processed securely via Paystack.
                        </p>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>

{{-- Paystack Inline SDK Script --}}
<script src="https://js.paystack.co/v1/inline.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabCard = document.getElementById('tab_card');
    const tabTransfer = document.getElementById('tab_transfer');
    const panelCard = document.getElementById('panel_card');
    const panelTransfer = document.getElementById('panel_transfer');
    
    const cardNameInput = document.getElementById('card_name');
    const cardNumberInput = document.getElementById('card_number');
    const cardExpiryInput = document.getElementById('card_expiry');
    const cardCvvInput = document.getElementById('card_cvv');
    
    const toggleCouponBtn = document.getElementById('toggle_coupon_btn');
    const couponContainer = document.getElementById('coupon_container');
    const couponChevron = document.getElementById('coupon_chevron');
    const couponToggleLabel = document.getElementById('coupon_toggle_label');
    const couponInput = document.getElementById('coupon_input');
    const applyCouponBtn = document.getElementById('apply_coupon_btn');
    const couponMsg = document.getElementById('coupon_msg');
    
    const discountRow = document.getElementById('discount_row');
    const discountVal = document.getElementById('discount_val');
    const totalVal = document.getElementById('total_val');
    const payNowBtn = document.getElementById('pay_now_btn');
    const payBtnText = document.getElementById('pay_btn_text');
    const paymentErrorMsg = document.getElementById('payment_error_msg');

    let currentPrice = {{ (float) $course->price }};
    let appliedCouponCode = '';
    let selectedMethod = 'card';

    // Tab Switching Logic
    tabCard.addEventListener('click', function () {
        selectedMethod = 'card';
        tabCard.className = 'payment-tab active-tab flex items-center justify-center gap-2 py-3 rounded-xl text-xs font-extrabold transition-all duration-200 bg-primary-jlm text-white shadow-sm';
        tabTransfer.className = 'payment-tab flex items-center justify-center gap-2 py-3 rounded-xl text-xs font-extrabold text-gray-600 hover:text-gray-900 transition-all duration-200';
        panelCard.classList.remove('hidden');
        panelTransfer.classList.add('hidden');
    });

    tabTransfer.addEventListener('click', function () {
        selectedMethod = 'transfer';
        tabTransfer.className = 'payment-tab active-tab flex items-center justify-center gap-2 py-3 rounded-xl text-xs font-extrabold transition-all duration-200 bg-primary-jlm text-white shadow-sm';
        tabCard.className = 'payment-tab flex items-center justify-center gap-2 py-3 rounded-xl text-xs font-extrabold text-gray-600 hover:text-gray-900 transition-all duration-200';
        panelTransfer.classList.remove('hidden');
        panelCard.classList.add('hidden');
    });

    // Card Number Formatting (0000 0000 0000 0000) & Brand Detection
    cardNumberInput.addEventListener('input', function (e) {
        let val = e.target.value.replace(/\D/g, '').substring(0, 16);
        val = val.replace(/(.{4})/g, '$1 ').trim();
        e.target.value = val;

        // Card Brand Detection
        const clean = val.replace(/\s/g, '');
        const visaBadge = document.getElementById('badge_visa');
        const masterBadge = document.getElementById('badge_mastercard');
        const verveBadge = document.getElementById('badge_verve');
        const amexBadge = document.getElementById('badge_amex');

        // Reset opacities
        [visaBadge, masterBadge, verveBadge, amexBadge].forEach(b => b.classList.add('opacity-30'));

        if (clean.startsWith('4')) {
            visaBadge.classList.remove('opacity-30');
        } else if (clean.startsWith('51') || clean.startsWith('52') || clean.startsWith('53') || clean.startsWith('54') || clean.startsWith('55')) {
            masterBadge.classList.remove('opacity-30');
        } else if (clean.startsWith('506') || clean.startsWith('650') || clean.startsWith('507')) {
            verveBadge.classList.remove('opacity-30');
        } else if (clean.startsWith('34') || clean.startsWith('37')) {
            amexBadge.classList.remove('opacity-30');
        }
    });

    // Expiry Formatting (MM / YY)
    cardExpiryInput.addEventListener('input', function (e) {
        let val = e.target.value.replace(/\D/g, '').substring(0, 4);
        if (val.length >= 3) {
            val = val.substring(0, 2) + ' / ' + val.substring(2);
        }
        e.target.value = val;
    });

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

    // Apply Coupon Code
    applyCouponBtn.addEventListener('click', function () {
        const code = couponInput.value.trim();
        if (!code) {
            showCouponMsg('Please enter a coupon code.', 'text-rose-600');
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
                appliedCouponCode = code;
                currentPrice = parseFloat(data.final_price);

                showCouponMsg(data.message, 'text-emerald-600');

                discountVal.textContent = '-₦' + parseFloat(data.discount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                totalVal.textContent = '₦' + currentPrice.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                discountRow.classList.remove('hidden');

                if (currentPrice <= 0) {
                    payBtnText.textContent = 'Enroll Instantly (Free)';
                } else {
                    payBtnText.textContent = 'Pay ₦' + currentPrice.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' Securely';
                }
            } else {
                showCouponMsg(res.data.message || 'Invalid coupon code.', 'text-rose-600');
            }
        })
        .catch(err => {
            applyCouponBtn.disabled = false;
            applyCouponBtn.innerHTML = 'Apply';
            showCouponMsg('Unable to verify coupon code.', 'text-rose-600');
        });
    });

    function showCouponMsg(msg, className) {
        couponMsg.textContent = msg;
        couponMsg.className = 'text-xs font-semibold mt-2 ' + className;
        couponMsg.classList.remove('hidden');
    }

    function showError(msg) {
        paymentErrorMsg.textContent = msg;
        paymentErrorMsg.classList.remove('hidden');
    }

    function hideError() {
        paymentErrorMsg.classList.add('hidden');
    }

    // Pay Now Action Execution
    payNowBtn.addEventListener('click', function () {
        hideError();

        // Validate Card fields if card tab selected and price > 0
        if (selectedMethod === 'card' && currentPrice > 0) {
            const cardNum = cardNumberInput.value.replace(/\s/g, '');
            if (cardNum.length < 13) {
                showError('Please enter a valid credit or debit card number.');
                cardNumberInput.focus();
                return;
            }
            if (cardExpiryInput.value.trim().length < 5) {
                showError('Please enter a valid expiry date (MM / YY).');
                cardExpiryInput.focus();
                return;
            }
            if (cardCvvInput.value.trim().length < 3) {
                showError('Please enter your card security code (CVV).');
                cardCvvInput.focus();
                return;
            }
        }

        payNowBtn.disabled = true;
        payNowBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i> Processing Secure Request...';

        // Initialize Checkout Transaction via AJAX
        fetch('{{ route("courses.checkout.initialize", $course) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                coupon_code: appliedCouponCode
            })
        })
        .then(res => res.json().then(data => ({ status: res.status, data: data })))
        .then(res => {
            if (res.status === 200 && res.data.success) {
                const data = res.data;

                // Free course enrollment complete
                if (currentPrice <= 0 || !data.authorization_url) {
                    window.location.href = "{{ route('course.detail', $course->slug) }}";
                    return;
                }

                // If Bank Transfer tab selected, redirect directly to Paystack payment URL
                if (selectedMethod === 'transfer') {
                    window.location.href = data.authorization_url;
                    return;
                }

                // Paystack Inline Modal Launch for Card Payments
                const handler = PaystackPop.setup({
                    key: '{{ $publicKey ?? config("services.paystack.public_key") }}',
                    email: '{{ Auth::user()->email }}',
                    amount: Math.round(currentPrice * 100),
                    ref: data.reference,
                    onClose: function () {
                        payNowBtn.disabled = false;
                        payNowBtn.innerHTML = '<i class="fas fa-lock text-xs mr-1"></i> <span id="pay_btn_text">Pay ₦' + currentPrice.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' Securely</span>';
                    },
                    callback: function (response) {
                        window.location.href = "{{ route('payment.callback') }}?reference=" + response.reference;
                    }
                });

                handler.openIframe();
            } else {
                payNowBtn.disabled = false;
                payNowBtn.innerHTML = '<i class="fas fa-lock text-xs mr-1"></i> Pay ₦' + currentPrice.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' Securely';
                showError(res.data.message || 'Payment initialization failed. Please try again.');
            }
        })
        .catch(err => {
            payNowBtn.disabled = false;
            payNowBtn.innerHTML = '<i class="fas fa-lock text-xs mr-1"></i> Pay ₦' + currentPrice.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' Securely';
            showError('Unable to connect to payment server. Please check your internet connection.');
        });
    });

});
</script>
@endsection
