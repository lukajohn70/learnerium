<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learnerium - Register</title>
    <!-- Include Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" xintegrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Tailwind CSS configuration for custom colors and font -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-jlm': '#1b2299',        // Deep Blue
                        'primary-jlm-dark': '#141a73',   // Slightly darker primary for hover
                        'secondary-jlm': '#e4306d',      // Vibrant Pink
                        'accent-jlm': '#f7de7a',         // Soft Yellow
                        'gray-jlm-light': '#f8f8f8',     // Custom light gray for backgrounds
                    },
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'], // Define Inter font family
                    }
                }
            }
        }
    </script>
    <!-- Custom CSS for Inter font and scrollbar styling -->
    <style>
        body {
            font-family: 'Inter', sans-serif; /* Apply Inter font */
            background-color: #f8f8f8; /* Light gray background from brand guide */
        }
        /* Custom scrollbar for a cleaner look */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body class="antialiased text-gray-800">

    <div class="min-h-screen flex items-center justify-center bg-gray-jlm-light py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-lg shadow-xl border-t-4 border-primary-jlm">
            <div>
                <a href="{{ url('/') }}" class="block text-center text-5xl font-extrabold text-primary-jlm">Learnerium</a>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Sign up for your {{ $role ?? 'account' }}
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Or <a href="{{ route('login') }}" class="font-medium text-secondary-jlm hover:text-secondary-jlm/80">already have an account?</a>
                </p>
            </div>
            <form class="mt-8 space-y-6" action="{{ ($role ?? 'student') === 'instructor' ? route('register.instructor.post') : route('register') }}" method="POST">
                @csrf
                <input type="hidden" name="role" value="{{ $role ?? 'student' }}">

                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="full-name" class="sr-only">Full Name</label>
                        <input id="full-name" name="name" type="text" autocomplete="name" required
                               class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-primary-jlm focus:border-primary-jlm focus:z-10 sm:text-sm"
                               placeholder="Full Name" value="{{ old('name') }}">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email-address" class="sr-only">Email address</label>
                        <input id="email-address" name="email" type="email" autocomplete="email" required
                               class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-primary-jlm focus:border-primary-jlm focus:z-10 sm:text-sm"
                               placeholder="Email address" value="{{ old('email') }}">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input id="password" name="password" type="password" autocomplete="new-password" required
                               class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-primary-jlm focus:border-primary-jlm focus:z-10 sm:text-sm"
                               placeholder="Password">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password-confirm" class="sr-only">Confirm Password</label>
                        <input id="password-confirm" name="password_confirmation" type="password" autocomplete="new-password" required
                               class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-primary-jlm focus:border-primary-jlm focus:z-10 sm:text-sm"
                               placeholder="Confirm Password">
                        @error('password_confirmation')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <button type="submit"
                                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-lg font-medium rounded-md text-white bg-secondary-jlm hover:bg-secondary-jlm/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-jlm transition duration-300">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-secondary-jlm group-hover:text-pink-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM15.75 6.75a.75.75 0 00-1.5 0v2.25H12a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H18a.75.75 0 000-1.5h-2.25V6.75z" />
                            </svg>
                        </span>
                        Sign up
                    </button>
                </div>
            </form>
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Or sign up with</span>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    <div>
                        <a href="#" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition duration-300">
                            <img class="h-5 w-5 mr-2" src="https://www.svgrepo.com/show/303108/google-icon-logo.svg" alt="Google">
                            Google
                        </a>
                    </div>
                    <div>
                        <a href="#" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition duration-300">
                            <i class="fab fa-github h-5 w-5 mr-2 text-gray-700"></i>
                            GitHub
                        </a>
                    </div>
                    <div>
                        <a href="#" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition duration-300">
                            <i class="fab fa-facebook-f h-5 w-5 mr-2 text-gray-700"></i>
                            Facebook
                        </a>
                    </div>
                    <div>
                        <a href="#" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition duration-300">
                            <i class="fab fa-x-twitter h-5 w-5 mr-2 text-gray-700"></i>
                            X (Twitter)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
