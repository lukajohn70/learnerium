@extends('layouts.app')

@section('title', 'Manage Users — Admin Learnerium')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-users text-blue-600"></i> User Management
                </h1>
                <p class="text-xs text-gray-500 mt-1">Manage user roles, students, instructors, and administrators.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-xl text-xs font-bold shadow-sm hover:bg-gray-50 transition">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        @if(session('status'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                <i class="fas fa-check-circle text-emerald-500"></i>{{ session('status') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead class="bg-gray-50 text-gray-600 uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-4 text-left">User</th>
                            <th class="px-6 py-4 text-left">Email</th>
                            <th class="px-6 py-4 text-left">Role</th>
                            <th class="px-6 py-4 text-left">Joined Date</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $user->avatarUrl() }}" class="w-9 h-9 rounded-full object-cover border border-gray-200">
                                    <span class="font-extrabold text-gray-900 text-sm">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 font-medium">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold capitalize
                                    {{ $user->role === 'admin' ? 'bg-red-100 text-red-700' : ($user->role === 'instructor' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700') }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $user->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.users.role', $user) }}" method="POST" class="inline-flex items-center gap-2">
                                    @csrf
                                    <select name="role" class="bg-gray-50 border border-gray-300 text-gray-800 text-xs rounded-lg px-2.5 py-1 font-semibold focus:outline-none">
                                        @foreach(['student','instructor','admin'] as $r)
                                            <option value="{{ $r }}" {{ $user->role === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-xs font-bold transition">Update</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">No users found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
