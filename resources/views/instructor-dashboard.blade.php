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

        {{-- Bank Details Card --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                <i class="fas fa-university text-primary-jlm"></i> Payout Bank Details
            </h3>
            <form action="{{ route('instructor.bank-details.update') }}" method="POST" class="space-y-2.5">
                @csrf
                <div>
                    <input type="text" name="bank_name" placeholder="Bank Name (e.g. GTBank, Zenith)" value="{{ old('bank_name', $user->bank_name) }}" required
                           class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-jlm/30">
                </div>
                <div>
                    <input type="text" name="account_number" placeholder="10-Digit Account Number" value="{{ old('account_number', $user->account_number) }}" required
                           class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-jlm/30">
                </div>
                <div>
                    <input type="text" name="account_name" placeholder="Account Name (as on bank app)" value="{{ old('account_name', $user->account_name) }}" required
                           class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-jlm/30">
                </div>
                <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white text-xs font-bold py-2 rounded-xl transition">
                    Save Bank Info
                </button>
            </form>
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <a href="{{ route('instructor.courses.create') }}" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-t-4 border-primary-jlm flex flex-col items-center text-center hover:shadow-lg transition duration-300 transform hover:-translate-y-1 group">
            <div class="text-primary-jlm mb-3 text-4xl group-hover:scale-110 transition-transform duration-300"><i class="fas fa-plus-circle"></i></div>
            <h3 class="text-base font-bold text-gray-900 mb-1">Create Course</h3>
            <p class="text-gray-400 text-xs mb-3">Publish a new learning experience.</p>
            <span class="mt-auto inline-block bg-primary-jlm text-white px-4 py-1.5 rounded-xl font-semibold text-xs group-hover:bg-primary-jlm-dark transition">Start Now</span>
        </a>

        <a href="{{ route('instructor.manage.courses') }}" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-t-4 border-secondary-jlm flex flex-col items-center text-center hover:shadow-lg transition duration-300 transform hover:-translate-y-1 group">
            <div class="text-secondary-jlm mb-3 text-4xl group-hover:scale-110 transition-transform duration-300"><i class="fas fa-folder-open"></i></div>
            <h3 class="text-base font-bold text-gray-900 mb-1">Manage Courses</h3>
            <p class="text-gray-400 text-xs mb-3">Edit, update, and manage syllabus.</p>
            <span class="mt-auto inline-block bg-secondary-jlm text-white px-4 py-1.5 rounded-xl font-semibold text-xs group-hover:bg-secondary-jlm/90 transition">View Courses</span>
        </a>

        <a href="{{ route('instructor.submissions') }}" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-t-4 border-accent-jlm flex flex-col items-center text-center hover:shadow-lg transition duration-300 transform hover:-translate-y-1 group relative">
            @if($pendingSubmissionsCount > 0)
                <span class="absolute top-3 right-3 bg-secondary-jlm text-white text-[10px] font-black px-2 py-0.5 rounded-full">{{ $pendingSubmissionsCount }} new</span>
            @endif
            <div class="text-accent-jlm mb-3 text-4xl group-hover:scale-110 transition-transform duration-300"><i class="fas fa-clipboard-check text-primary-jlm"></i></div>
            <h3 class="text-base font-bold text-gray-900 mb-1">Grading Queue</h3>
            <p class="text-gray-400 text-xs mb-3">Review & grade student submissions.</p>
            <span class="mt-auto inline-block bg-primary-jlm text-white px-4 py-1.5 rounded-xl font-semibold text-xs group-hover:bg-primary-jlm-dark transition">Open Queue</span>
        </a>

        <a href="{{ url('/profile') }}" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-t-4 border-blue-400 flex flex-col items-center text-center hover:shadow-lg transition duration-300 transform hover:-translate-y-1 group">
            <div class="text-blue-400 mb-3 text-4xl group-hover:scale-110 transition-transform duration-300"><i class="fas fa-user-cog"></i></div>
            <h3 class="text-base font-bold text-gray-900 mb-1">Profile & Settings</h3>
            <p class="text-gray-400 text-xs mb-3">Update your profile & preferences.</p>
            <span class="mt-auto inline-block bg-blue-500 text-white px-4 py-1.5 rounded-xl font-semibold text-xs group-hover:bg-blue-600 transition">Edit Profile</span>
        </a>
    </div>

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
                        <div class="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0">
                            <img src="{{ $course->thumbnail ?? 'https://placehold.co/48x48/1b2299/f7de7a?text=C' }}" 
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
@endsection
