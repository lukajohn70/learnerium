@extends('layouts.app')

@section('title', 'Admin Dashboard — Learnerium')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- JLM Admin Top Header Banner -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-800 to-pink-600 rounded-xl flex items-center justify-center text-white shadow-md">
                    <i class="fas fa-shield-alt text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900">Admin Dashboard</h1>
                    <p class="text-xs text-gray-500 font-medium">Learnerium Control Panel &bull; Powered by JLM</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right text-xs">
                    <p class="font-extrabold text-gray-800">{{ Auth::user()->name }}</p>
                    <span class="bg-pink-100 text-pink-700 px-2 py-0.5 rounded-full text-[10px] font-bold">Administrator</span>
                </div>
            </div>
        </div>

        @if(session('status'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                <i class="fas fa-check-circle text-emerald-500"></i>{{ session('status') }}
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-6">

            <!-- Sidebar Navigation (JLM Style) -->
            <aside class="w-full lg:w-64 flex-shrink-0">
                <nav class="bg-white rounded-2xl shadow-sm border border-gray-200 p-2 flex lg:flex-col flex-row gap-1.5 overflow-x-auto">
                    @foreach([
                        ['overview', 'fa-tachometer-alt', 'Overview'],
                        ['users', 'fa-users', 'Users'],
                        ['courses', 'fa-book-open', 'Courses'],
                        ['coupons', 'fa-ticket-alt', 'Coupons'],
                        ['payments', 'fa-credit-card', 'Payments'],
                        ['payouts', 'fa-wallet', 'Payouts'],
                        ['applications', 'fa-user-check', 'Instructor Apps'],
                        ['mailer', 'fa-paper-plane', 'Mailer & Inbox'],
                        ['settings', 'fa-sliders-h', 'Settings'],

                    ] as [$tab, $icon, $label])
                    <button onclick="switchTab('{{ $tab }}')" id="tab-btn-{{ $tab }}"
                        class="tab-btn flex items-center gap-2.5 px-4 py-3 text-sm font-bold rounded-xl whitespace-nowrap transition-all duration-200 min-w-[9rem] lg:min-w-0
                        {{ $tab === 'overview' ? 'bg-gradient-to-r from-blue-800 to-pink-600 text-white shadow-md' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas {{ $icon }} text-base"></i> {{ $label }}
                    </button>
                    @endforeach
                </nav>
            </aside>

            <!-- Main Content Area -->
            <main class="flex-1 min-w-0">

                <!-- ============= OVERVIEW TAB ============= -->
                <div id="tab-overview" class="tab-panel space-y-6">

                    <!-- Stat Cards Grid (JLM Gradient Style) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                        <div class="bg-gradient-to-br from-blue-700 to-blue-500 text-white p-6 rounded-2xl shadow-sm border border-blue-600/20">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold uppercase tracking-wider text-blue-100">Total Users</span>
                                <i class="fas fa-users text-xl text-blue-200"></i>
                            </div>
                            <div class="text-3xl font-black">{{ number_format($stats['total_users']) }}</div>
                            <p class="text-[11px] text-blue-100 mt-1">{{ $stats['total_students'] }} Students &bull; {{ $stats['total_instructors'] }} Instructors</p>
                        </div>

                        <div class="bg-gradient-to-br from-pink-600 to-pink-400 text-white p-6 rounded-2xl shadow-sm border border-pink-500/20">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold uppercase tracking-wider text-pink-100">Courses</span>
                                <i class="fas fa-book-open text-xl text-pink-200"></i>
                            </div>
                            <div class="text-3xl font-black">{{ number_format($stats['total_courses']) }}</div>
                            <p class="text-[11px] text-pink-100 mt-1">{{ $stats['published_courses'] }} Published Courses</p>
                        </div>

                        <div class="bg-gradient-to-br from-emerald-600 to-teal-400 text-white p-6 rounded-2xl shadow-sm border border-emerald-500/20">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold uppercase tracking-wider text-emerald-100">Paid Enrollments</span>
                                <i class="fas fa-graduation-cap text-xl text-emerald-200"></i>
                            </div>
                            <div class="text-3xl font-black">{{ number_format($stats['paid_enrollments']) }}</div>
                            <p class="text-[11px] text-emerald-100 mt-1">Out of {{ $stats['total_enrollments'] }} Total</p>
                        </div>

                        <div class="bg-gradient-to-br from-amber-500 to-yellow-400 text-white p-6 rounded-2xl shadow-sm border border-amber-400/20">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold uppercase tracking-wider text-amber-100">Total Revenue</span>
                                <i class="fas fa-coins text-xl text-amber-200"></i>
                            </div>
                            <div class="text-3xl font-black">₦{{ number_format($stats['total_revenue'], 2) }}</div>
                            <p class="text-[11px] text-amber-100 mt-1">Paystack Online Payments</p>
                        </div>
                    </div>

                    {{-- Revenue Split Summary --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-university text-emerald-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Platform Net</p>
                                <p class="text-xl font-black text-gray-900">₦{{ number_format($stats['platform_revenue'], 2) }}</p>
                            </div>
                        </div>
                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-chalkboard-teacher text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Instructor Earnings</p>
                                <p class="text-xl font-black text-gray-900">₦{{ number_format($stats['instructor_payouts'], 2) }}</p>
                            </div>
                        </div>
                        <div class="bg-white rounded-2xl border border-orange-100 shadow-sm p-5 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-clock text-orange-500"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Pending Payouts</p>
                                <p class="text-xl font-black text-orange-600">₦{{ number_format($stats['pending_payouts'], 2) }}</p>
                                <button onclick="switchTab('payouts')" class="text-[10px] text-orange-500 hover:underline font-bold">View Payouts →</button>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Users & Recent Enrollments -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                        <!-- Recent Users -->
                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                                    <i class="fas fa-user-plus text-blue-600"></i> Recent Users
                                </h3>
                                <button onclick="switchTab('users')" class="text-xs text-blue-600 font-bold hover:underline">View All</button>
                            </div>
                            <div class="divide-y divide-gray-100">
                                @foreach($recentUsers as $u)
                                <div class="px-6 py-3.5 flex items-center justify-between gap-3 hover:bg-gray-50 transition">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <img src="{{ $u->avatarUrl() }}" class="w-9 h-9 rounded-full object-cover border border-gray-200 flex-shrink-0">
                                        <div class="min-w-0">
                                            <p class="text-gray-900 text-xs font-bold truncate">{{ $u->name }}</p>
                                            <p class="text-gray-500 text-[11px] truncate">{{ $u->email }}</p>
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full capitalize
                                        {{ $u->role === 'admin' ? 'bg-red-100 text-red-700' : ($u->role === 'instructor' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700') }}">
                                        {{ $u->role }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Recent Enrollments -->
                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                                    <i class="fas fa-shopping-cart text-pink-600"></i> Recent Enrollments
                                </h3>
                                <button onclick="switchTab('payments')" class="text-xs text-pink-600 font-bold hover:underline">View All</button>
                            </div>
                            <div class="divide-y divide-gray-100">
                                @foreach($recentEnrolls->take(5) as $enroll)
                                <div class="px-6 py-3.5 flex items-center justify-between gap-3 hover:bg-gray-50 transition">
                                    <div class="min-w-0">
                                        <p class="text-gray-900 text-xs font-bold truncate">{{ $enroll->user->name ?? 'Unknown' }}</p>
                                        <p class="text-gray-500 text-[11px] truncate">{{ $enroll->course->title ?? 'Unknown Course' }}</p>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <span class="block text-[10px] font-bold {{ $enroll->payment_status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                                            {{ ucfirst($enroll->payment_status ?? 'pending') }}
                                        </span>
                                        <span class="text-gray-600 text-[11px] font-bold">₦{{ number_format($enroll->amount_paid ?? 0, 2) }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ============= USERS TAB ============= -->
                <div id="tab-users" class="tab-panel hidden space-y-4">
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h2 class="font-bold text-gray-800 text-base"><i class="fas fa-users mr-2 text-blue-600"></i>User Management</h2>
                            <a href="{{ route('admin.users') }}" class="text-xs font-bold text-blue-600 hover:underline">Full Page &rarr;</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs">
                                <thead class="bg-gray-50 text-gray-600 uppercase font-semibold">
                                    <tr>
                                        <th class="px-6 py-3.5 text-left">User</th>
                                        <th class="px-6 py-3.5 text-left">Email</th>
                                        <th class="px-6 py-3.5 text-left">Role</th>
                                        <th class="px-6 py-3.5 text-left">Joined</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($recentUsers as $u)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-3.5">
                                            <div class="flex items-center gap-3">
                                                <img src="{{ $u->avatarUrl() }}" class="w-8 h-8 rounded-full object-cover border border-gray-200">
                                                <span class="font-bold text-gray-900">{{ $u->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3.5 text-gray-600">{{ $u->email }}</td>
                                        <td class="px-6 py-3.5">
                                            <form action="{{ route('admin.users.role', $u) }}" method="POST" class="flex items-center gap-2">
                                                @csrf
                                                <select name="role" class="bg-gray-50 border border-gray-300 text-gray-800 text-xs rounded-lg px-2.5 py-1 font-semibold focus:outline-none">
                                                    @foreach(['student','instructor','admin'] as $r)
                                                        <option value="{{ $r }}" {{ $u->role === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-2.5 py-1 rounded-lg text-[10px] font-bold transition">Save</button>
                                            </form>
                                        </td>
                                        <td class="px-6 py-3.5 text-gray-500">{{ $u->created_at->format('d M Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ============= COURSES TAB ============= -->
                <div id="tab-courses" class="tab-panel hidden space-y-4">
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h2 class="font-bold text-gray-800 text-base"><i class="fas fa-book-open mr-2 text-pink-600"></i>Course Management</h2>
                            <a href="{{ route('admin.courses') }}" class="text-xs font-bold text-pink-600 hover:underline">Full Page &rarr;</a>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach($recentCourses as $c)
                            <div class="px-6 py-4 flex items-center justify-between gap-4 hover:bg-gray-50 transition">
                                <div class="flex items-center gap-3 min-w-0">
                                    <img src="{{ $c->thumbnailUrl() }}" class="w-14 h-10 object-cover rounded-xl border border-gray-200 flex-shrink-0">
                                    <div class="min-w-0">
                                        <p class="text-gray-900 text-sm font-bold truncate">{{ $c->title }}</p>
                                        <p class="text-gray-500 text-xs">by {{ $c->instructor->name ?? 'Instructor' }} &bull; ₦{{ number_format($c->price, 2) }} &bull; {{ $c->enrollments->count() }} enrolled</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <span class="text-[10px] px-2.5 py-0.5 rounded-full font-bold {{ $c->published_at ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $c->published_at ? 'Published' : 'Draft' }}
                                    </span>
                                    <form action="{{ route('admin.courses.toggle', $c) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-1.5 rounded-xl font-bold transition">
                                            {{ $c->published_at ? 'Unpublish' : 'Publish' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- ============= COUPONS TAB ============= -->
                <div id="tab-coupons" class="tab-panel hidden space-y-4">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-4">
                            <h3 class="font-extrabold text-gray-900 text-base flex items-center gap-2">
                                <i class="fas fa-plus-circle text-pink-600"></i> New Coupon
                            </h3>
                            <form action="{{ route('admin.coupons.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Coupon Code</label>
                                    <input type="text" name="code" required placeholder="e.g. LEARN50"
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
                                    <input type="number" name="discount_value" required min="1" step="0.01" placeholder="e.g. 50"
                                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-xl px-3 py-2 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-pink-500/30">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Applicable Course</label>
                                    <select name="course_id" class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-xl px-3 py-2 text-sm font-semibold focus:outline-none">
                                        <option value="">All Courses (Global)</option>
                                        @foreach($recentCourses as $c)
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
                        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col justify-between">
                            <div>
                                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                    <h3 class="font-extrabold text-gray-900 text-base flex items-center gap-2">
                                        <i class="fas fa-ticket-alt text-amber-500"></i> Active Coupons
                                    </h3>
                                    <a href="{{ route('admin.coupons') }}" class="text-xs text-blue-600 font-bold hover:underline">Manage All Coupons &rarr;</a>
                                </div>
                                <div class="divide-y divide-gray-100">
                                    @forelse($recentCoupons as $coupon)
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
                                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Delete coupon?')">
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

                <!-- ============= PAYMENTS TAB ============= -->
                <div id="tab-payments" class="tab-panel hidden space-y-4">
                    {{-- Pending Payments (Reminder) Section --}}
                    @if($pendingEnrollments->count() > 0)
                    <div class="bg-white rounded-2xl border border-amber-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-amber-100 flex items-center justify-between bg-amber-50/50">
                            <div>
                                <h2 class="font-bold text-amber-900 text-base flex items-center gap-2"><i class="fas fa-clock text-amber-500"></i> Pending Payments</h2>
                                <p class="text-xs text-amber-700 mt-0.5">Students who started checkout but haven't completed payment.</p>
                            </div>
                            <span class="bg-amber-100 text-amber-800 text-xs font-bold px-3 py-1.5 rounded-xl">{{ $pendingEnrollments->count() }} Pending</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs">
                                <thead class="bg-gray-50 text-gray-600 uppercase font-semibold">
                                    <tr>
                                        <th class="px-5 py-3.5 text-left">Student</th>
                                        <th class="px-5 py-3.5 text-left">Course</th>
                                        <th class="px-5 py-3.5 text-left">Amount</th>
                                        <th class="px-5 py-3.5 text-left">Started</th>
                                        <th class="px-5 py-3.5 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($pendingEnrollments as $pending)
                                    <tr class="hover:bg-amber-50/30 transition">
                                        <td class="px-5 py-3.5">
                                            <p class="font-bold text-gray-900">{{ $pending->user->name ?? '—' }}</p>
                                            <p class="text-gray-400">{{ $pending->user->email ?? '' }}</p>
                                        </td>
                                        <td class="px-5 py-3.5 text-gray-700 font-medium">{{ Str::limit($pending->course->title ?? '—', 35) }}</td>
                                        <td class="px-5 py-3.5 font-extrabold text-amber-700">₦{{ number_format($pending->amount_paid ?? 0, 2) }}</td>
                                        <td class="px-5 py-3.5 text-gray-500">{{ $pending->created_at->format('d M Y') }}</td>
                                        <td class="px-5 py-3.5 text-center">
                                            <form action="{{ route('enrollment.remind', $pending) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold px-3.5 py-1.5 rounded-xl transition shadow-sm flex items-center gap-1.5 mx-auto"
                                                        onclick="return confirm('Send payment reminder to {{ addslashes($pending->user->name ?? '') }}?')">
                                                    <i class="fas fa-bell"></i> Send Reminder
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    {{-- Completed Payments --}}
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h2 class="font-bold text-gray-800 text-base"><i class="fas fa-credit-card mr-2 text-emerald-600"></i>Completed Payments & Enrollments</h2>
                            <a href="{{ route('admin.payments') }}" class="text-xs font-bold text-emerald-600 hover:underline">Full Page &rarr;</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs">
                                <thead class="bg-gray-50 text-gray-600 uppercase font-semibold">
                                    <tr>
                                        <th class="px-6 py-3.5 text-left">Student</th>
                                        <th class="px-6 py-3.5 text-left">Course</th>
                                        <th class="px-6 py-3.5 text-left">Amount</th>
                                        <th class="px-6 py-3.5 text-left">Status</th>
                                        <th class="px-6 py-3.5 text-left">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($recentEnrolls as $enroll)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-3.5 font-bold text-gray-900">{{ $enroll->user->name ?? '—' }}</td>
                                        <td class="px-6 py-3.5 text-gray-600">{{ Str::limit($enroll->course->title ?? '—', 35) }}</td>
                                        <td class="px-6 py-3.5 font-extrabold text-gray-900">₦{{ number_format($enroll->amount_paid ?? 0, 2) }}</td>
                                        <td class="px-6 py-3.5">
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $enroll->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                                {{ ucfirst($enroll->payment_status ?? 'pending') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3.5 text-gray-500">{{ $enroll->created_at->format('d M Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                <!-- ============= PAYOUTS TAB ============= -->
                <div id="tab-payouts" class="tab-panel hidden space-y-5">
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <div>
                                <h2 class="font-bold text-gray-800 text-base flex items-center gap-2"><i class="fas fa-wallet text-emerald-600"></i> Instructor Payouts Ledger</h2>
                                <p class="text-xs text-gray-500 mt-0.5">Track earnings owed to each instructor. Mark as paid once you've manually transferred funds.</p>
                            </div>
                            <span class="bg-orange-100 text-orange-700 text-xs font-bold px-3 py-1.5 rounded-xl">₦{{ number_format($stats['pending_payouts'], 2) }} Pending</span>
                        </div>

                        @if($instructorPayoutSummary->isEmpty())
                            <div class="p-12 text-center text-gray-400">
                                <i class="fas fa-wallet text-4xl mb-3 block text-gray-200"></i>
                                No instructor sales recorded yet.
                            </div>
                        @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs">
                                <thead class="bg-gray-50 text-gray-600 uppercase font-semibold">
                                    <tr>
                                        <th class="px-6 py-3.5 text-left">Instructor</th>
                                        <th class="px-6 py-3.5 text-left">Bank Account Details</th>
                                        <th class="px-6 py-3.5 text-right">Sales</th>
                                        <th class="px-6 py-3.5 text-right">Total Earned</th>
                                        <th class="px-6 py-3.5 text-right">Pending Payout</th>
                                        <th class="px-6 py-3.5 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($instructorPayoutSummary as $instructor)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <img src="{{ $instructor->avatarUrl() }}" class="w-9 h-9 rounded-full object-cover border border-gray-200">
                                                <div>
                                                    <p class="font-bold text-gray-900">{{ $instructor->name }}</p>
                                                    <p class="text-gray-400 text-[11px]">{{ $instructor->email }}</p>
                                                    @if($instructor->payout_requested_at)
                                                        <span class="inline-flex items-center gap-1 text-[10px] text-amber-700 bg-amber-100 font-bold px-2 py-0.5 rounded-full mt-1">
                                                            <i class="fas fa-bell animate-pulse"></i> Withdrawal Requested
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($instructor->bank_name && $instructor->account_number)
                                                <div class="space-y-0.5">
                                                    <p class="font-bold text-gray-900">{{ $instructor->bank_name }}</p>
                                                    <p class="font-mono text-xs text-primary-jlm font-bold tracking-wide">{{ $instructor->account_number }}</p>
                                                    <p class="text-[11px] text-gray-500 uppercase">{{ $instructor->account_name }}</p>
                                                </div>
                                            @else
                                                <span class="text-xs text-gray-400 italic"><i class="fas fa-exclamation-circle mr-1 text-amber-400"></i>No bank info added</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold text-gray-700">{{ number_format($instructor->sales_count) }}</td>
                                        <td class="px-6 py-4 text-right font-extrabold text-gray-900">₦{{ number_format($instructor->total_earned ?? 0, 2) }}</td>
                                        <td class="px-6 py-4 text-right">
                                            @if(($instructor->pending_payout ?? 0) > 0)
                                                <span class="font-extrabold text-orange-600">₦{{ number_format($instructor->pending_payout, 2) }}</span>
                                            @else
                                                <span class="text-emerald-600 font-bold"><i class="fas fa-check-circle mr-1"></i>Up to date</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if(($instructor->pending_payout ?? 0) > 0)
                                                <form action="{{ route('admin.payouts.mark-paid', $instructor) }}" method="POST" onsubmit="return confirm('Mark all pending earnings for {{ $instructor->name }} as paid?')">
                                                    @csrf
                                                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-1.5 rounded-xl text-[11px] font-bold transition shadow">
                                                        <i class="fas fa-check mr-1"></i>Mark Paid
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-300 text-[11px]">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- ============= INSTRUCTOR APPS TAB ============= -->
                <div id="tab-applications" class="tab-panel hidden space-y-4">
                    <div class="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm text-center">
                        <i class="fas fa-user-check text-4xl text-blue-600 mb-3 block"></i>
                        <h3 class="text-lg font-extrabold text-gray-900 mb-1">Instructor Applications</h3>
                        <p class="text-xs text-gray-500 mb-5">Review and approve instructor verification applications.</p>
                        <a href="{{ route('admin.instructor.applications') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-800 to-pink-600 text-white px-6 py-2.5 rounded-xl font-bold text-xs shadow hover:opacity-90 transition">
                            <i class="fas fa-external-link-alt"></i> Open Applications Page
                        </a>
                    </div>
                </div>

                <!-- ============= MAILER & INBOX TAB ============= -->
                <div id="tab-mailer" class="tab-panel hidden space-y-6">

                    <!-- Compose Broadcast Card -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
                        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                            <div>
                                <h2 class="text-lg font-extrabold text-gray-900 flex items-center gap-2">
                                    <i class="fas fa-paper-plane text-primary-jlm"></i> Compose & Broadcast Email
                                </h2>
                                <p class="text-xs text-gray-500 mt-0.5">Send custom announcements, notifications, or direct emails to platform users.</p>
                            </div>
                            <span class="bg-primary-jlm/10 text-primary-jlm text-xs font-bold px-3 py-1 rounded-full">
                                {{ $stats['total_users'] }} Total Registered Users
                            </span>
                        </div>

                        <form action="{{ route('admin.mailer.send') }}" method="POST" class="space-y-5" onsubmit="return confirm('Confirm sending this email broadcast?');">
                            @csrf

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Recipient Group <span class="text-red-500">*</span></label>
                                    <select name="recipient_type" id="recipientTypeSelect" required onchange="toggleSpecificUser(this.value)"
                                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-xl px-4 py-3 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-primary-jlm/30">
                                        <option value="all">📢 All Users (Students + Instructors + Admins) [{{ $stats['total_users'] }}]</option>
                                        <option value="students">🎓 All Students Only [{{ $stats['total_students'] }}]</option>
                                        <option value="instructors">👨‍🏫 All Instructors Only [{{ $stats['total_instructors'] }}]</option>
                                        <option value="specific">👤 Specific User...</option>
                                    </select>
                                </div>

                                <div id="specificUserWrap" class="hidden">
                                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Select User <span class="text-red-500">*</span></label>
                                    <select name="recipient_user_id" id="recipientUserSelect"
                                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-xl px-4 py-3 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-primary-jlm/30">
                                        <option value="">-- Choose User --</option>
                                        @foreach($allUsersList as $u)
                                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }}) [{{ ucfirst($u->role) }}]</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Subject Line <span class="text-red-500">*</span></label>
                                <input type="text" name="subject" required placeholder="e.g., Important Platform Update & New Features on Learnerium"
                                       class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-xl px-4 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-primary-jlm/30">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Email Message Body <span class="text-red-500">*</span></label>
                                <textarea name="message" rows="6" required placeholder="Write your message here... Recipients can reply directly to this email."
                                          class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-jlm/30 leading-relaxed"></textarea>
                            </div>

                            <div class="flex items-center justify-between pt-2">
                                <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-gray-700">
                                    <input type="checkbox" name="also_notify" value="1" checked class="rounded text-primary-jlm focus:ring-primary-jlm">
                                    <span>Also deliver as In-App Notification (Bell alert)</span>
                                </label>

                                <button type="submit" class="bg-gradient-to-r from-blue-800 to-pink-600 text-white px-7 py-3 rounded-xl font-extrabold text-xs shadow-md hover:opacity-90 transition flex items-center gap-2">
                                    <i class="fas fa-paper-plane"></i> Send Email
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Inbound Messages & Inquiries Inbox -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/60">
                            <div>
                                <h3 class="font-extrabold text-gray-900 text-base flex items-center gap-2">
                                    <i class="fas fa-inbox text-purple-600"></i> Inbound Messages & Student Replies
                                </h3>
                                <p class="text-xs text-gray-500">Messages sent by students and visitors via contact forms and email replies.</p>
                            </div>
                            <span class="bg-purple-100 text-purple-700 text-xs font-bold px-3 py-1 rounded-full">
                                {{ $inboundMessages->where('status', 'unread')->count() }} Unread
                            </span>
                        </div>

                        @if($inboundMessages->isEmpty())
                            <div class="p-12 text-center text-gray-400">
                                <i class="fas fa-inbox text-4xl mb-3 block text-gray-200"></i>
                                No incoming inquiries recorded yet.
                            </div>
                        @else
                            <div class="divide-y divide-gray-100">
                                @foreach($inboundMessages as $inbound)
                                <div class="p-6 hover:bg-gray-50 transition space-y-3">
                                    <div class="flex items-start justify-between gap-4 flex-wrap">
                                        <div>
                                            <div class="flex items-center gap-2.5">
                                                <span class="font-extrabold text-gray-900 text-sm">{{ $inbound->name }}</span>
                                                <span class="text-xs text-gray-400">&bull; {{ $inbound->email }}</span>
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $inbound->status === 'replied' ? 'bg-emerald-100 text-emerald-700' : 'bg-purple-100 text-purple-700' }}">
                                                    {{ ucfirst($inbound->status) }}
                                                </span>
                                            </div>
                                            <h4 class="font-bold text-primary-jlm text-xs mt-1">{{ $inbound->subject }}</h4>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs text-gray-400">{{ $inbound->created_at->diffForHumans() }}</span>
                                            <button type="button" onclick="toggleReplyBox('replyBox-{{ $inbound->id }}')"
                                                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-3 py-1 rounded-lg text-xs transition flex items-center gap-1">
                                                <i class="fas fa-reply text-purple-500"></i> Reply
                                            </button>
                                        </div>
                                    </div>

                                    <div class="bg-gray-50 rounded-xl p-3.5 text-xs text-gray-700 leading-relaxed border border-gray-100">
                                        {!! nl2br(e($inbound->message)) !!}
                                    </div>

                                    @if($inbound->admin_reply)
                                    <div class="bg-emerald-50 rounded-xl p-3.5 text-xs text-emerald-900 leading-relaxed border border-emerald-200">
                                        <span class="font-bold text-[10px] uppercase text-emerald-700 block mb-1">
                                            <i class="fas fa-check-circle mr-1"></i> Replied on {{ $inbound->replied_at ? $inbound->replied_at->format('d M Y, h:i A') : 'earlier' }}:
                                        </span>
                                        {!! nl2br(e($inbound->admin_reply)) !!}
                                    </div>
                                    @endif

                                    <!-- Expandable Reply Box -->
                                    <div id="replyBox-{{ $inbound->id }}" class="hidden pt-2">
                                        <form action="{{ route('admin.mailer.reply', $inbound) }}" method="POST" class="space-y-2.5">
                                            @csrf
                                            <textarea name="reply_text" rows="3" required placeholder="Type your reply to {{ $inbound->name }}..."
                                                      class="w-full bg-white border border-gray-300 rounded-xl p-3 text-xs focus:outline-none focus:ring-2 focus:ring-purple-500/30"></textarea>
                                            <div class="flex justify-end gap-2">
                                                <button type="button" onclick="toggleReplyBox('replyBox-{{ $inbound->id }}')"
                                                        class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-bold text-gray-600">Cancel</button>
                                                <button type="submit"
                                                        class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-1.5 rounded-lg text-xs font-bold shadow transition flex items-center gap-1">
                                                    <i class="fas fa-paper-plane"></i> Send Reply Email
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Recent Broadcasts History -->
                    @if($broadcastEmails->isNotEmpty())
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="font-extrabold text-gray-900 text-sm flex items-center gap-2">
                                <i class="fas fa-history text-gray-500"></i> Broadcast History
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs">
                                <thead class="bg-gray-50 text-gray-600 uppercase font-semibold">
                                    <tr>
                                        <th class="px-6 py-3 text-left">Subject</th>
                                        <th class="px-6 py-3 text-left">Audience</th>
                                        <th class="px-6 py-3 text-center">Sent Count</th>
                                        <th class="px-6 py-3 text-right">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($broadcastEmails as $bc)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-3 font-bold text-gray-900">{{ $bc->subject }}</td>
                                        <td class="px-6 py-3 capitalize text-gray-600">{{ $bc->recipient_type }}</td>
                                        <td class="px-6 py-3 text-center font-bold text-primary-jlm">{{ $bc->total_sent }}</td>
                                        <td class="px-6 py-3 text-right text-gray-400">{{ $bc->created_at->format('d M Y, h:i A') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                </div>

                <!-- ============= SETTINGS TAB ============= -->
                <div id="tab-settings" class="tab-panel hidden space-y-5">


                    @if($errors->has('revenue_split'))
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                            <i class="fas fa-exclamation-circle text-red-500"></i>{{ $errors->first('revenue_split') }}
                        </div>
                    @endif

                    <!-- Revenue Split Settings -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                        <h3 class="font-extrabold text-gray-900 text-base flex items-center gap-2 mb-1">
                            <i class="fas fa-chart-pie text-blue-600"></i> Revenue Split Configuration
                        </h3>
                        <p class="text-xs text-gray-500 mb-6">Define how course revenue is split between instructors and the platform. Must total 100%.</p>

                        <form action="{{ route('admin.settings.update') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1.5">
                                        <i class="fas fa-chalkboard-teacher mr-1 text-blue-500"></i> Instructor Share (%)
                                    </label>
                                    <input type="number" name="instructor_revenue_share" min="0" max="100" step="0.1" required
                                        value="{{ $platformSettings['instructor_revenue_share']->value ?? 70 }}"
                                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-xl px-4 py-3 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1.5">
                                        <i class="fas fa-university mr-1 text-emerald-500"></i> Platform Share (%)
                                    </label>
                                    <input type="number" name="platform_revenue_share" min="0" max="100" step="0.1" required
                                        value="{{ $platformSettings['platform_revenue_share']->value ?? 30 }}"
                                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-xl px-4 py-3 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500/30">
                                </div>
                            </div>

                            <div class="mt-4 p-4 bg-blue-50 border border-blue-100 rounded-xl text-xs text-blue-700 flex items-start gap-2">
                                <i class="fas fa-info-circle mt-0.5 flex-shrink-0"></i>
                                <span>Changes apply to <strong>all future payments</strong>. Past enrollments retain their original split amounts. Instructor + Platform must equal 100%.</span>
                            </div>

                            <div class="mt-5">
                                <button type="submit" class="bg-gradient-to-r from-blue-800 to-pink-600 text-white px-6 py-3 rounded-xl font-extrabold text-sm shadow hover:opacity-90 transition">
                                    <i class="fas fa-save mr-2"></i>Save Revenue Split
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- AI Configuration Settings -->
                    <div class="bg-white rounded-2xl border border-indigo-200 shadow-sm p-6">
                        <div class="flex items-center gap-2 mb-1">
                            <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">✨</div>
                            <h3 class="font-extrabold text-gray-900 text-base">Google Gemini AI Configuration</h3>
                        </div>
                        <p class="text-xs text-gray-500 mb-5">Configure the Gemini AI Assistant used across the course builder for auto-generating descriptions, outlines, and notes.</p>

                        <form action="{{ route('admin.settings.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="instructor_revenue_share" value="{{ $platformSettings['instructor_revenue_share']->value ?? 70 }}">
                            <input type="hidden" name="platform_revenue_share" value="{{ $platformSettings['platform_revenue_share']->value ?? 30 }}">

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">
                                    Google Gemini API Key
                                </label>
                                <input type="password" name="gemini_api_key" placeholder="AIzaSy..."
                                    value="{{ $platformSettings['gemini_api_key']->value ?? env('GEMINI_API_KEY', '') }}"
                                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-xl px-4 py-3 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500/30">
                                <p class="text-[11px] text-gray-400 mt-1">Get your free key from <a href="https://aistudio.google.com/app/apikey" target="_blank" class="text-indigo-600 underline font-semibold">Google AI Studio</a>. Saved in database and works instantly without editing server files.</p>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold text-xs shadow transition flex items-center gap-1.5">
                                    <i class="fas fa-key"></i> Save AI Key
                                </button>
                            </div>
                        </form>
                    </div>
                </div>


            </main>
        </div>
    </div>
</div>

<script>
function switchTab(name) {
    document.querySelectorAll('.tab-panel').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-gradient-to-r', 'from-blue-800', 'to-pink-600', 'text-white', 'shadow-md');
        btn.classList.add('text-gray-700', 'hover:bg-gray-100');
    });
    document.getElementById('tab-' + name).classList.remove('hidden');
    const activeBtn = document.getElementById('tab-btn-' + name);
    if (activeBtn) {
        activeBtn.classList.remove('text-gray-700', 'hover:bg-gray-100');
        activeBtn.classList.add('bg-gradient-to-r', 'from-blue-800', 'to-pink-600', 'text-white', 'shadow-md');
    }
}

function toggleSpecificUser(val) {
    const wrap = document.getElementById('specificUserWrap');
    const select = document.getElementById('recipientUserSelect');
    if (wrap) {
        if (val === 'specific') {
            wrap.classList.remove('hidden');
            if (select) select.required = true;
        } else {
            wrap.classList.add('hidden');
            if (select) select.required = false;
        }
    }
}

function toggleReplyBox(id) {
    const box = document.getElementById(id);
    if (box) box.classList.toggle('hidden');
}

</script>
@endsection
