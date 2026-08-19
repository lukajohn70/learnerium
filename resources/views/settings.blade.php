@extends('layouts.app')

@section('content')
<div class="py-12 px-4">
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900"><i class="fas fa-cog mr-3 text-primary-jlm"></i>Settings</h1>
            <p class="text-gray-500 mt-1">Manage your account preferences and security.</p>
        </div>

        @if(session('status'))
            <div class="mb-6 bg-emerald-50 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-xl flex items-center space-x-3 shadow-sm">
                <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
                <span class="font-medium text-sm">{{ session('status') }}</span>
            </div>
        @endif

        <!-- Profile Details Card -->
        <div class="bg-white rounded-2xl shadow-md overflow-hidden mb-6">
            <div class="bg-gray-50 border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-user mr-2 text-primary-jlm"></i>Profile Details</h2>
            </div>
            <div class="p-6">
                <form action="#" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name</label>
                            <input id="name" type="text" name="name" value="{{ Auth::user()->name }}" 
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-jlm/40 focus:border-primary-jlm bg-white text-gray-800 transition"
                                   placeholder="Your full name">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
                            <input id="email" type="email" name="email" value="{{ Auth::user()->email }}" 
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-jlm/40 focus:border-primary-jlm bg-white text-gray-800 transition"
                                   placeholder="your@email.com">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Role</label>
                        <div class="px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-500 text-sm capitalize">
                            {{ Auth::user()->isInstructor() ? 'Instructor' : 'Student' }} (cannot be changed)
                        </div>
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="bg-primary-jlm text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-primary-jlm-dark transition shadow text-sm">
                            <i class="fas fa-save mr-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Password Card -->
        <div class="bg-white rounded-2xl shadow-md overflow-hidden mb-6">
            <div class="bg-gray-50 border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-lock mr-2 text-secondary-jlm"></i>Change Password</h2>
            </div>
            <div class="p-6">
                <form action="#" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-1.5">Current Password</label>
                        <input id="current_password" type="password" name="current_password" 
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary-jlm/40 focus:border-secondary-jlm bg-white text-gray-800 transition"
                               placeholder="••••••••">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-1.5">New Password</label>
                            <input id="new_password" type="password" name="password" 
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary-jlm/40 focus:border-secondary-jlm bg-white text-gray-800 transition"
                                   placeholder="••••••••">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm New Password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" 
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary-jlm/40 focus:border-secondary-jlm bg-white text-gray-800 transition"
                                   placeholder="••••••••">
                        </div>
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="bg-secondary-jlm text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-secondary-jlm/90 transition shadow text-sm">
                            <i class="fas fa-key mr-2"></i>Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Notifications Card -->
        <div class="bg-white rounded-2xl shadow-md overflow-hidden mb-6">
            <div class="bg-gray-50 border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-bell mr-2 text-accent-jlm/70"></i>Notification Preferences</h2>
            </div>
            <div class="p-6 space-y-4">
                @foreach([
                    ['Email me on new course enrollment', 'notify_enrollment'],
                    ['Email me on quiz results', 'notify_quiz'],
                    ['Email me with platform updates', 'notify_updates'],
                    ['Send weekly learning digest', 'notify_digest'],
                ] as [$label, $key])
                <label class="flex items-center justify-between cursor-pointer group py-2 border-b border-gray-50 last:border-0">
                    <span class="text-gray-700 font-medium group-hover:text-primary-jlm transition text-sm">{{ $label }}</span>
                    <div class="relative">
                        <input type="checkbox" class="sr-only peer" name="{{ $key }}" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-checked:bg-primary-jlm rounded-full transition-colors duration-200"></div>
                        <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform duration-200"></div>
                    </div>
                </label>
                @endforeach
                <div class="pt-2">
                    <button type="button" class="bg-primary-jlm text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-primary-jlm-dark transition shadow text-sm">
                        <i class="fas fa-save mr-2"></i>Save Preferences
                    </button>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-red-100">
            <div class="bg-red-50 border-b border-red-100 px-6 py-4">
                <h2 class="text-lg font-bold text-red-700"><i class="fas fa-exclamation-triangle mr-2"></i>Danger Zone</h2>
            </div>
            <div class="p-6">
                <p class="text-gray-600 text-sm mb-4">Once you delete your account, all of your data will be permanently removed. This action cannot be undone.</p>
                <button type="button" onclick="confirm('Are you sure? This action cannot be undone.') && alert('Please contact support to delete your account.')" 
                        class="bg-red-600 text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-red-700 transition shadow text-sm">
                    <i class="fas fa-trash mr-2"></i>Delete Account
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
