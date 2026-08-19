<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@if(($role ?? 'student') === 'instructor') Instructor Portal Sign In @else Student Sign In @endif — Learnerium</title>
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
        .input-field {
            width: 100%; padding: 12px 16px; border: 1.5px solid #e5e7eb;
            border-radius: 12px; font-size: 15px; outline: none;
            transition: border-color .2s, box-shadow .2s;
            background: #fff;
        }
        .input-field:focus {
            border-color: @if(($role ?? 'student') === 'instructor') #e4306d @else #1b2299 @endif;
            box-shadow: 0 0 0 3px @if(($role ?? 'student') === 'instructor') rgba(228,48,109,.12) @else rgba(27,34,153,.12) @endif;
        }
        .hero-dot { position: absolute; border-radius: 50%; opacity: .15; }
    </style>
</head>
<body class="antialiased text-gray-800 min-h-screen flex">

    <!-- Left Panel: Branding -->
    <div class="hidden lg:flex lg:w-1/2 @if(($role ?? 'student') === 'instructor') bg-gradient-to-br from-secondary-jlm via-pink-700 to-primary-jlm @else bg-gradient-to-br from-primary-jlm via-blue-800 to-secondary-jlm @endif relative overflow-hidden flex-col items-center justify-center p-12 text-white">
        <!-- Decorative blobs -->
        <div class="hero-dot w-96 h-96 bg-white top-[-80px] left-[-80px]"></div>
        <div class="hero-dot w-64 h-64 bg-accent-jlm bottom-[-40px] right-[-40px]"></div>
        <div class="hero-dot w-32 h-32 bg-secondary-jlm top-1/2 left-1/4"></div>

        <div class="relative z-10 text-center max-w-md">
            <a href="{{ url('/') }}" class="text-5xl font-black tracking-tight block mb-6">
                Learnerium
            </a>
            @if(($role ?? 'student') === 'instructor')
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
    <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12 bg-gray-50">
        <div class="w-full max-w-md">
            <!-- Mobile logo -->
            <div class="lg:hidden text-center mb-8">
                <a href="{{ url('/') }}" class="text-4xl font-black text-primary-jlm">Learnerium</a>
            </div>

            <!-- Role Switcher Tabs -->
            <div class="flex rounded-xl bg-gray-200 p-1 mb-6">
                <a href="{{ route('login.student') }}"
                   class="flex-1 text-center py-2.5 rounded-lg text-sm font-semibold transition {{ ($role ?? 'student') === 'student' ? 'bg-white text-primary-jlm shadow' : 'text-gray-500 hover:text-gray-700' }}">
                    <i class="fas fa-user-graduate mr-1.5"></i>Student Login
                </a>
                <a href="{{ route('login.instructor') }}"
                   class="flex-1 text-center py-2.5 rounded-lg text-sm font-semibold transition {{ ($role ?? 'student') === 'instructor' ? 'bg-white text-secondary-jlm shadow' : 'text-gray-500 hover:text-gray-700' }}">
                    <i class="fas fa-chalkboard-teacher mr-1.5"></i>Instructor Login
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                <div class="mb-7">
                    <h1 class="text-2xl font-extrabold text-gray-900 mb-1">
                        @if(($role ?? 'student') === 'instructor') Instructor Portal @else Welcome back! @endif
                    </h1>
                    <p class="text-gray-500 text-sm">
                        @if(($role ?? 'student') === 'instructor') Sign in to access your instructor dashboard. @else Sign in to continue learning. @endif
                    </p>
                </div>

                @if(session('status'))
                    <div class="mb-5 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-500"></i>{{ session('status') }}
                    </div>
                @endif

                <form action="{{ ($role ?? 'student') === 'instructor' ? route('login.instructor.post') : route('login.student.post') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email-address" class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
                        <input id="email-address" name="email" type="email" autocomplete="email" required
                               class="input-field" placeholder="your@email.com" value="{{ old('email') }}">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                   class="input-field pr-12" placeholder="••••••••">
                            <button type="button" onclick="const p=document.getElementById('password'); p.type = p.type==='password'?'text':'password'; this.querySelector('i').classList.toggle('fa-eye'); this.querySelector('i').classList.toggle('fa-eye-slash');"
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
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-primary-jlm" name="remember">
                            <span class="text-sm text-gray-600">Remember me</span>
                        </label>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm font-semibold text-secondary-jlm hover:text-secondary-jlm/80 transition">Forgot password?</a>
                        @endif
                    </div>

                    <button type="submit" class="w-full @if(($role ?? 'student') === 'instructor') bg-secondary-jlm hover:bg-secondary-jlm/90 @else bg-primary-jlm hover:bg-primary-jlm-dark @endif text-white py-3.5 rounded-xl font-bold text-base transition shadow-md hover:shadow-lg">
                        <i class="fas fa-sign-in-alt mr-2"></i>Sign In as @if(($role ?? 'student') === 'instructor') Instructor @else Student @endif
                    </button>
                </form>

                <div class="mt-6 relative">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                    <div class="relative flex justify-center text-xs"><span class="px-3 bg-white text-gray-400 font-medium">or continue with</span></div>
                </div>

                <div class="mt-5 grid grid-cols-2 gap-3">
                    <a href="#" class="flex items-center justify-center gap-2 px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm">
                        <img class="h-4 w-4" src="https://www.svgrepo.com/show/303108/google-icon-logo.svg" alt="Google"> Google
                    </a>
                    <a href="#" class="flex items-center justify-center gap-2 px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm">
                        <i class="fab fa-github text-gray-800"></i> GitHub
                    </a>
                </div>

                <p class="mt-7 text-center text-sm text-gray-500">
                    Don't have an account?
                    @if(($role ?? 'student') === 'instructor')
                        <a href="{{ route('register.instructor') }}" class="font-bold text-secondary-jlm hover:text-secondary-jlm/80 transition">Register as Instructor</a>
                    @else
                        <a href="{{ route('register') }}" class="font-bold text-secondary-jlm hover:text-secondary-jlm/80 transition">Create Student account</a>
                    @endif
                </p>
            </div>
        </div>
    </div>
</body>
</html>
