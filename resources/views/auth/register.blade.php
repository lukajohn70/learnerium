<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account — Learnerium</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
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
                    },
                    fontFamily: { 'inter': ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .input-group {
            position: relative;
            display: flex;
            items-center: center;
        }
        .input-group i.prefix-icon {
            position: absolute;
            left: 16px;
            color: #9ca3af;
            font-size: 14px;
            pointer-events: none;
            transition: color 0.2s;
        }
        .input-field {
            width: 100%;
            padding: 13px 44px 13px 44px;
            border: 1.5px solid #e5e7eb;
            border-radius: 14px;
            font-size: 14.5px;
            outline: none;
            transition: all .2s ease-in-out;
            background: #ffffff;
            color: #1f2937;
        }
        .input-field.no-suffix {
            padding-right: 16px;
        }
        .input-field:focus {
            border-color: #1b2299;
            box-shadow: 0 0 0 4px rgba(27,34,153,.1);
        }
        .input-group:focus-within i.prefix-icon {
            color: #1b2299;
        }
        .toggle-pw-btn {
            position: absolute;
            right: 14px;
            color: #9ca3af;
            cursor: pointer;
            padding: 6px;
            border-radius: 8px;
            transition: color 0.2s, background-color 0.2s;
        }
        .toggle-pw-btn:hover {
            color: #1b2299;
            background-color: #f3f4f6;
        }
        .hero-dot { position: absolute; border-radius: 50%; opacity: .15; }
    </style>
</head>
<body class="antialiased text-gray-800 min-h-screen flex bg-gray-50">

    <!-- Left Panel: Branding & Value Props -->
    <div class="hidden lg:flex lg:w-5/12 bg-gradient-to-br from-primary-jlm via-indigo-900 to-secondary-jlm relative overflow-hidden flex-col justify-between p-12 text-white">
        <div class="hero-dot w-96 h-96 bg-white top-[-80px] right-[-80px]"></div>
        <div class="hero-dot w-64 h-64 bg-accent-jlm bottom-[-40px] left-[-40px]"></div>

        <!-- Top logo -->
        <div class="relative z-10">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-3 text-3xl font-black tracking-tight text-white hover:opacity-95 transition group">
                <img src="{{ asset('logo.png') }}" alt="Learnerium Logo" class="h-11 w-auto object-contain drop-shadow-lg transition group-hover:scale-105">
                <span>Learnerium</span>
            </a>
        </div>

        <!-- Hero Content -->
        <div class="relative z-10 my-auto py-8">
            <span class="inline-block px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur border border-white/20 text-accent-jlm font-semibold text-xs uppercase tracking-wider mb-4">
                🎓 Elevate Your Future
            </span>
            <h2 class="text-3xl font-extrabold leading-tight mb-4">
                Start Learning & Teaching on Nigeria's Premier LMS
            </h2>
            <p class="text-base text-white/80 font-light leading-relaxed mb-8">
                Join thousands of students and expert instructors transforming education across Africa and beyond.
            </p>

            <div class="space-y-3.5">
                <div class="flex items-center gap-3.5 bg-white/10 backdrop-blur rounded-2xl p-3.5 border border-white/15 shadow-sm">
                    <div class="w-9 h-9 rounded-xl bg-accent-jlm/20 text-accent-jlm flex items-center justify-center flex-shrink-0 text-base">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div>
                        <p class="font-bold text-sm">Interactive Courses & Quizzes</p>
                        <p class="text-xs text-white/70">Self-paced video lessons with gate checks</p>
                    </div>
                </div>

                <div class="flex items-center gap-3.5 bg-white/10 backdrop-blur rounded-2xl p-3.5 border border-white/15 shadow-sm">
                    <div class="w-9 h-9 rounded-xl bg-pink-400/20 text-pink-300 flex items-center justify-center flex-shrink-0 text-base">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div>
                        <p class="font-bold text-sm">Practical Tasks & Peer Reviews</p>
                        <p class="text-xs text-white/70">Submit links, files, and get peer feedback</p>
                    </div>
                </div>

                <div class="flex items-center gap-3.5 bg-white/10 backdrop-blur rounded-2xl p-3.5 border border-white/15 shadow-sm">
                    <div class="w-9 h-9 rounded-xl bg-emerald-400/20 text-emerald-300 flex items-center justify-center flex-shrink-0 text-base">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <div>
                        <p class="font-bold text-sm">Verified Completion Certificates</p>
                        <p class="text-xs text-white/70">Shareable certificates for your resume & LinkedIn</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer watermark -->
        <div class="relative z-10 text-xs text-white/50 flex items-center justify-between border-t border-white/10 pt-6">
            <span>&copy; {{ date('Y') }} Learnerium</span>
            <span>Powered by <a href="https://jlm.com.ng" target="_blank" rel="noopener" class="inline-flex items-center gap-1 bg-accent-jlm text-primary-jlm px-2 py-0.5 rounded-full text-xs font-extrabold hover:bg-yellow-300 transition"><i class="fas fa-external-link-alt text-[8px]"></i>JLM</a></span>
        </div>
    </div>

    <!-- Right Panel: Registration Form -->
    <div class="w-full lg:w-7/12 flex items-center justify-center px-4 sm:px-8 py-12">
        <div class="w-full max-w-lg">

            <!-- Mobile Header Logo -->
            <div class="lg:hidden text-center mb-8">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 text-3xl font-black text-primary-jlm group">
                    <img src="{{ asset('logo.png') }}" alt="Learnerium Logo" class="h-9 w-auto object-contain transition group-hover:scale-105">
                    <span>Learnerium</span>
                </a>
            </div>

            <!-- Role Selector Tabs -->
            <div class="flex rounded-2xl bg-gray-200/80 p-1.5 mb-8 shadow-inner">
                <a href="{{ route('register') }}"
                   class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-bold transition-all duration-200 {{ ($role ?? 'student') === 'student' ? 'bg-white text-primary-jlm shadow-md' : 'text-gray-600 hover:text-gray-900' }}">
                    <i class="fas fa-user-graduate text-base {{ ($role ?? 'student') === 'student' ? 'text-primary-jlm' : 'text-gray-400' }}"></i>
                    <span>Student Account</span>
                </a>
                <a href="{{ route('instructor.apply') }}"
                   class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-bold transition-all duration-200 {{ ($role ?? 'student') === 'instructor' ? 'bg-white text-secondary-jlm shadow-md' : 'text-gray-600 hover:text-gray-900' }}">
                    <i class="fas fa-chalkboard-teacher text-base {{ ($role ?? 'student') === 'instructor' ? 'text-secondary-jlm' : 'text-gray-400' }}"></i>
                    <span>Apply as Instructor</span>
                </a>
            </div>

            <!-- Main Form Card -->
            <div class="bg-white rounded-3xl shadow-xl p-8 sm:p-10 border border-gray-100 relative">
                
                <div class="mb-8">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-2">
                        @if(($role ?? 'student') === 'instructor')
                            Join as an Instructor 👨‍🏫
                        @else
                            Create Student Account 🚀
                        @endif
                    </h1>
                    <p class="text-gray-500 text-sm">
                        Already registered? 
                        <a href="{{ ($role ?? 'student') === 'instructor' ? route('login.instructor') : route('login.student') }}" class="font-bold text-secondary-jlm hover:underline">
                            Sign in here
                        </a>
                    </p>
                </div>

                <form action="{{ ($role ?? 'student') === 'instructor' ? route('register.instructor.post') : route('register') }}" method="POST" class="space-y-5" id="regForm">
                    @csrf
                    <input type="hidden" name="role" value="{{ $role ?? 'student' }}">

                    <!-- Full Name Field -->
                    <div>
                        <label for="full-name" class="block text-sm font-bold text-gray-700 mb-1.5">
                            Full Name <span class="text-secondary-jlm">*</span>
                        </label>
                        <div class="input-group">
                            <i class="fas fa-user prefix-icon"></i>
                            <input id="full-name" name="name" type="text" autocomplete="name" required
                                   class="input-field no-suffix" placeholder="Enter your full name" value="{{ old('name') }}"
                                   oninput="updateVirtualBadge(this.value)">
                        </div>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1 font-medium"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                        @enderror

                        <!-- Virtual Handle Preview Badge -->
                        <div id="virtualBadgeBox" class="mt-2 hidden">
                            <div class="inline-flex items-center gap-2 bg-primary-jlm/5 border border-primary-jlm/15 rounded-lg px-3 py-1.5 text-xs text-primary-jlm font-semibold">
                                <i class="fas fa-id-badge"></i>
                                <span>Learnerium Student ID: <strong id="virtualBadgeText" class="font-mono text-secondary-jlm">@student</strong></span>
                            </div>
                        </div>
                    </div>

                    <!-- Email Address Field -->
                    <div>
                        <label for="email-address" class="block text-sm font-bold text-gray-700 mb-1.5">
                            Email Address <span class="text-secondary-jlm">*</span>
                        </label>
                        <div class="input-group">
                            <i class="fas fa-envelope prefix-icon"></i>
                            <input id="email-address" name="email" type="email" autocomplete="email" required
                                   class="input-field no-suffix" placeholder="Enter your email address" value="{{ old('email') }}">
                        </div>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1 font-medium"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Fields Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-bold text-gray-700 mb-1.5">
                                Password <span class="text-secondary-jlm">*</span>
                            </label>
                            <div class="input-group">
                                <i class="fas fa-lock prefix-icon"></i>
                                <input id="password" name="password" type="password" autocomplete="new-password" required
                                       class="input-field" placeholder="Min. 8 chars" oninput="checkPasswordStrength(this.value)">
                                <button type="button" class="toggle-pw-btn" onclick="togglePassword('password', this)" title="Toggle Password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1 font-medium"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password-confirm" class="block text-sm font-bold text-gray-700 mb-1.5">
                                Confirm Password <span class="text-secondary-jlm">*</span>
                            </label>
                            <div class="input-group">
                                <i class="fas fa-shield-alt prefix-icon"></i>
                                <input id="password-confirm" name="password_confirmation" type="password" autocomplete="new-password" required
                                       class="input-field" placeholder="Re-type password">
                                <button type="button" class="toggle-pw-btn" onclick="togglePassword('password-confirm', this)" title="Toggle Password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Password Strength Meter Bar -->
                    <div id="pwStrengthMeter" class="hidden space-y-1">
                        <div class="flex justify-between items-center text-xs text-gray-500 font-semibold">
                            <span>Password strength:</span>
                            <span id="pwStrengthText" class="text-gray-400">Too short</span>
                        </div>
                        <div class="w-full bg-gray-200 h-1.5 rounded-full overflow-hidden">
                            <div id="pwStrengthBar" class="h-full w-0 bg-red-500 transition-all duration-300"></div>
                        </div>
                    </div>

                    <!-- Terms Agreement Checkbox -->
                    <div class="pt-1">
                        <label for="agree" class="flex items-start gap-3 cursor-pointer group">
                            <input type="checkbox" id="agree" required class="mt-1 w-4 h-4 text-primary-jlm border-gray-300 rounded focus:ring-primary-jlm cursor-pointer">
                            <span class="text-xs text-gray-600 leading-relaxed">
                                I agree to Learnerium's 
                                <a href="{{ route('about') }}" target="_blank" class="text-primary-jlm font-bold hover:underline">Terms of Service</a> 
                                and 
                                <a href="{{ route('about') }}" target="_blank" class="text-primary-jlm font-bold hover:underline">Privacy Policy</a>.
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="submitBtn" class="w-full bg-secondary-jlm text-white py-3.5 rounded-2xl font-bold text-base hover:bg-secondary-jlm/90 transition shadow-lg hover:shadow-secondary-jlm/30 hover:-translate-y-0.5 transform duration-200 flex items-center justify-center gap-2">
                        <i class="fas fa-user-plus"></i>
                        <span>Complete Registration</span>
                    </button>
                </form>

                <!-- Divider -->
                <div class="mt-8 relative">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                    <div class="relative flex justify-center text-xs"><span class="px-4 bg-white text-gray-400 font-semibold uppercase tracking-wider">Fast & Secure Sign Up</span></div>
                </div>

                <!-- Footer assistance -->
                <div class="mt-6 text-center text-xs text-gray-500">
                    <p><i class="fas fa-shield-halved text-green-500 mr-1"></i> Your personal data is encrypted and securely stored.</p>
                </div>

            </div>

        </div>
    </div>

    <!-- Interactive Scripts -->
    <script>
        // Password Visibility Toggle
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Live Password Strength Checker
        function checkPasswordStrength(val) {
            const meter = document.getElementById('pwStrengthMeter');
            const bar = document.getElementById('pwStrengthBar');
            const text = document.getElementById('pwStrengthText');

            if (!val) {
                meter.classList.add('hidden');
                return;
            }

            meter.classList.remove('hidden');

            let score = 0;
            if (val.length >= 8) score += 25;
            if (/[A-Z]/.test(val)) score += 25;
            if (/[0-9]/.test(val)) score += 25;
            if (/[^A-Za-z0-9]/.test(val)) score += 25;

            bar.style.width = score + '%';

            if (score <= 25) {
                bar.className = 'h-full bg-red-500 transition-all duration-300';
                text.textContent = 'Weak';
                text.className = 'text-red-500 font-bold';
            } else if (score <= 50) {
                bar.className = 'h-full bg-yellow-500 transition-all duration-300';
                text.textContent = 'Fair';
                text.className = 'text-yellow-600 font-bold';
            } else if (score <= 75) {
                bar.className = 'h-full bg-blue-500 transition-all duration-300';
                text.textContent = 'Good';
                text.className = 'text-blue-600 font-bold';
            } else {
                bar.className = 'h-full bg-green-500 transition-all duration-300';
                text.textContent = 'Strong 💪';
                text.className = 'text-green-600 font-bold';
            }
        }

        // Live Learnerium Handle Preview (surnamefirstname format)
        function updateVirtualBadge(name) {
            const box = document.getElementById('virtualBadgeBox');
            const text = document.getElementById('virtualBadgeText');

            if (!name || name.trim().length < 2) {
                box.classList.add('hidden');
                return;
            }

            // Convert name to clean surnamefirstname format without dots
            const parts = name.trim().toLowerCase().replace(/[^a-z0-9\s]/g, '').split(/\s+/).filter(Boolean);
            const clean = parts.length > 1 ? (parts[parts.length - 1] + parts[0]) : (parts[0] || '');
            if (clean) {
                text.textContent = clean + '@learnerium.jlm.com.ng';
                box.classList.remove('hidden');
            } else {
                box.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
