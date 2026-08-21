@extends('layouts.app')

@section('title', 'Secure Checkout — ' . $course->title)

@section('content')
<div class="py-12 px-4 bg-gray-50 min-h-[80vh]">
    <div class="max-w-4xl mx-auto">
        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 flex items-center gap-2">
                <i class="fas fa-shopping-cart text-primary-jlm"></i> Secure Checkout
            </h1>
            <p class="text-gray-500 mt-1">Review your order details and complete payment securely.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Course Summary Card -->
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100">
                    <div class="p-6 flex gap-5">
                        <img src="{{ $course->thumbnailUrl() }}" alt="{{ $course->title }}" class="w-32 h-24 object-cover rounded-xl border border-gray-100 flex-shrink-0">
                        <div class="space-y-1">
                            <span class="inline-block bg-primary-jlm/5 text-primary-jlm text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md">
                                {{ $course->category ?? 'General' }}
                            </span>
                            <h2 class="text-xl font-bold text-gray-800 leading-snug">{{ $course->title }}</h2>
                            <p class="text-sm text-gray-500 line-clamp-2">{{ Str::limit($course->description, 120) }}</p>
                            <div class="flex items-center gap-3 text-xs text-gray-400 mt-2 font-medium">
                                <span><i class="fas fa-clock mr-1"></i> {{ round($course->duration_minutes / 60, 1) }} hours</span>
                                <span>·</span>
                                <span>Instructor: {{ $course->instructor->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Togglable Coupon Box (Optional) -->
                <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
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
                            <input type="text" id="coupon_input" placeholder="ENTER CODE" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary-jlm/30 focus:border-secondary-jlm text-gray-800 font-bold tracking-widest uppercase text-sm">
                            <button type="button" id="apply_coupon_btn" class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition flex-shrink-0">
                                Apply
                            </button>
                        </div>
                        <div id="coupon_msg" class="text-xs font-semibold hidden mt-2"></div>
                    </div>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden sticky top-24">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                        <h3 class="font-bold text-gray-800">Order Summary</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Original Price</span>
                            <span class="font-semibold text-gray-700">₦{{ number_format($course->price, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-green-600 hidden" id="discount_row">
                            <span>Discount Applied</span>
                            <span class="font-semibold" id="discount_val">-₦0.00</span>
                        </div>
                        <hr class="border-gray-100">
                        <div class="flex justify-between text-base text-gray-800 font-bold">
                            <span>Total Price</span>
                            <span class="text-primary-jlm text-xl" id="total_val">₦{{ number_format($course->price, 2) }}</span>
                        </div>

                        <!-- Checkout Forms (Optional Coupon) -->
                        <form action="{{ route('courses.checkout.initialize', $course) }}" method="POST" class="pt-4">
                            @csrf
                            <input type="hidden" name="coupon_code" id="applied_coupon_code">
                            <button type="submit" class="w-full bg-secondary-jlm hover:bg-secondary-jlm/90 text-white py-3.5 rounded-xl font-bold text-sm transition shadow-md flex items-center justify-center gap-2">
                                <i class="fas fa-lock"></i> <span id="checkout_btn_text">Proceed to Payment</span>
                            </button>
                        </form>

                        <p class="text-[10px] text-gray-400 text-center mt-3">
                            <i class="fas fa-shield-alt mr-1"></i> SSL Secured transaction powered by Paystack.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('toggle_coupon_btn');
    const couponContainer = document.getElementById('coupon_container');
    const couponChevron = document.getElementById('coupon_chevron');
    const couponToggleLabel = document.getElementById('coupon_toggle_label');
    const couponInput = document.getElementById('coupon_input');
    const applyBtn = document.getElementById('apply_coupon_btn');
    const couponMsg = document.getElementById('coupon_msg');
    const discountRow = document.getElementById('discount_row');
    const discountVal = document.getElementById('discount_val');
    const totalVal = document.getElementById('total_val');
    const appliedCouponInput = document.getElementById('applied_coupon_code');
    const checkoutBtnText = document.getElementById('checkout_btn_text');

    // Toggle Coupon Drawer
    toggleBtn.addEventListener('click', function () {
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

    applyBtn.addEventListener('click', function () {
        const code = couponInput.value.trim();
        if (code === '') {
            showMsg('Please enter a coupon code.', 'text-red-500');
            return;
        }

        applyBtn.disabled = true;
        applyBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Applying';

        fetch('{{ route("courses.checkout.coupon", $course) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ coupon_code: code })
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(res => {
            applyBtn.disabled = false;
            applyBtn.innerHTML = 'Apply';

            if (res.status === 200) {
                const data = res.body;
                showMsg(data.message, 'text-green-600');
                appliedCouponInput.value = code;

                // Format values
                discountVal.textContent = '-₦' + parseFloat(data.discount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                totalVal.textContent = '₦' + parseFloat(data.final_price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                discountRow.classList.remove('hidden');

                if (parseFloat(data.final_price) <= 0) {
                    checkoutBtnText.textContent = 'Enroll Instantly (Free)';
                } else {
                    checkoutBtnText.textContent = 'Proceed to Payment';
                }
            } else {
                showMsg(res.body.message || 'Error applying coupon.', 'text-red-500');
                resetSummary();
            }
        })
        .catch(err => {
            applyBtn.disabled = false;
            applyBtn.innerHTML = 'Apply';
            showMsg('Unable to verify coupon code.', 'text-red-500');
            resetSummary();
        });
    });

    function showMsg(text, className) {
        couponMsg.textContent = text;
        couponMsg.className = 'text-xs font-semibold mt-2 ' + className;
        couponMsg.classList.remove('hidden');
    }

    function resetSummary() {
        discountRow.classList.add('hidden');
        appliedCouponInput.value = '';
        totalVal.textContent = '₦{{ number_format($course->price, 2) }}';
        checkoutBtnText.textContent = 'Proceed to Payment';
    }
});
</script>
@endsection
