<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php $currentRole = $role ?? 'student'; @endphp
    <title>
        @if($currentRole === 'instructor') Instructor Portal Sign In
        @elseif($currentRole === 'admin') Administrator Sign In
        @else Student Sign In
        @endif — Learnerium
    </title>
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-jlm': '#1b2299',
                        'primary-jlm-dark': '#141a73',
                        'secondary-jlm': '#e4306d',
                        'accent-jlm': '#f7de7a',
                        'admin-gold': '#b45309',
                    },
                    fontFamily: { 'inter': ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .input-field {
            width: 100%; padding: 12px 16px; border: 1.5px solid #e5e7eb;
            border-radius: 12px; font-size: 15px; outline: none;
            transition: border-color .2s, box-shadow .2s;
            background: #fff;
        }
        .input-field:focus {
            @php
                if ($currentRole === 'instructor') {
                    echo 'border-color: #e4306d; box-shadow: 0 0 0 3px rgba(228,48,109,.12);';
                } elseif ($currentRole === 'admin') {
                    echo 'border-color: #b45309; box-shadow: 0 0 0 3px rgba(180,83,9,.12);';
                } else {
                    echo 'border-color: #1b2299; box-shadow: 0 0 0 3px rgba(27,34,153,.12);';
                }
            @endphp
        }
        .hero-dot { position: absolute; border-radius: 50%; opacity: .15; }
    </style>
</head>
<body class="antialiased text-gray-800 min-h-screen flex">

    <!-- Left Panel: Branding -->
    <div class="hidden lg:flex lg:w-1/2
        @if($currentRole === 'instructor') bg-gradient-to-br from-secondary-jlm via-pink-700 to-primary-jlm
        @elseif($currentRole === 'admin') bg-gradient-to-br from-amber-800 via-amber-700 to-yellow-600
        @else bg-gradient-to-br from-primary-jlm via-blue-800 to-secondary-jlm
        @endif
        relative overflow-hidden flex-col items-center justify-center p-12 text-white">

        <div class="hero-dot w-96 h-96 bg-white top-[-80px] left-[-80px]"></div>
        <div class="hero-dot w-64 h-64 bg-accent-jlm bottom-[-40px] right-[-40px]"></div>
        <div class="hero-dot w-32 h-32 bg-secondary-jlm top-1/2 left-1/4"></div>

        <div class="relative z-10 text-center max-w-md">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-3 bg-white/90 backdrop-blur-md px-6 py-3 rounded-2xl shadow-xl border border-white/40 mb-8 group transition hover:scale-105">
                <img src="{{ asset('logo-only.png') }}" alt="Learnerium Logo" class="h-10 w-auto object-contain">
                <span class="text-3xl font-black bg-gradient-to-r from-[#1b2299] to-[#e4306d] bg-clip-text text-transparent tracking-tight">Learnerium</span>
            </a>

            @if($currentRole === 'admin')
                <p class="text-xl font-light text-white/80 mb-10 leading-relaxed">
                    Administrator Portal — <span class="text-yellow-200 font-semibold">Manage, oversee, and control</span> the entire platform.
                </p>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                        <div class="text-2xl font-extrabold"><i class="fas fa-users-cog"></i></div>
                        <div class="text-xs text-white/70 mt-1">User Management</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                        <div class="text-2xl font-extrabold"><i class="fas fa-cogs"></i></div>
                        <div class="text-xs text-white/70 mt-1">Platform Settings</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                        <div class="text-2xl font-extrabold"><i class="fas fa-chart-bar"></i></div>
                        <div class="text-xs text-white/70 mt-1">Analytics</div>
                    </div>
                </div>
            @elseif($currentRole === 'instructor')
                <p class="text-xl font-light text-white/80 mb-10 leading-relaxed">
                    Instructor Portal — <span class="text-accent-jlm font-semibold">Inspire, teach, and manage</span> your online courses with ease.
                </p>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                        <div class="text-2xl font-extrabold"><i class="fas fa-book-open"></i></div>
                        <div class="text-xs text-white/70 mt-1">Course Builder</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                        <div class="text-2xl font-extrabold"><i class="fas fa-users"></i></div>
                        <div class="text-xs text-white/70 mt-1">Student Roster</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                        <div class="text-2xl font-extrabold"><i class="fas fa-chart-line"></i></div>
                        <div class="text-xs text-white/70 mt-1">Analytics</div>
                    </div>
                </div>
            @else
                <p class="text-xl font-light text-white/80 mb-10 leading-relaxed">
                    Student Portal — <span class="text-accent-jlm font-semibold">Knowledge meets ambition.</span> Continue your learning journey today.
                </p>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                        <div class="text-2xl font-extrabold">500+</div>
                        <div class="text-xs text-white/70 mt-0.5">Courses</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                        <div class="text-2xl font-extrabold">10K+</div>
                        <div class="text-xs text-white/70 mt-0.5">Students</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                        <div class="text-2xl font-extrabold">4.9★</div>
                        <div class="text-xs text-white/70 mt-0.5">Rating</div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Right Panel: Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12 bg-gray-50 relative">


        <div class="w-full max-w-md">

            <!-- Mobile logo -->
            <div class="lg:hidden text-center mb-8">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2">
                    <img src="{{ asset('logo-only.png') }}" alt="Learnerium Logo" class="h-10 w-auto object-contain">
                    <span class="text-3xl font-black text-primary-jlm tracking-tight">Learnerium</span>
                </a>
            </div>

            @if($currentRole === 'admin')
                <!-- Admin portal header badge -->
                <div class="flex items-center justify-center gap-2 mb-6 py-2.5 px-4 bg-amber-50 border border-amber-200 rounded-xl">
                    <i class="fas fa-shield-halved text-amber-600"></i>
                    <span class="text-sm font-bold text-amber-700 tracking-wide uppercase">Administrator Portal</span>
                </div>
            @else
                <!-- Role Switcher Tabs (Student / Instructor only) -->
                <div class="flex rounded-xl bg-gray-200 p-1 mb-6">
                    <a href="{{ route('login.student') }}"
                       class="flex-1 text-center py-2.5 rounded-lg text-sm font-semibold transition {{ $currentRole === 'student' ? 'bg-white text-primary-jlm shadow' : 'text-gray-500 hover:text-gray-700' }}">
                        <i class="fas fa-user-graduate mr-1.5"></i>Student Login
                    </a>
                    <a href="{{ route('login.instructor') }}"
                       class="flex-1 text-center py-2.5 rounded-lg text-sm font-semibold transition {{ $currentRole === 'instructor' ? 'bg-white text-secondary-jlm shadow' : 'text-gray-500 hover:text-gray-700' }}">
                        <i class="fas fa-chalkboard-teacher mr-1.5"></i>Instructor Login
                    </a>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                <div class="mb-7">
                    <h1 class="text-2xl font-extrabold text-gray-900 mb-1">
                        @if($currentRole === 'admin') Administrator Sign In
                        @elseif($currentRole === 'instructor') Instructor Portal
                        @else Welcome back!
                        @endif
                    </h1>
                    <p class="text-gray-500 text-sm">
                        @if($currentRole === 'admin') Restricted access — administrators only.
                        @elseif($currentRole === 'instructor') Sign in to access your instructor dashboard.
                        @else Sign in to continue learning.
                        @endif
                    </p>
                </div>

                @if(session('status'))
                    <div class="mb-5 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-500"></i>{{ session('status') }}
                    </div>
                @endif

                @php
                    $formAction = match($currentRole) {
                        'instructor' => route('login.instructor.post'),
                        'admin'      => route('login.admin.post'),
                        default      => route('login.student.post'),
                    };
                @endphp

                <form action="{{ $formAction }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email-address" class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
                        <input id="email-address" name="email" type="email" autocomplete="email" required
                               class="input-field" placeholder="Enter your email address" value="{{ old('email') }}">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                   class="input-field pr-12" placeholder="••••••••">
                            <button type="button"
                                    onclick="const p=document.getElementById('password'); p.type = p.type==='password'?'text':'password'; this.querySelector('i').classList.toggle('fa-eye'); this.querySelector('i').classList.toggle('fa-eye-slash');"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-between items-center">
                        <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300" name="remember">
                            <span class="text-sm text-gray-600">Remember me</span>
                        </label>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm font-semibold text-secondary-jlm hover:text-secondary-jlm/80 transition">Forgot password?</a>
                        @endif
                    </div>

                    <button type="submit"
                            class="w-full text-white py-3.5 rounded-xl font-bold text-base transition shadow-md hover:shadow-lg
                            @if($currentRole === 'admin') bg-amber-700 hover:bg-amber-800
                            @elseif($currentRole === 'instructor') bg-secondary-jlm hover:bg-secondary-jlm/90
                            @else bg-primary-jlm hover:bg-primary-jlm-dark
                            @endif">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Sign In as
                        @if($currentRole === 'admin') Administrator
                        @elseif($currentRole === 'instructor') Instructor
                        @else Student
                        @endif
                    </button>
                </form>

                @if($currentRole !== 'admin')
                <p class="mt-7 text-center text-sm text-gray-500">
                    Don't have an account?
                    @if($currentRole === 'instructor')
                        <a href="{{ route('register.instructor') }}" class="font-bold text-secondary-jlm hover:text-secondary-jlm/80 transition">Register as Instructor</a>
                    @else
                        <a href="{{ route('register') }}" class="font-bold text-secondary-jlm hover:text-secondary-jlm/80 transition">Create Student account</a>
                    @endif
                </p>
                @else
                <p class="mt-7 text-center text-xs text-gray-400">
                    <a href="{{ route('login.student') }}" class="hover:text-gray-600 transition"><i class="fas fa-arrow-left mr-1"></i>Back to public login</a>
                </p>
                @endif
            </div>

        </div>
    </div>
</body>
</html>
