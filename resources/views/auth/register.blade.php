<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account — Learnerium</title>
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
            transition: border-color .2s, box-shadow .2s; background: #fff;
        }
        .input-field:focus {
            border-color: #1b2299;
            box-shadow: 0 0 0 3px rgba(27,34,153,.12);
        }
        .hero-dot { position: absolute; border-radius: 50%; opacity: .15; }
    </style>
</head>
<body class="antialiased text-gray-800 min-h-screen flex">

    <!-- Left Panel: Branding -->
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-secondary-jlm via-pink-700 to-primary-jlm relative overflow-hidden flex-col items-center justify-center p-12 text-white">
        <div class="hero-dot w-96 h-96 bg-white top-[-80px] right-[-80px]"></div>
        <div class="hero-dot w-64 h-64 bg-accent-jlm bottom-[-40px] left-[-40px]"></div>
        <div class="hero-dot w-40 h-40 bg-primary-jlm top-1/3 right-1/4"></div>

        <div class="relative z-10 text-center max-w-md">
            <a href="{{ url('/') }}" class="text-5xl font-black tracking-tight block mb-6">Learnerium</a>
            <p class="text-xl font-light text-white/80 mb-10 leading-relaxed">
                Join thousands of learners and instructors building the future of education.
            </p>
            <div class="space-y-3">
                @foreach(['Access 500+ premium courses', 'Track progress with analytics', 'Earn shareable certificates', 'Learn from expert instructors'] as $benefit)
                <div class="flex items-center gap-3 bg-white/10 backdrop-blur rounded-xl px-4 py-3 text-left">
                    <i class="fas fa-check-circle text-accent-jlm text-lg flex-shrink-0"></i>
                    <span class="font-medium text-sm">{{ $benefit }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Right Panel: Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12 bg-gray-50">
        <div class="w-full max-w-md">
            <!-- Mobile logo -->
            <div class="lg:hidden text-center mb-8">
                <a href="{{ url('/') }}" class="text-4xl font-black text-primary-jlm">Learnerium</a>
            </div>

            <!-- Role tabs -->
            <div class="flex rounded-xl bg-gray-100 p-1 mb-6">
                <a href="{{ route('register') }}"
                   class="flex-1 text-center py-2.5 rounded-lg text-sm font-semibold transition {{ ($role ?? 'student') === 'student' ? 'bg-white text-primary-jlm shadow' : 'text-gray-500 hover:text-gray-700' }}">
                    <i class="fas fa-graduation-cap mr-1.5"></i>Student
                </a>
                <a href="{{ route('register.instructor') }}"
                   class="flex-1 text-center py-2.5 rounded-lg text-sm font-semibold transition {{ ($role ?? 'student') === 'instructor' ? 'bg-white text-secondary-jlm shadow' : 'text-gray-500 hover:text-gray-700' }}">
                    <i class="fas fa-chalkboard-teacher mr-1.5"></i>Instructor
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                <div class="mb-7">
                    <h1 class="text-2xl font-extrabold text-gray-900 mb-1">
                        @if(($role ?? 'student') === 'instructor') Become an Instructor @else Create your account @endif
                    </h1>
                    <p class="text-gray-500 text-sm">
                        Already have an account? <a href="{{ route('login') }}" class="font-bold text-secondary-jlm hover:text-secondary-jlm/80">Sign in</a>
                    </p>
                </div>

                <form action="{{ ($role ?? 'student') === 'instructor' ? route('register.instructor.post') : route('register') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="role" value="{{ $role ?? 'student' }}">

                    <div>
                        <label for="full-name" class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name</label>
                        <input id="full-name" name="name" type="text" autocomplete="name" required
                               class="input-field" placeholder="John Doe" value="{{ old('name') }}">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email-address" class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
                        <input id="email-address" name="email" type="email" autocomplete="email" required
                               class="input-field" placeholder="your@email.com" value="{{ old('email') }}">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                            <input id="password" name="password" type="password" autocomplete="new-password" required
                                   class="input-field" placeholder="••••••••">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password-confirm" class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm</label>
                            <input id="password-confirm" name="password_confirmation" type="password" autocomplete="new-password" required
                                   class="input-field" placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-start gap-2.5">
                        <input type="checkbox" id="agree" required class="mt-0.5 rounded border-gray-300 text-primary-jlm">
                        <label for="agree" class="text-sm text-gray-500 leading-snug">
                            I agree to the <a href="#" class="text-secondary-jlm font-semibold hover:underline">Terms of Service</a> and <a href="#" class="text-secondary-jlm font-semibold hover:underline">Privacy Policy</a>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-secondary-jlm text-white py-3.5 rounded-xl font-bold text-base hover:bg-secondary-jlm/90 transition shadow-md hover:shadow-lg">
                        <i class="fas fa-user-plus mr-2"></i>Create Account
                    </button>
                </form>

                <div class="mt-6 relative">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                    <div class="relative flex justify-center text-xs"><span class="px-3 bg-white text-gray-400 font-medium">or sign up with</span></div>
                </div>

                <div class="mt-5 grid grid-cols-2 gap-3">
                    <a href="#" class="flex items-center justify-center gap-2 px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm">
                        <img class="h-4 w-4" src="https://www.svgrepo.com/show/303108/google-icon-logo.svg" alt="Google"> Google
                    </a>
                    <a href="#" class="flex items-center justify-center gap-2 px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm">
                        <i class="fab fa-github text-gray-800"></i> GitHub
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
