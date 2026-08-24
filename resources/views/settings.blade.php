@extends('layouts.app')

@section('title', 'Account Settings — Learnerium')

@section('content')
<div class="min-h-screen bg-gray-50 py-10 px-4">
    <div class="max-w-4xl mx-auto">

        <div class="mb-8">
            <h1 class="text-2xl font-extrabold text-gray-900 flex items-center gap-3">
                <div class="w-10 h-10 bg-primary-jlm rounded-xl flex items-center justify-center">
                    <i class="fas fa-cog text-white text-sm"></i>
                </div>
                Account Settings
            </h1>
            <p class="text-gray-500 text-sm mt-1.5 ml-13">Manage your profile information, security, and preferences.</p>
        </div>

        @if(session('status'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="fas fa-check-circle text-emerald-500"></i>{{ session('status') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- ====== LEFT SIDEBAR: Profile Picture ====== -->
            <div class="lg:col-span-1 space-y-5">
                <!-- Avatar Card -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="bg-gradient-to-br from-primary-jlm to-secondary-jlm h-20"></div>
                    <div class="px-6 pb-6 -mt-10 text-center">
                        <div class="relative inline-block mb-4">
                            <img id="avatar-preview"
                                src="{{ auth()->user()->avatarUrl() }}"
                                alt="{{ auth()->user()->name }}"
                                class="w-20 h-20 rounded-full border-4 border-white shadow-md object-cover">
                            <label for="avatar-upload"
                                class="absolute bottom-0 right-0 bg-primary-jlm hover:bg-primary-jlm-dark text-white w-7 h-7 rounded-full flex items-center justify-center cursor-pointer shadow-lg transition border-2 border-white"
                                title="Change profile picture">
                                <i class="fas fa-camera text-[10px]"></i>
                            </label>
                        </div>
                        <h2 class="font-extrabold text-gray-800 text-base">{{ auth()->user()->name }}</h2>
                        <p class="text-gray-400 text-xs">{{ auth()->user()->email }}</p>
                        <span class="mt-2 inline-block bg-primary-jlm/10 text-primary-jlm text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wide">
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                    </div>
                    <!-- Avatar Upload Form -->
                    <div class="border-t border-gray-50 px-5 py-4">
                        <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data" id="avatar-form">
                            @csrf
                            <input type="file" id="avatar-upload" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(this)">
                            @error('avatar')
                                <p class="text-red-500 text-xs mb-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                            <button type="submit" id="avatar-save-btn"
                                class="hidden w-full bg-primary-jlm hover:bg-primary-jlm-dark text-white py-2.5 rounded-xl text-xs font-bold transition">
                                <i class="fas fa-upload mr-1.5"></i> Upload New Photo
                            </button>
                            <p class="text-center text-[10px] text-gray-400 mt-2">Click the camera icon to choose a photo. JPG, PNG, GIF, WebP. Max 5MB.</p>
                        </form>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h3 class="font-bold text-gray-700 text-xs uppercase tracking-wider mb-3">Quick Links</h3>
                    <ul class="space-y-1.5">
                        @if(auth()->user()->isInstructor())
                        <li>
                            <a href="{{ route('instructor.dashboard') }}" class="flex items-center gap-2 text-sm text-gray-600 hover:text-primary-jlm px-3 py-2 rounded-lg hover:bg-gray-50 transition">
                                <i class="fas fa-chalkboard-teacher w-4 text-center text-primary-jlm/60"></i> Instructor Dashboard
                            </a>
                        </li>
                        @endif
                        <li>
                            <a href="{{ route('student.dashboard') }}" class="flex items-center gap-2 text-sm text-gray-600 hover:text-primary-jlm px-3 py-2 rounded-lg hover:bg-gray-50 transition">
                                <i class="fas fa-graduation-cap w-4 text-center text-primary-jlm/60"></i> My Learning
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('courses') }}" class="flex items-center gap-2 text-sm text-gray-600 hover:text-primary-jlm px-3 py-2 rounded-lg hover:bg-gray-50 transition">
                                <i class="fas fa-book-open w-4 text-center text-primary-jlm/60"></i> Browse Courses
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('privacy') }}" class="flex items-center gap-2 text-sm text-gray-600 hover:text-primary-jlm px-3 py-2 rounded-lg hover:bg-gray-50 transition">
                                <i class="fas fa-shield-alt w-4 text-center text-primary-jlm/60"></i> Privacy Policy
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('eua') }}" class="flex items-center gap-2 text-sm text-gray-600 hover:text-primary-jlm px-3 py-2 rounded-lg hover:bg-gray-50 transition">
                                <i class="fas fa-file-contract w-4 text-center text-primary-jlm/60"></i> Terms of Service
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- ====== RIGHT: Forms ====== -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Profile Details -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 flex items-center gap-2">
                        <i class="fas fa-user-edit text-primary-jlm"></i>
                        <h2 class="font-bold text-gray-800">Profile Information</h2>
                    </div>
                    <form action="{{ route('settings.profile') }}" method="POST" class="px-6 py-6 space-y-5">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="name" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Full Name</label>
                                <input type="text" id="name" name="name"
                                    value="{{ old('name', auth()->user()->name) }}"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-jlm/30 focus:border-primary-jlm text-sm text-gray-800 transition"
                                    required>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="email" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Email Address</label>
                                <input type="email" id="email" name="email"
                                    value="{{ old('email', auth()->user()->email) }}"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-jlm/30 focus:border-primary-jlm text-sm text-gray-800 transition"
                                    required>
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="bg-gray-50 border border-gray-100 rounded-xl p-4">
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <i class="fas fa-info-circle text-blue-400"></i>
                                <span>Your email is used for account notifications and login. Changing it may require re-verification.</span>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-primary-jlm hover:bg-primary-jlm-dark text-white px-8 py-2.5 rounded-xl text-sm font-bold transition flex items-center gap-2">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Change Password -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 flex items-center gap-2">
                        <i class="fas fa-lock text-secondary-jlm"></i>
                        <h2 class="font-bold text-gray-800">Change Password</h2>
                    </div>
                    <form action="{{ route('settings.password') }}" method="POST" class="px-6 py-6 space-y-5">
                        @csrf
                        <div>
                            <label for="current_password" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Current Password</label>
                            <div class="relative">
                                <input type="password" id="current_password" name="current_password"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-jlm/30 focus:border-primary-jlm text-sm text-gray-800 pr-10 transition"
                                    required>
                                <button type="button" onclick="togglePwd('current_password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="password" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">New Password</label>
                                <div class="relative">
                                    <input type="password" id="password" name="password"
                                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-jlm/30 focus:border-primary-jlm text-sm text-gray-800 pr-10 transition"
                                        required minlength="8">
                                    <button type="button" onclick="togglePwd('password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Confirm New Password</label>
                                <div class="relative">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-jlm/30 focus:border-primary-jlm text-sm text-gray-800 pr-10 transition"
                                        required>
                                    <button type="button" onclick="togglePwd('password_confirmation')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Password strength hint -->
                        <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 flex items-start gap-2 text-xs text-blue-700">
                            <i class="fas fa-shield-alt mt-0.5 flex-shrink-0"></i>
                            <span>Use at least 8 characters with a mix of uppercase, lowercase, numbers, and symbols for a strong password.</span>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-secondary-jlm hover:opacity-90 text-white px-8 py-2.5 rounded-xl text-sm font-bold transition flex items-center gap-2">
                                <i class="fas fa-key"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Account Deletion Request -->
                <div class="bg-white rounded-2xl border border-amber-200/80 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-amber-100 bg-amber-50/50 flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-user-shield text-amber-600"></i>
                            <h2 class="font-bold text-gray-800 text-sm">Account Deletion Request</h2>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-800 px-2.5 py-0.5 rounded-full">Data & Privacy</span>
                    </div>
                    <div class="px-6 py-5 space-y-4">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">Request Permanent Account Deletion</p>
                                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                    Need to close your account? Submit a request and our support team will verify, process, and permanently remove your account and course data within 48 hours.
                                </p>
                            </div>
                            <button type="button" onclick="toggleDeletionRequestForm()"
                                class="bg-amber-600 hover:bg-amber-700 text-white px-5 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 flex-shrink-0 shadow-sm">
                                <i class="fas fa-paper-plane"></i> Request Deletion
                            </button>
                        </div>

                        <!-- Expandable Deletion Request Form -->
                        <div id="deletionRequestBox" class="hidden pt-3 border-t border-gray-100">
                            <form id="accountDeletionForm" action="{{ route('settings.account.request-deletion') }}" method="POST" class="space-y-3.5">
                                @csrf
                                <div>
                                    <label for="deletion_reason" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                                        Reason for Deletion (Optional)
                                    </label>
                                    <textarea id="deletion_reason" name="reason" rows="3"
                                        placeholder="Please tell us why you wish to delete your account (e.g. no longer needed, duplicate account, etc.)..."
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-xs focus:outline-none focus:border-amber-500 focus:bg-white resize-none"></textarea>
                                </div>
                                <div class="flex items-center justify-end gap-2.5">
                                    <button type="button" onclick="toggleDeletionRequestForm()"
                                        class="px-4 py-2 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition">Cancel</button>
                                    <button type="button" onclick="confirmAccountDeletionRequest()"
                                        class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-xl text-xs font-bold shadow transition flex items-center gap-1.5">
                                        <i class="fas fa-paper-plane"></i> Submit Deletion Request
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatar-preview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
        document.getElementById('avatar-save-btn').classList.remove('hidden');
    }
}

function togglePwd(id) {
    const input = document.getElementById(id);
    if (input) input.type = input.type === 'password' ? 'text' : 'password';
}

function toggleDeletionRequestForm() {
    const box = document.getElementById('deletionRequestBox');
    if (box) box.classList.toggle('hidden');
}

async function confirmAccountDeletionRequest() {
    const confirmed = await showModal({
        type: 'warning',
        title: '⚠️ Confirm Account Deletion Request',
        message: 'Are you sure you want to request account deletion? An admin will review and permanently delete your profile, course progress, and associated data.',
        confirmText: 'Yes, Submit Request',
        cancelText: 'Cancel',
        isConfirm: true
    });
    if (confirmed) {
        document.getElementById('accountDeletionForm').submit();
    }
}
</script>
@endpush
@endsection
