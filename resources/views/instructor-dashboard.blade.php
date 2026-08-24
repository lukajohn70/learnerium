@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900">Instructor Dashboard</h1>
        <p class="text-gray-500 mt-1">Welcome, {{ Auth::user()->name }}! Manage your courses and students.</p>
    </div>

    @if(session('status'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl text-sm font-medium flex items-center gap-2">
            <i class="fas fa-check-circle text-emerald-500"></i>{{ session('status') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-2xl text-sm font-medium flex items-center gap-2">
            <i class="fas fa-exclamation-circle text-rose-500"></i>{{ session('error') }}
        </div>
    @endif

    <!-- Earnings & Payout Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
        {{-- Total Earned --}}
        <div class="bg-gradient-to-br from-primary-jlm to-indigo-900 text-white rounded-3xl p-6 shadow-md flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between opacity-80 mb-2">
                    <span class="text-xs uppercase font-bold tracking-wider">Total Sales Share</span>
                    <i class="fas fa-wallet text-xl text-accent-jlm"></i>
                </div>
                <div class="text-3xl font-black">₦{{ number_format($totalEarned, 2) }}</div>
                <p class="text-xs text-white/70 mt-1">Calculated based on your 70% platform share.</p>
            </div>
            <div class="mt-4 pt-4 border-t border-white/10 flex items-center justify-between text-xs">
                <span>Pending Balance:</span>
                <span class="font-extrabold text-accent-jlm text-sm">₦{{ number_format($pendingPayout, 2) }}</span>
            </div>
        </div>

        {{-- Payout Action Card --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs uppercase font-bold tracking-wider text-gray-400">Payout Status</span>
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $pendingPayout > 0 ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                        {{ $pendingPayout > 0 ? 'Payout Available' : 'Settled' }}
                    </span>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Request Withdrawal</h3>
                <p class="text-xs text-gray-500 mt-1">
                    @if($user->payout_requested_at)
                        <span class="text-primary-jlm font-semibold"><i class="fas fa-clock mr-1"></i>Requested on {{ \Carbon\Carbon::parse($user->payout_requested_at)->format('d M Y, h:i A') }}</span>
                    @else
                        Click below to notify administration to process your available balance.
                    @endif
                </p>
            </div>
            <form action="{{ route('instructor.payout.request') }}" method="POST" class="mt-4">
                @csrf
                <button type="submit" {{ $pendingPayout <= 0 ? 'disabled' : '' }} class="w-full bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold py-2.5 px-4 rounded-xl text-xs transition shadow-sm flex items-center justify-center gap-2">
                    <i class="fas fa-money-bill-wave"></i> Request Payout (₦{{ number_format($pendingPayout, 2) }})
                </button>
            </form>
        </div>

        {{-- Bank Details Card with Instant NIBSS/Paystack Verification --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold text-gray-900 mb-1 flex items-center justify-between">
                    <span class="flex items-center gap-2"><i class="fas fa-university text-primary-jlm"></i> Payout Bank Details</span>
                    <span class="text-[10px] text-emerald-700 bg-emerald-50 font-bold px-2 py-0.5 rounded-full border border-emerald-100">
                        <i class="fas fa-bolt mr-0.5"></i> Instant Verification
                    </span>
                </h3>
                <p class="text-[11px] text-gray-400 mb-3">Verified automatically via Central Bank / NIBSS network.</p>

                <form id="bankDetailsForm" action="{{ route('instructor.bank-details.update') }}" method="POST" class="space-y-2.5">
                    @csrf
                    <input type="hidden" name="bank_name" id="bankNameInput" value="{{ old('bank_name', $user->bank_name) }}">
                    <input type="hidden" name="bank_code" id="bankCodeInput" value="{{ old('bank_code', $user->bank_code) }}">

                    {{-- Bank Select Dropdown --}}
                    <div>
                        <select id="bankSelect" required class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-jlm/30 font-medium">
                            <option value="">-- Select Your Bank --</option>
                            @if($user->bank_code && $user->bank_name)
                                <option value="{{ $user->bank_code }}" selected>{{ $user->bank_name }}</option>
                            @endif
                        </select>
                    </div>

                    {{-- Account Number Input --}}
                    <div>
                        <input type="text" name="account_number" id="accountNumberInput" maxlength="10" placeholder="10-Digit Account Number" value="{{ old('account_number', $user->account_number) }}" required
                               class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl px-3 py-2.5 font-mono tracking-wider focus:outline-none focus:ring-2 focus:ring-primary-jlm/30">
                    </div>

                    {{-- Live Account Resolution Status Badge --}}
                    <div id="verifyStatusBox" class="text-[11px] min-h-[22px] flex items-center">
                        @if($user->account_name)
                            <span class="text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md font-bold text-[11px] flex items-center gap-1 border border-emerald-100">
                                <i class="fas fa-check-circle text-emerald-600"></i> {{ $user->account_name }}
                            </span>
                        @else
                            <span class="text-gray-400 italic">Select bank & enter 10 digits to verify</span>
                        @endif
                    </div>

                    {{-- Account Name Input (Auto-populated from Paystack) --}}
                    <div>
                        <input type="text" name="account_name" id="accountNameInput" placeholder="Account Name (Auto-resolved)" value="{{ old('account_name', $user->account_name) }}" required readonly
                               class="w-full bg-gray-100 border border-gray-200 text-gray-700 font-semibold text-xs rounded-xl px-3 py-2.5 focus:outline-none cursor-not-allowed">
                    </div>

                    <button type="submit" id="saveBankBtn" class="w-full bg-gray-900 hover:bg-black text-white text-xs font-bold py-2.5 rounded-xl transition shadow-sm">
                        <i class="fas fa-save mr-1.5"></i>Save Verified Bank Info
                    </button>
                </form>
            </div>
        </div>

    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 border-l-4 border-primary-jlm hover:shadow-md transition">
            <div class="text-3xl font-extrabold text-primary-jlm mb-1">{{ $courses->count() }}</div>
            <div class="text-xs text-gray-500 font-medium"><i class="fas fa-book mr-1"></i>Courses Created</div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 border-l-4 border-secondary-jlm hover:shadow-md transition">
            <div class="text-3xl font-extrabold text-secondary-jlm mb-1">{{ $totalStudents }}</div>
            <div class="text-xs text-gray-500 font-medium"><i class="fas fa-users mr-1"></i>Total Students</div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 border-l-4 border-accent-jlm hover:shadow-md transition">
            <div class="text-3xl font-extrabold text-gray-900 mb-1">
                {{ $pendingSubmissionsCount }}
            </div>
            <div class="text-xs text-gray-500 font-medium"><i class="fas fa-tasks mr-1 text-primary-jlm"></i>Pending Submissions</div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 border-l-4 border-green-500 hover:shadow-md transition">
            <div class="text-3xl font-extrabold text-green-500 mb-1">
                {{ $courses->sum(fn($c) => $c->lessons->count()) }}
            </div>
            <div class="text-xs text-gray-500 font-medium"><i class="fas fa-list-ul mr-1"></i>Total Lessons</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5 mb-10">
        <a href="{{ route('instructor.courses.create') }}" class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 border-t-4 border-primary-jlm flex flex-col items-center text-center hover:shadow-lg transition duration-300 transform hover:-translate-y-1 group">
            <div class="text-primary-jlm mb-3 text-3xl group-hover:scale-110 transition-transform duration-300"><i class="fas fa-plus-circle"></i></div>
            <h3 class="text-sm font-bold text-gray-900 mb-1">Create Course</h3>
            <p class="text-gray-400 text-xs mb-3">Publish new syllabus.</p>
            <span class="mt-auto inline-block bg-primary-jlm text-white px-3 py-1 rounded-xl font-semibold text-xs group-hover:bg-primary-jlm-dark transition">Start Now</span>
        </a>

        <a href="{{ route('instructor.manage.courses') }}" class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 border-t-4 border-secondary-jlm flex flex-col items-center text-center hover:shadow-lg transition duration-300 transform hover:-translate-y-1 group">
            <div class="text-secondary-jlm mb-3 text-3xl group-hover:scale-110 transition-transform duration-300"><i class="fas fa-folder-open"></i></div>
            <h3 class="text-sm font-bold text-gray-900 mb-1">Manage Courses</h3>
            <p class="text-gray-400 text-xs mb-3">Edit lessons & modules.</p>
            <span class="mt-auto inline-block bg-secondary-jlm text-white px-3 py-1 rounded-xl font-semibold text-xs group-hover:bg-secondary-jlm/90 transition">View Courses</span>
        </a>

        <a href="{{ route('instructor.submissions') }}" class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 border-t-4 border-accent-jlm flex flex-col items-center text-center hover:shadow-lg transition duration-300 transform hover:-translate-y-1 group relative">
            @if($pendingSubmissionsCount > 0)
                <span class="absolute top-2.5 right-2.5 bg-secondary-jlm text-white text-[10px] font-black px-2 py-0.5 rounded-full">{{ $pendingSubmissionsCount }} new</span>
            @endif
            <div class="text-accent-jlm mb-3 text-3xl group-hover:scale-110 transition-transform duration-300"><i class="fas fa-clipboard-check text-primary-jlm"></i></div>
            <h3 class="text-sm font-bold text-gray-900 mb-1">Grading Queue</h3>
            <p class="text-gray-400 text-xs mb-3">Review submissions.</p>
            <span class="mt-auto inline-block bg-primary-jlm text-white px-3 py-1 rounded-xl font-semibold text-xs group-hover:bg-primary-jlm-dark transition">Open Queue</span>
        </a>

        <a href="{{ route('instructor.coupons.index') }}" class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 border-t-4 border-emerald-500 flex flex-col items-center text-center hover:shadow-lg transition duration-300 transform hover:-translate-y-1 group">
            <div class="text-emerald-500 mb-3 text-3xl group-hover:scale-110 transition-transform duration-300"><i class="fas fa-tags"></i></div>
            <h3 class="text-sm font-bold text-gray-900 mb-1">Discount Coupons</h3>
            <p class="text-gray-400 text-xs mb-3">Create promo codes.</p>
            <span class="mt-auto inline-block bg-emerald-600 text-white px-3 py-1 rounded-xl font-semibold text-xs group-hover:bg-emerald-700 transition">Coupons</span>
        </a>

        <a href="{{ url('/profile') }}" class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 border-t-4 border-blue-400 flex flex-col items-center text-center hover:shadow-lg transition duration-300 transform hover:-translate-y-1 group">
            <div class="text-blue-400 mb-3 text-3xl group-hover:scale-110 transition-transform duration-300"><i class="fas fa-user-cog"></i></div>
            <h3 class="text-sm font-bold text-gray-900 mb-1">Profile & Settings</h3>
            <p class="text-gray-400 text-xs mb-3">Instructor settings.</p>
            <span class="mt-auto inline-block bg-blue-500 text-white px-3 py-1 rounded-xl font-semibold text-xs group-hover:bg-blue-600 transition">Edit Profile</span>
        </a>
    </div>

    {{-- Pending Payments / Reminders Card --}}
    @if(isset($pendingEnrollments) && $pendingEnrollments->count() > 0)
    <div class="bg-white rounded-2xl shadow-sm border border-amber-200 overflow-hidden mb-10">
        <div class="bg-amber-50/70 border-b border-amber-100 px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2.5">
                <i class="fas fa-clock text-amber-500 text-lg"></i>
                <div>
                    <h2 class="text-sm font-extrabold text-amber-900">Pending Student Checkout & Enrollments</h2>
                    <p class="text-[11px] text-amber-700">Students who started enrolling in your courses but haven't completed checkout.</p>
                </div>
            </div>
            <span class="bg-amber-100 text-amber-800 text-xs font-bold px-3 py-1 rounded-full">
                {{ $pendingEnrollments->count() }} Incomplete
            </span>
        </div>
        <div class="divide-y divide-gray-100">
            @foreach($pendingEnrollments as $pending)
            <div class="px-6 py-3.5 flex items-center justify-between hover:bg-amber-50/30 transition flex-wrap gap-3 text-xs">
                <div>
                    <span class="font-bold text-gray-900">{{ $pending->user->name ?? 'Student' }}</span>
                    <span class="text-gray-400">({{ $pending->user->email ?? '—' }})</span>
                    <span class="text-gray-400 mx-1">&bull;</span>
                    <span class="font-medium text-gray-700">{{ Str::limit($pending->course->title ?? '', 35) }}</span>
                </div>
                <div class="flex items-center gap-3 ml-auto">
                    <span class="font-bold text-amber-700">₦{{ number_format($pending->amount_paid ?? 0, 2) }}</span>
                    <span class="text-gray-400">{{ $pending->created_at->diffForHumans() }}</span>
                    <form action="{{ route('enrollment.remind', $pending) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-3 py-1 rounded-lg text-xs transition shadow-sm flex items-center gap-1">
                            <i class="fas fa-bell"></i> Send Reminder
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- My Courses List -->
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <div class="bg-gray-50 border-b border-gray-100 px-6 py-4 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-book-open mr-2 text-primary-jlm"></i>My Courses</h2>
            <a href="{{ route('instructor.courses.create') }}" class="bg-primary-jlm text-white px-4 py-2 rounded-xl font-semibold text-sm hover:bg-primary-jlm-dark transition">
                <i class="fas fa-plus mr-1"></i>New Course
            </a>
        </div>


        @if($courses->isEmpty())
            <div class="p-12 text-center text-gray-400">
                <div class="text-5xl mb-4"><i class="fas fa-book-open"></i></div>
                <p class="text-lg font-semibold mb-2">No courses yet.</p>
                <p class="text-sm mb-4">Create your first course to start teaching.</p>
                <a href="{{ route('instructor.courses.create') }}" class="bg-primary-jlm text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-primary-jlm-dark transition text-sm">
                    Create Course
                </a>
            </div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($courses as $course)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition flex-wrap gap-3">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
                            <img src="{{ $course->thumbnailUrl() }}" 
                                 alt="{{ $course->title }}" class="w-full h-full object-cover">
                        </div>

                        <div>
                            <p class="font-semibold text-gray-800">{{ $course->title }}</p>
                            <p class="text-sm text-gray-400">
                                <span class="mr-3"><i class="fas fa-users mr-1"></i>{{ $course->enrollments->count() }} students</span>
                                <span><i class="fas fa-list-ul mr-1"></i>{{ $course->lessons->count() }} lessons</span>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 ml-auto">
                        <a href="{{ route('instructor.courses.edit', $course) }}" class="bg-secondary-jlm text-white px-4 py-1.5 rounded-lg text-xs font-semibold hover:bg-secondary-jlm/90 transition">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                        <a href="{{ route('instructor.courses.students', $course) }}" class="border border-primary-jlm text-primary-jlm px-4 py-1.5 rounded-lg text-xs font-semibold hover:bg-primary-jlm/5 transition">
                            <i class="fas fa-users mr-1"></i>Students
                        </a>
                        <a href="{{ route('course.detail', $course->slug) }}" class="border border-gray-300 text-gray-600 px-4 py-1.5 rounded-lg text-xs font-semibold hover:bg-gray-100 transition">
                            <i class="fas fa-eye mr-1"></i>View
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- Bank Resolution Real-Time Script --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bankSelect     = document.getElementById('bankSelect');
    const bankNameInput  = document.getElementById('bankNameInput');
    const bankCodeInput  = document.getElementById('bankCodeInput');
    const accNumInput    = document.getElementById('accountNumberInput');
    const accNameInput   = document.getElementById('accountNameInput');
    const statusBox      = document.getElementById('verifyStatusBox');
    const saveBtn        = document.getElementById('saveBankBtn');

    if (!bankSelect || !accNumInput) return;

    const currentBankCode = "{{ $user->bank_code ?? '' }}";

    // 1. Fetch live bank list from Paystack
    fetch("{{ route('api.banks') }}")
        .then(r => r.json())
        .then(data => {
            if (data.success && data.banks) {
                bankSelect.innerHTML = '<option value="">-- Select Your Bank --</option>';
                data.banks.forEach(bank => {
                    const opt = document.createElement('option');
                    opt.value = bank.code;
                    opt.textContent = bank.name;
                    opt.dataset.name = bank.name;
                    if (bank.code === currentBankCode) {
                        opt.selected = true;
                    }
                    bankSelect.appendChild(opt);
                });
            }
        })
        .catch(() => {});

    // 2. Real-time Account Resolver
    let debounceTimer = null;

    function verifyAccount() {
        const selectedOpt = bankSelect.options[bankSelect.selectedIndex];
        const bankCode    = selectedOpt ? selectedOpt.value : '';
        const bankName    = selectedOpt ? (selectedOpt.dataset.name || selectedOpt.textContent) : '';
        const accNum      = accNumInput.value.trim();

        if (bankCode) {
            bankCodeInput.value = bankCode;
            bankNameInput.value = bankName;
        }

        if (accNum.length !== 10 || !bankCode) {
            if (accNum.length > 0 && accNum.length < 10) {
                statusBox.innerHTML = `<span class="text-gray-400 italic">Enter full 10-digit account number (${accNum.length}/10)</span>`;
            }
            return;
        }

        // Show verifying animation
        statusBox.innerHTML = `
            <span class="text-blue-600 font-semibold text-[11px] flex items-center gap-1.5 animate-pulse">
                <i class="fas fa-circle-notch fa-spin text-blue-500"></i> Verifying with ${bankName}...
            </span>`;
        saveBtn.disabled = true;

        fetch("{{ route('api.banks.resolve') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                account_number: accNum,
                bank_code: bankCode
            })
        })
        .then(r => r.json())
        .then(res => {
            saveBtn.disabled = false;
            if (res.success && res.account_name) {
                accNameInput.value = res.account_name;
                statusBox.innerHTML = `
                    <span class="text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md font-bold text-[11px] flex items-center gap-1 border border-emerald-100">
                        <i class="fas fa-check-circle text-emerald-600"></i> Verified: ${res.account_name}
                    </span>`;
            } else {
                accNameInput.value = '';
                statusBox.innerHTML = `
                    <span class="text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md font-semibold text-[11px] flex items-center gap-1 border border-rose-100">
                        <i class="fas fa-times-circle text-rose-500"></i> ${res.message || 'Account verification failed.'}
                    </span>`;
            }
        })
        .catch(() => {
            saveBtn.disabled = false;
            statusBox.innerHTML = `<span class="text-rose-500 text-[11px]">Network verification error. Please try again.</span>`;
        });
    }

    accNumInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(verifyAccount, 400);
    });

    bankSelect.addEventListener('change', function() {
        if (accNumInput.value.trim().length === 10) {
            verifyAccount();
        }
    });
});
</script>
@endsection

