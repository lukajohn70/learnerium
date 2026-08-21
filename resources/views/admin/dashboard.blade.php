@extends('layouts.app')

@section('title', 'Admin Dashboard — Learnerium')

@section('content')
<div class="min-h-screen bg-gray-950">
    <!-- Admin Header -->
    <div class="bg-gradient-to-r from-gray-900 via-primary-jlm to-secondary-jlm px-6 py-8">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                        <i class="fas fa-shield-alt text-accent-jlm text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold text-white">Admin Dashboard</h1>
                        <p class="text-white/60 text-xs">Learnerium Control Panel</p>
                    </div>
                </div>
            </div>
            <div class="text-right text-xs text-white/60">
                <p class="font-bold text-white">{{ Auth::user()->name }}</p>
                <p>Administrator</p>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="bg-gray-900 border-b border-gray-800 sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-6">
            <nav class="flex gap-1 overflow-x-auto py-1 hide-scrollbar">
                @foreach([
                    ['overview', 'fa-tachometer-alt', 'Overview'],
                    ['users', 'fa-users', 'Users'],
                    ['courses', 'fa-book-open', 'Courses'],
                    ['coupons', 'fa-tag', 'Coupons'],
                    ['payments', 'fa-credit-card', 'Payments'],
                    ['applications', 'fa-user-check', 'Instructor Apps'],
                ] as [$tab, $icon, $label])
                <button onclick="switchTab('{{ $tab }}')" id="tab-btn-{{ $tab }}"
                    class="tab-btn flex items-center gap-2 px-4 py-3 text-xs font-bold whitespace-nowrap border-b-2 transition
                    {{ $tab === 'overview' ? 'text-accent-jlm border-accent-jlm' : 'text-gray-400 border-transparent hover:text-white hover:border-gray-600' }}">
                    <i class="fas {{ $icon }}"></i> {{ $label }}
                </button>
                @endforeach
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8">
        @if(session('status'))
            <div class="mb-6 bg-emerald-900/30 border border-emerald-500/40 text-emerald-300 px-4 py-3 rounded-xl text-sm font-medium">
                <i class="fas fa-check-circle mr-2"></i>{{ session('status') }}
            </div>
        @endif

        <!-- ============= OVERVIEW TAB ============= -->
        <div id="tab-overview" class="tab-panel">
            <!-- Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                @foreach([
                    ['Total Users', $stats['total_users'], 'fa-users', 'from-blue-600 to-blue-800'],
                    ['Courses', $stats['total_courses'], 'fa-book-open', 'from-purple-600 to-purple-800'],
                    ['Paid Enrollments', $stats['paid_enrollments'], 'fa-graduation-cap', 'from-green-600 to-green-800'],
                    ['Revenue (₦)', number_format($stats['total_revenue'], 2), 'fa-naira-sign', 'from-secondary-jlm to-pink-800'],
                ] as [$label, $value, $icon, $gradient])
                <div class="bg-gradient-to-br {{ $gradient }} rounded-2xl p-5 shadow-lg">
                    <div class="flex items-center justify-between mb-3">
                        <i class="fas {{ $icon }} text-white/50 text-xl"></i>
                    </div>
                    <div class="text-2xl md:text-3xl font-extrabold text-white">{{ $value }}</div>
                    <div class="text-white/70 text-xs mt-1 font-medium">{{ $label }}</div>
                </div>
                @endforeach
            </div>

            <!-- Secondary Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                @foreach([
                    ['Students', $stats['total_students'], 'fa-user-graduate', 'text-blue-400'],
                    ['Instructors', $stats['total_instructors'], 'fa-chalkboard-teacher', 'text-purple-400'],
                    ['Published Courses', $stats['published_courses'], 'fa-eye', 'text-green-400'],
                    ['Active Coupons', $stats['total_coupons'], 'fa-ticket-alt', 'text-yellow-400'],
                ] as [$label, $value, $icon, $color])
                <div class="bg-gray-800 rounded-2xl p-5 border border-gray-700">
                    <i class="fas {{ $icon }} {{ $color }} text-xl mb-3 block"></i>
                    <div class="text-xl font-extrabold text-white">{{ $value }}</div>
                    <div class="text-gray-400 text-xs mt-1">{{ $label }}</div>
                </div>
                @endforeach
            </div>

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-700 flex items-center gap-2">
                        <i class="fas fa-user-plus text-blue-400"></i>
                        <h3 class="font-bold text-white text-sm">Recent Users</h3>
                    </div>
                    <div class="divide-y divide-gray-700/50">
                        @foreach($recentUsers as $u)
                        <div class="flex items-center gap-3 px-5 py-3">
                            <img src="{{ $u->avatarUrl() }}" alt="{{ $u->name }}" class="w-8 h-8 rounded-full object-cover border border-gray-600">
                            <div class="flex-1 min-w-0">
                                <p class="text-white text-xs font-semibold truncate">{{ $u->name }}</p>
                                <p class="text-gray-400 text-[10px] truncate">{{ $u->email }}</p>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full
                                {{ $u->role === 'admin' ? 'bg-red-900/40 text-red-400' : ($u->role === 'instructor' ? 'bg-purple-900/40 text-purple-300' : 'bg-blue-900/40 text-blue-300') }}">
                                {{ ucfirst($u->role) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-700 flex items-center gap-2">
                        <i class="fas fa-shopping-cart text-green-400"></i>
                        <h3 class="font-bold text-white text-sm">Recent Enrollments</h3>
                    </div>
                    <div class="divide-y divide-gray-700/50">
                        @foreach($recentEnrolls->take(5) as $enroll)
                        <div class="px-5 py-3 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-white text-xs font-semibold truncate">{{ $enroll->user->name ?? 'Unknown' }}</p>
                                <p class="text-gray-400 text-[10px] truncate">{{ $enroll->course->title ?? 'Unknown Course' }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="block text-[10px] font-bold {{ $enroll->payment_status === 'paid' ? 'text-green-400' : 'text-yellow-400' }}">
                                    {{ ucfirst($enroll->payment_status ?? 'pending') }}
                                </span>
                                @if($enroll->amount_paid)
                                    <span class="text-gray-400 text-[10px]">₦{{ number_format($enroll->amount_paid, 2) }}</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- ============= USERS TAB ============= -->
        <div id="tab-users" class="tab-panel hidden">
            <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-700 flex items-center justify-between">
                    <h2 class="font-bold text-white"><i class="fas fa-users mr-2 text-blue-400"></i>All Users</h2>
                    <a href="{{ route('admin.users') }}" class="text-xs text-blue-400 hover:underline">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead class="bg-gray-700/50">
                            <tr class="text-gray-400 text-left">
                                <th class="px-5 py-3 font-semibold">User</th>
                                <th class="px-5 py-3 font-semibold">Email</th>
                                <th class="px-5 py-3 font-semibold">Role</th>
                                <th class="px-5 py-3 font-semibold">Joined</th>
                                <th class="px-5 py-3 font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/40">
                            @foreach($recentUsers as $u)
                            <tr class="hover:bg-gray-700/30 transition">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-2">
                                        <img src="{{ $u->avatarUrl() }}" class="w-7 h-7 rounded-full object-cover border border-gray-600">
                                        <span class="text-white font-medium">{{ $u->name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-gray-400">{{ $u->email }}</td>
                                <td class="px-5 py-3">
                                    <form action="{{ route('admin.users.role', $u) }}" method="POST" class="flex items-center gap-1.5">
                                        @csrf
                                        <select name="role" class="bg-gray-700 text-white text-xs border border-gray-600 rounded-lg px-2 py-1">
                                            @foreach(['student','instructor','admin'] as $r)
                                                <option value="{{ $r }}" {{ $u->role === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="bg-primary-jlm text-white px-2 py-1 rounded-lg text-[10px] font-bold hover:bg-primary-jlm-dark transition">Save</button>
                                    </form>
                                </td>
                                <td class="px-5 py-3 text-gray-400">{{ $u->created_at->format('d M Y') }}</td>
                                <td class="px-5 py-3">
                                    <span class="text-gray-500 text-[10px]">—</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-5 py-3 border-t border-gray-700">
                    <a href="{{ route('admin.users') }}" class="text-xs text-blue-400 hover:underline">Manage all users →</a>
                </div>
            </div>
        </div>

        <!-- ============= COURSES TAB ============= -->
        <div id="tab-courses" class="tab-panel hidden">
            <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-700">
                    <h2 class="font-bold text-white"><i class="fas fa-book-open mr-2 text-purple-400"></i>All Courses</h2>
                </div>
                <div class="divide-y divide-gray-700/40">
                    @foreach($recentCourses as $c)
                    <div class="px-6 py-4 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <img src="{{ $c->thumbnailUrl() }}" class="w-12 h-9 object-cover rounded-lg border border-gray-600 flex-shrink-0">
                            <div class="min-w-0">
                                <p class="text-white text-sm font-bold truncate">{{ $c->title }}</p>
                                <p class="text-gray-400 text-[10px]">by {{ $c->instructor->name }} · ₦{{ number_format($c->price,2) }} · {{ $c->enrollments->count() }} enrolled</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span class="text-[10px] px-2 py-0.5 rounded-full {{ $c->published_at ? 'bg-green-900/40 text-green-400' : 'bg-gray-700 text-gray-400' }} font-bold">
                                {{ $c->published_at ? 'Published' : 'Draft' }}
                            </span>
                            <form action="{{ route('admin.courses.toggle', $c) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-[10px] bg-gray-700 hover:bg-gray-600 text-white px-3 py-1.5 rounded-lg font-bold transition">
                                    {{ $c->published_at ? 'Unpublish' : 'Publish' }}
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="px-5 py-3 border-t border-gray-700">
                    <a href="{{ route('admin.courses') }}" class="text-xs text-purple-400 hover:underline">Manage all courses →</a>
                </div>
            </div>
        </div>

        <!-- ============= COUPONS TAB ============= -->
        <div id="tab-coupons" class="tab-panel hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Create Coupon Form -->
                <div class="bg-gray-800 rounded-2xl border border-gray-700 p-6 space-y-4">
                    <h3 class="font-bold text-white flex items-center gap-2"><i class="fas fa-plus text-accent-jlm"></i> New Coupon</h3>
                    <form action="{{ route('admin.coupons.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-[10px] text-gray-400 font-bold uppercase mb-1">Coupon Code</label>
                            <input type="text" name="code" required placeholder="e.g. LEARN50"
                                class="w-full bg-gray-700 border border-gray-600 text-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent-jlm/40 uppercase font-bold tracking-widest">
                        </div>
                        <div>
                            <label class="block text-[10px] text-gray-400 font-bold uppercase mb-1">Discount Type</label>
                            <select name="discount_type" class="w-full bg-gray-700 border border-gray-600 text-white rounded-xl px-3 py-2 text-sm focus:outline-none">
                                <option value="percentage">Percentage (%)</option>
                                <option value="fixed">Fixed Amount (₦)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] text-gray-400 font-bold uppercase mb-1">Discount Value</label>
                            <input type="number" name="discount_value" required min="1" step="0.01" placeholder="e.g. 50"
                                class="w-full bg-gray-700 border border-gray-600 text-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent-jlm/40">
                        </div>
                        <div>
                            <label class="block text-[10px] text-gray-400 font-bold uppercase mb-1">Applicable Course (leave blank for all)</label>
                            <select name="course_id" class="w-full bg-gray-700 border border-gray-600 text-white rounded-xl px-3 py-2 text-sm focus:outline-none">
                                <option value="">All Courses (Global)</option>
                                @foreach($recentCourses as $c)
                                    <option value="{{ $c->id }}">{{ $c->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] text-gray-400 font-bold uppercase mb-1">Expires At (optional)</label>
                            <input type="date" name="expires_at"
                                class="w-full bg-gray-700 border border-gray-600 text-white rounded-xl px-3 py-2 text-sm focus:outline-none">
                        </div>
                        <button type="submit" class="w-full bg-accent-jlm hover:bg-yellow-400 text-primary-jlm py-2.5 rounded-xl font-bold text-sm transition">
                            <i class="fas fa-plus mr-2"></i>Create Coupon
                        </button>
                    </form>
                </div>

                <!-- Existing Coupons -->
                <div class="lg:col-span-2 bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-700">
                        <h3 class="font-bold text-white"><i class="fas fa-ticket-alt mr-2 text-accent-jlm"></i>Active Coupons</h3>
                    </div>
                    <div class="divide-y divide-gray-700/40">
                        @forelse($recentCourses as $c)
                            @php $coupons = $c->coupons; @endphp
                        @empty
                            <div class="px-6 py-10 text-center text-gray-500">
                                <i class="fas fa-ticket-alt text-4xl mb-3 opacity-30 block"></i>
                                <p class="text-sm">No coupons created yet. Use the form to create your first.</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="px-5 py-3 border-t border-gray-700">
                        <a href="{{ route('admin.coupons') }}" class="text-xs text-accent-jlm hover:underline">Manage all coupons →</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============= PAYMENTS TAB ============= -->
        <div id="tab-payments" class="tab-panel hidden">
            <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-700 flex items-center justify-between">
                    <h2 class="font-bold text-white"><i class="fas fa-credit-card mr-2 text-green-400"></i>Recent Payments</h2>
                    <a href="{{ route('admin.payments') }}" class="text-xs text-green-400 hover:underline">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead class="bg-gray-700/50">
                            <tr class="text-gray-400 text-left">
                                <th class="px-5 py-3 font-semibold">Student</th>
                                <th class="px-5 py-3 font-semibold">Course</th>
                                <th class="px-5 py-3 font-semibold">Amount</th>
                                <th class="px-5 py-3 font-semibold">Status</th>
                                <th class="px-5 py-3 font-semibold">Coupon</th>
                                <th class="px-5 py-3 font-semibold">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/40">
                            @foreach($recentEnrolls as $enroll)
                            <tr class="hover:bg-gray-700/30 transition">
                                <td class="px-5 py-3 text-white font-medium">{{ $enroll->user->name ?? '—' }}</td>
                                <td class="px-5 py-3 text-gray-400">{{ Str::limit($enroll->course->title ?? '—', 30) }}</td>
                                <td class="px-5 py-3 text-white font-bold">₦{{ number_format($enroll->amount_paid ?? 0, 2) }}</td>
                                <td class="px-5 py-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $enroll->payment_status === 'paid' ? 'bg-green-900/40 text-green-400' : 'bg-yellow-900/40 text-yellow-400' }}">
                                        {{ ucfirst($enroll->payment_status ?? 'pending') }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-gray-400">{{ $enroll->coupon_code ?? '—' }}</td>
                                <td class="px-5 py-3 text-gray-400">{{ $enroll->created_at->format('d M Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ============= INSTRUCTOR APPS TAB ============= -->
        <div id="tab-applications" class="tab-panel hidden">
            <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-700 flex items-center justify-between">
                    <h2 class="font-bold text-white"><i class="fas fa-user-check mr-2 text-accent-jlm"></i>Instructor Applications</h2>
                    <a href="{{ route('admin.instructor.applications') }}" class="text-xs text-accent-jlm hover:underline font-semibold">View Full Page →</a>
                </div>
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="fas fa-chalkboard-teacher text-4xl opacity-30 mb-3 block"></i>
                    <p class="text-sm mb-4">Manage instructor verification requests from the dedicated page.</p>
                    <a href="{{ route('admin.instructor.applications') }}"
                        class="bg-accent-jlm hover:bg-yellow-400 text-primary-jlm px-6 py-2.5 rounded-xl font-bold text-sm transition inline-flex items-center gap-2">
                        <i class="fas fa-external-link-alt"></i> Open Instructor Applications
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function switchTab(name) {
    document.querySelectorAll('.tab-panel').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('text-accent-jlm', 'border-accent-jlm');
        btn.classList.add('text-gray-400', 'border-transparent');
    });
    document.getElementById('tab-' + name).classList.remove('hidden');
    const activeBtn = document.getElementById('tab-btn-' + name);
    activeBtn.classList.remove('text-gray-400', 'border-transparent');
    activeBtn.classList.add('text-accent-jlm', 'border-accent-jlm');
}
</script>
<style>.hide-scrollbar::-webkit-scrollbar{display:none}</style>
@endsection
