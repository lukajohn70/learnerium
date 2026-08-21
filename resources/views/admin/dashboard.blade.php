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
                        ['applications', 'fa-user-check', 'Instructor Apps'],
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
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h2 class="font-bold text-gray-800 text-base"><i class="fas fa-credit-card mr-2 text-emerald-600"></i>Payments & Enrollments</h2>
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
    activeBtn.classList.remove('text-gray-700', 'hover:bg-gray-100');
    activeBtn.classList.add('bg-gradient-to-r', 'from-blue-800', 'to-pink-600', 'text-white', 'shadow-md');
}
</script>
@endsection
