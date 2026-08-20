<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Learnerium') }} - Elevating Education</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-jlm': '#1b2299',          // Deep Blue
                        'primary-jlm-dark': '#141a73',     // Darker Blue for hover
                        'secondary-jlm': '#e4306d',        // Vibrant Pink
                        'accent-jlm': '#f7de7a',           // Soft Yellow
                        'gray-jlm-light': '#f8f8f8',       // Light gray background
                    },
                    fontFamily: {
                        'sans': ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            background-color: #f8f8f8;
        }
        /* Custom scrollbar */
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
<body class="antialiased text-gray-800 min-h-screen flex flex-col justify-between">
    <div id="app" class="flex-grow flex flex-col">
        <!-- Unified Navbar -->
        <nav class="bg-white shadow-md p-4 sticky top-0 z-50">
            <div class="container mx-auto flex justify-between items-center">
                <a href="{{ url('/') }}" class="text-3xl font-extrabold text-primary-jlm tracking-tight">Learnerium</a>
                
                <!-- Desktop Nav Links -->
                <div class="hidden lg:flex items-center space-x-6">
                    <a href="{{ url('/') }}" class="text-primary-jlm hover:text-secondary-jlm font-medium transition duration-300">Home</a>
                    <a href="{{ route('courses') }}" class="text-primary-jlm hover:text-secondary-jlm font-medium transition duration-300">Courses</a>
                    <a href="{{ route('instructors') }}" class="text-primary-jlm hover:text-secondary-jlm font-medium transition duration-300">Instructors</a>
                    <a href="{{ route('about') }}" class="text-primary-jlm hover:text-secondary-jlm font-medium transition duration-300">About Us</a>
                    <a href="{{ route('contact') }}" class="text-primary-jlm hover:text-secondary-jlm font-medium transition duration-300">Contact</a>
                </div>

                <!-- Auth/Menu -->
                <div class="hidden lg:flex items-center space-x-4">
                    @guest
                        <div class="relative" id="signInDropdownWrap">
                            <button id="signInDropdownBtn" class="bg-primary-jlm text-white px-5 py-2.5 rounded-lg hover:bg-primary-jlm-dark transition duration-300 shadow-md font-semibold text-sm flex items-center space-x-2">
                                <span>Sign In</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div id="signInDropdownMenu" class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl py-2 z-20 hidden border border-gray-100 origin-top-right">
                                <a href="{{ route('login.student') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-primary-jlm/5 hover:text-primary-jlm font-medium">
                                    <i class="fas fa-user-graduate mr-2.5 text-primary-jlm text-base"></i>Student Sign In
                                </a>
                                <a href="{{ route('login.instructor') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-secondary-jlm/5 hover:text-secondary-jlm font-medium border-t border-gray-100">
                                    <i class="fas fa-chalkboard-teacher mr-2.5 text-secondary-jlm text-base"></i>Instructor Portal
                                </a>
                            </div>
                        </div>
                        <a href="{{ route('register') }}" class="border border-primary-jlm text-primary-jlm px-5 py-2.5 rounded-lg hover:bg-primary-jlm/5 transition duration-300 font-semibold text-sm">Register</a>
                    @else
                        <div class="relative" id="userDropdownWrap">
                            <button id="userDropdownBtn" class="flex items-center text-primary-jlm focus:outline-none hover:text-secondary-jlm font-semibold space-x-2">
                                <img src="https://placehold.co/32x32/1b2299/f7de7a?text={{ urlencode(substr(Auth::user()->name, 0, 2)) }}" alt="User Profile" class="w-8 h-8 rounded-full border-2 border-primary-jlm">
                                <span>{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div id="userDropdownMenu" class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-xl py-2 z-20 hidden border border-gray-100 origin-top-right">
                                <div class="px-4 py-2 border-b border-gray-100 mb-1">
                                    <p class="text-xs text-gray-400 font-medium">Logged in as</p>
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary-jlm transition"><i class="fas fa-th-large mr-3 text-gray-400"></i>Dashboard</a>
                                @if(Auth::user()->isInstructor())
                                    <a href="{{ route('instructor.manage.courses') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary-jlm transition"><i class="fas fa-tasks mr-3 text-gray-400"></i>Manage Courses</a>
                                @else
                                    <a href="{{ route('student.courses') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary-jlm transition"><i class="fas fa-graduation-cap mr-3 text-gray-400"></i>My Courses</a>
                                    <a href="{{ route('student.progress') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary-jlm transition"><i class="fas fa-chart-pie mr-3 text-gray-400"></i>My Progress</a>
                                @endif
                                <a href="{{ url('/profile') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary-jlm transition"><i class="fas fa-user-circle mr-3 text-gray-400"></i>Profile</a>
                                <a href="{{ url('/settings') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary-jlm transition"><i class="fas fa-cog mr-3 text-gray-400"></i>Settings</a>
                                <form method="POST" action="{{ route('logout') }}" class="block border-t border-gray-100 mt-1">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition"><i class="fas fa-sign-out-alt mr-3"></i>Logout</button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobileMenuBtn" class="lg:hidden text-primary-jlm focus:outline-none p-2 rounded-lg hover:bg-gray-100 transition">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>

            <!-- Mobile Nav Links -->
            <div id="mobileMenu" class="hidden lg:hidden mt-4 bg-gray-50 rounded-xl p-4 border border-gray-100 space-y-3">
                <a href="{{ url('/') }}" class="block px-3 py-2 text-primary-jlm hover:text-secondary-jlm hover:bg-white rounded-lg transition font-medium">Home</a>
                <a href="{{ route('courses') }}" class="block px-3 py-2 text-primary-jlm hover:text-secondary-jlm hover:bg-white rounded-lg transition font-medium">Courses</a>
                <a href="{{ route('instructors') }}" class="block px-3 py-2 text-primary-jlm hover:text-secondary-jlm hover:bg-white rounded-lg transition font-medium">Instructors</a>
                <a href="{{ route('about') }}" class="block px-3 py-2 text-primary-jlm hover:text-secondary-jlm hover:bg-white rounded-lg transition font-medium">About Us</a>
                <a href="{{ route('contact') }}" class="block px-3 py-2 text-primary-jlm hover:text-secondary-jlm hover:bg-white rounded-lg transition font-medium">Contact</a>
                
                <hr class="border-gray-200">

                @guest
                    <div class="space-y-2 pt-2">
                        <a href="{{ route('login.student') }}" class="flex items-center justify-center gap-2 bg-primary-jlm text-white py-2.5 rounded-lg font-semibold text-sm shadow">
                            <i class="fas fa-user-graduate"></i>Student Sign In
                        </a>
                        <a href="{{ route('login.instructor') }}" class="flex items-center justify-center gap-2 bg-secondary-jlm text-white py-2.5 rounded-lg font-semibold text-sm shadow">
                            <i class="fas fa-chalkboard-teacher"></i>Instructor Portal
                        </a>
                        <a href="{{ route('register') }}" class="block text-center border border-primary-jlm text-primary-jlm py-2.5 rounded-lg font-semibold text-sm">Register</a>
                    </div>
                @else
                    <div class="space-y-1">
                        <div class="px-3 py-2 text-xs text-gray-400 font-semibold uppercase">My Account</div>
                        <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-white rounded-lg transition">Dashboard</a>
                        @if(Auth::user()->isInstructor())
                            <a href="{{ route('instructor.manage.courses') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-white rounded-lg transition">Manage Courses</a>
                        @else
                            <a href="{{ route('student.courses') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-white rounded-lg transition">My Courses</a>
                            <a href="{{ route('student.progress') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-white rounded-lg transition">My Progress</a>
                        @endif
                        <a href="{{ url('/profile') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-white rounded-lg transition">Profile</a>
                        <a href="{{ url('/settings') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-white rounded-lg transition">Settings</a>
                        <form method="POST" action="{{ route('logout') }}" class="block pt-2">
                            @csrf
                            <button type="submit" class="w-full text-center bg-red-50 text-red-600 py-2.5 rounded-lg font-semibold text-sm hover:bg-red-100 transition">Logout</button>
                        </form>
                    </div>
                @endguest
            </div>
        </nav>

        <!-- Main Content Area -->
        <main class="flex-grow flex flex-col justify-start">
            <!-- Flash Session Alerts -->
            <div class="container mx-auto px-4 mt-6">
                @if (session('status'))
                    <div class="max-w-2xl mx-auto mb-6 bg-emerald-50 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-xl flex items-center space-x-3 shadow-sm" role="alert">
                        <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
                        <span class="font-medium text-sm">{{ session('status') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="max-w-2xl mx-auto mb-6 bg-rose-50 border border-rose-300 text-rose-800 px-4 py-3 rounded-xl flex items-center space-x-3 shadow-sm" role="alert">
                        <i class="fas fa-exclamation-circle text-rose-500 text-lg"></i>
                        <span class="font-medium text-sm">{{ session('error') }}</span>
                    </div>
                @endif
                @if (session('info'))
                    <div class="max-w-2xl mx-auto mb-6 bg-blue-50 border border-blue-300 text-blue-800 px-4 py-3 rounded-xl flex items-center space-x-3 shadow-sm" role="alert">
                        <i class="fas fa-info-circle text-blue-500 text-lg"></i>
                        <span class="font-medium text-sm">{{ session('info') }}</span>
                    </div>
                @endif
            </div>

            @yield('content')
        </main>
    </div>

    <!-- Unified Footer -->
    <footer class="bg-gray-900 text-white py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center md:items-start">
            <div class="mb-8 md:mb-0 text-center md:text-left">
                <h3 class="text-2xl font-extrabold text-white mb-2 tracking-tight">Learnerium</h3>
                <p class="text-gray-400 text-sm">&copy; {{ date('Y') }} Learnerium. Powered by JLM. All rights reserved.</p>
            </div>
            <div class="grid grid-cols-2 gap-8 md:grid-cols-3 md:gap-12 text-center md:text-left">
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-accent-jlm">Company</h4>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-white transition duration-200">About Us</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-white transition duration-200">Contact Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-200">Careers</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-accent-jlm">Explore</h4>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('courses') }}" class="text-gray-400 hover:text-white transition duration-200">Courses</a></li>
                        <li><a href="{{ route('instructors') }}" class="text-gray-400 hover:text-white transition duration-200">Instructors</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-200">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-accent-jlm">Legal</h4>
                    <ul class="space-y-2.5">
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-200">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-200">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile menu toggle + Dropdown hover scripts -->
    <script>
        // Mobile menu
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));
        }

        // Generic sticky-hover dropdown: keeps menu open when moving from trigger to menu
        function stickyDropdown(wrapperId, btnId, menuId) {
            const wrap = document.getElementById(wrapperId);
            const btn  = document.getElementById(btnId);
            const menu = document.getElementById(menuId);
            if (!wrap || !btn || !menu) return;

            let leaveTimer = null;

            function showMenu() {
                clearTimeout(leaveTimer);
                menu.classList.remove('hidden');
            }
            function hideMenu() {
                leaveTimer = setTimeout(() => menu.classList.add('hidden'), 120);
            }

            // Both the button and the menu keep it open
            btn.addEventListener('mouseenter', showMenu);
            btn.addEventListener('mouseleave', hideMenu);
            menu.addEventListener('mouseenter', showMenu);
            menu.addEventListener('mouseleave', hideMenu);

            // Also close on click outside
            document.addEventListener('click', (e) => {
                if (!wrap.contains(e.target)) menu.classList.add('hidden');
            });
        }

        stickyDropdown('signInDropdownWrap', 'signInDropdownBtn', 'signInDropdownMenu');
        stickyDropdown('userDropdownWrap',   'userDropdownBtn',   'userDropdownMenu');
    </script>
</body>
</html>
