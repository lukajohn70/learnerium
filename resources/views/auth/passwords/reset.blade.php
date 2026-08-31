<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password — Learnerium</title>
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
                    },
                    fontFamily: { 'inter': ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .input-group { position: relative; display: flex; items-center: center; }
        .input-group i.prefix-icon { position: absolute; left: 16px; color: #9ca3af; font-size: 14px; pointer-events: none; transition: color 0.2s; }
        .input-field { width: 100%; padding: 13px 44px 13px 44px; border: 1.5px solid #e5e7eb; border-radius: 14px; font-size: 14.5px; outline: none; transition: all .2s ease-in-out; background: #ffffff; color: #1f2937; }
        .input-field:focus { border-color: #1b2299; box-shadow: 0 0 0 4px rgba(27,34,153,.1); }
        .input-group:focus-within i.prefix-icon { color: #1b2299; }
        .toggle-pw-btn { position: absolute; right: 14px; color: #9ca3af; cursor: pointer; padding: 6px; border-radius: 8px; transition: color 0.2s, background-color 0.2s; }
        .toggle-pw-btn:hover { color: #1b2299; background-color: #f3f4f6; }
        .hero-dot { position: absolute; border-radius: 50%; opacity: .15; }
    </style>
</head>
<body class="antialiased text-gray-800 min-h-screen flex bg-gray-50">

    <!-- Left Panel: Branding & Value Props (5/12 width) -->
    <div class="hidden lg:flex lg:w-5/12 bg-gradient-to-br from-primary-jlm via-indigo-900 to-secondary-jlm relative overflow-hidden flex-col justify-between p-12 text-white">
        <div class="hero-dot w-96 h-96 bg-white top-[-80px] right-[-80px]"></div>
        <div class="hero-dot w-64 h-64 bg-accent-jlm bottom-[-40px] left-[-40px]"></div>

        <!-- Top logo -->
        <div class="relative z-10">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-3 bg-white/90 backdrop-blur-md px-5 py-2.5 rounded-2xl shadow-xl border border-white/40 group transition hover:scale-105">
                <img src="{{ asset('logo-only.png') }}" alt="Learnerium Logo" class="h-9 w-auto object-contain">
                <span class="text-2xl font-black bg-gradient-to-r from-[#1b2299] to-[#e4306d] bg-clip-text text-transparent tracking-tight">Learnerium</span>
            </a>
        </div>

        <!-- Hero Content -->
        <div class="relative z-10 my-auto py-8">
            <span class="inline-block px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur border border-white/20 text-accent-jlm font-semibold text-xs uppercase tracking-wider mb-4">
                🔑 Create New Password
            </span>
            <h2 class="text-3xl font-extrabold leading-tight mb-4">
                Set a Strong New Password
            </h2>
            <p class="text-white/80 text-sm leading-relaxed mb-8">
                Choose a strong password containing letters, numbers, and special characters to protect your account.
            </p>

            <div class="space-y-4 text-sm font-medium text-white/90">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center text-accent-jlm"><i class="fas fa-key text-xs"></i></div>
                    <span>Minimum 8 Characters</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center text-accent-jlm"><i class="fas fa-shield-alt text-xs"></i></div>
                    <span>Encrypted & Hashed in Database</span>
                </div>
            </div>
        </div>

        <!-- Footer link -->
        <div class="relative z-10 text-xs text-white/60">
            &copy; {{ date('Y') }} Learnerium. Powered by <a href="https://jlm.com.ng" class="text-accent-jlm hover:underline font-bold">JLM</a>.
        </div>
    </div>

    <!-- Right Panel: Set New Password Form (7/12 width) -->
    <div class="w-full lg:w-7/12 flex items-center justify-center p-6 md:p-12 overflow-y-auto">
        <div class="max-w-md w-full space-y-8">
            
            <!-- Mobile Logo -->
            <div class="lg:hidden text-center mb-6">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5">
                    <img src="{{ asset('logo-only.png') }}" alt="Learnerium Logo" class="h-10 w-auto object-contain">
                    <span class="text-2xl font-black text-primary-jlm tracking-tight">Learnerium</span>
                </a>
            </div>

            <div class="text-center lg:text-left">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Set New Password</h1>
                <p class="text-sm text-gray-500 mt-2">Enter your email and choose your new password below.</p>
            </div>

            <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Email Address</label>
                    <div class="input-group">
                        <i class="fas fa-envelope prefix-icon"></i>
                        <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="your.email@example.com" class="input-field @error('email') border-red-500 @enderror">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs font-semibold mt-1 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- New Password Input -->
                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">New Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock prefix-icon"></i>
                        <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="At least 8 characters" class="input-field @error('password') border-red-500 @enderror">
                        <button type="button" class="toggle-pw-btn" onclick="togglePassword('password', 'pw-icon-1')">
                            <i class="fas fa-eye" id="pw-icon-1"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs font-semibold mt-1 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Confirm Password Input -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Confirm New Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock prefix-icon"></i>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Re-enter new password" class="input-field">
                        <button type="button" class="toggle-pw-btn" onclick="togglePassword('password_confirmation', 'pw-icon-2')">
                            <i class="fas fa-eye" id="pw-icon-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-secondary-jlm hover:bg-secondary-jlm/90 text-white py-3.5 rounded-2xl font-extrabold text-sm shadow-lg shadow-secondary-jlm/20 hover:scale-[1.01] transition duration-200 flex items-center justify-center gap-2">
                    <i class="fas fa-key text-xs"></i> Update Password
                </button>
            </form>

            <div class="text-center pt-4 border-t border-gray-100">
                <a href="{{ route('login') }}" class="text-xs font-bold text-primary-jlm hover:text-secondary-jlm transition">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Sign In
                </a>
            </div>

        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
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
    </script>
</body>
</html>
