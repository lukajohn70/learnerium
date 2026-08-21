<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Learnerium') . ' - Elevating Education')</title>

    {{-- SEO: Meta Description (override per-page with @section('meta_description')) --}}
    <meta name="description" content="@yield('meta_description', 'Learnerium is an innovative online learning platform by JLM — offering expert-led courses, interactive lessons, and verified certificates to learners across Africa and beyond.')">

    {{-- SEO: Canonical URL (auto-resolves to current page URL) --}}
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- SEO: Open Graph (Facebook, WhatsApp, LinkedIn) --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="Learnerium">
    <meta property="og:title" content="@yield('og_title', 'Learnerium - Elevating Education')">
    <meta property="og:description" content="@yield('og_description', 'Expert-led online courses, interactive lessons, and verified certificates — powered by JLM.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('logo-only.png'))">
    <meta property="og:locale" content="en_NG">

    {{-- SEO: Twitter / X Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@jlmng">
    <meta name="twitter:title" content="@yield('og_title', 'Learnerium - Elevating Education')">
    <meta name="twitter:description" content="@yield('og_description', 'Expert-led online courses, interactive lessons, and verified certificates — powered by JLM.')">
    <meta name="twitter:image" content="@yield('og_image', asset('logo-only.png'))">

    {{-- Per-page JSON-LD Structured Data slot --}}
    @stack('structured_data')

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo-only.png') }}">
    <link rel="shortcut icon" href="{{ asset('logo-only.png') }}">

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
        :root {
            --primary-jlm: #1b2299;
            --primary-jlm-dark: #141a73;
            --secondary-jlm: #e4306d;
            --secondary-jlm-dark: #c22055;
            --accent-jlm: #f7de7a;
            --accent-jlm-hover: #f5d454;
        }
        
        body {
            background-color: #f8fafc;
            color: #1e293b;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        /* JLM Global Utility Overrides */
        .bg-primary-jlm { background-color: #1b2299 !important; }
        .bg-primary-jlm-dark { background-color: #141a73 !important; }
        .text-primary-jlm { color: #1b2299 !important; }
        .border-primary-jlm { border-color: #1b2299 !important; }

        .bg-secondary-jlm { background-color: #e4306d !important; }
        .bg-secondary-jlm-dark { background-color: #c22055 !important; }
        .text-secondary-jlm { color: #e4306d !important; }
        .border-secondary-jlm { border-color: #e4306d !important; }

        .bg-accent-jlm { background-color: #f7de7a !important; color: #1b2299 !important; }
        .text-accent-jlm { color: #f7de7a !important; }
        .border-accent-jlm { border-color: #f7de7a !important; }

        .bg-jlm-gradient { background: linear-gradient(135deg, #1b2299 0%, #7b1fa2 50%, #e4306d 100%) !important; }
        .bg-jlm-header { background: linear-gradient(90deg, #1b2299 0%, #e4306d 100%) !important; }

        /* JLM Button System */
        .btn-jlm-primary {
            background-color: #1b2299 !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            border-radius: 0.75rem !important;
            transition: all 0.2s ease-in-out !important;
            box-shadow: 0 4px 12px rgba(27, 34, 153, 0.2) !important;
        }
        .btn-jlm-primary:hover {
            background-color: #141a73 !important;
            color: #ffffff !important;
            box-shadow: 0 6px 16px rgba(27, 34, 153, 0.35) !important;
        }

        .btn-jlm-secondary {
            background-color: #e4306d !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            border-radius: 0.75rem !important;
            transition: all 0.2s ease-in-out !important;
            box-shadow: 0 4px 12px rgba(228, 48, 109, 0.2) !important;
        }
        .btn-jlm-secondary:hover {
            background-color: #c22055 !important;
            color: #ffffff !important;
            box-shadow: 0 6px 16px rgba(228, 48, 109, 0.35) !important;
        }

        .btn-jlm-accent {
            background-color: #f7de7a !important;
            color: #1b2299 !important;
            font-weight: 800 !important;
            border-radius: 0.75rem !important;
            transition: all 0.2s ease-in-out !important;
            box-shadow: 0 4px 12px rgba(247, 222, 122, 0.3) !important;
        }
        .btn-jlm-accent:hover {
            background-color: #f5d454 !important;
            color: #141a73 !important;
            box-shadow: 0 6px 16px rgba(247, 222, 122, 0.45) !important;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #1b2299; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #141a73; }
    </style>
</head>
<body class="antialiased text-gray-800 min-h-screen flex flex-col justify-between">
    <div id="app" class="flex-grow flex flex-col">
        <!-- Unified JLM Glassmorphic Navbar -->
        <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-200/60 shadow-sm">
            <div class="container mx-auto px-4 lg:px-8 flex justify-between items-center h-16">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                    <img src="{{ asset('logo-only.png') }}" alt="Learnerium Logo" class="h-10 w-auto object-contain transition group-hover:scale-105">
                    <span class="text-2xl font-black bg-gradient-to-r from-[#1b2299] to-[#e4306d] bg-clip-text text-transparent tracking-tight">Learnerium</span>
                </a>
                
                <!-- Desktop Nav Links (JLM Style) -->
                <div class="hidden lg:flex items-center space-x-8">
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-[#1b2299] font-semibold text-sm transition-colors duration-200 relative group py-1">
                        Home
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-[#1b2299] to-[#e4306d] group-hover:w-full transition-all duration-300"></span>
                    </a>
                    <a href="{{ route('courses') }}" class="text-gray-700 hover:text-[#1b2299] font-semibold text-sm transition-colors duration-200 relative group py-1">
                        Courses
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-[#1b2299] to-[#e4306d] group-hover:w-full transition-all duration-300"></span>
                    </a>
                    <a href="{{ route('instructors') }}" class="text-gray-700 hover:text-[#1b2299] font-semibold text-sm transition-colors duration-200 relative group py-1">
                        Instructors
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-[#1b2299] to-[#e4306d] group-hover:w-full transition-all duration-300"></span>
                    </a>
                    @auth
                        @if(Auth::user()->isInstructor())
                            <a href="{{ route('instructor.dashboard') }}" class="text-secondary-jlm font-bold text-sm hover:text-secondary-jlm/80 transition duration-300 flex items-center gap-1.5">
                                <i class="fas fa-chalkboard-teacher text-xs"></i>Instructor
                            </a>
                        @else
                            <a href="{{ route('instructor.apply') }}" class="text-gray-700 hover:text-[#1b2299] font-semibold text-sm transition-colors duration-200 relative group py-1">
                                Teach on Learnerium
                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-[#1b2299] to-[#e4306d] group-hover:w-full transition-all duration-300"></span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('instructor.apply') }}" class="text-gray-700 hover:text-[#1b2299] font-semibold text-sm transition-colors duration-200 relative group py-1">
                            Teach on Learnerium
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-[#1b2299] to-[#e4306d] group-hover:w-full transition-all duration-300"></span>
                        </a>
                    @endauth
                    <a href="{{ route('about') }}" class="text-gray-700 hover:text-[#1b2299] font-semibold text-sm transition-colors duration-200 relative group py-1">
                        About
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-[#1b2299] to-[#e4306d] group-hover:w-full transition-all duration-300"></span>
                    </a>
                    <a href="{{ route('contact') }}" class="text-gray-700 hover:text-[#1b2299] font-semibold text-sm transition-colors duration-200 relative group py-1">
                        Contact
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-[#1b2299] to-[#e4306d] group-hover:w-full transition-all duration-300"></span>
                    </a>
                    @auth
                        @if(Auth::user()->isAdmin() || Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="bg-red-600 hover:bg-red-700 text-white font-extrabold text-xs px-3.5 py-2 rounded-xl transition inline-flex items-center gap-1.5 shadow-md">
                                <i class="fas fa-shield-alt text-amber-300"></i> Admin Panel
                            </a>
                        @endif
                    @endauth
                </div>

                <!-- Auth/Menu (JLM CTA Style) -->
                <div class="hidden lg:flex items-center space-x-4">
                    @guest
                        <div class="relative" id="signInDropdownWrap">
                            <button id="signInDropdownBtn" class="bg-gradient-to-r from-[#1b2299] to-[#e4306d] text-white px-6 py-2 rounded-lg font-bold text-sm shadow-md hover:scale-105 transition-all duration-200 flex items-center space-x-2">
                                <span>Sign In</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div id="signInDropdownMenu" class="absolute right-0 mt-2 w-56 bg-white/90 backdrop-blur-md rounded-xl shadow-xl py-2 z-20 hidden border border-gray-100 origin-top-right">
                                <a href="{{ route('login.student') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-primary-jlm/5 hover:text-primary-jlm font-medium">
                                    <i class="fas fa-user-graduate mr-2.5 text-primary-jlm text-base"></i>Student Sign In
                                </a>
                                <a href="{{ route('login.instructor') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-secondary-jlm/5 hover:text-secondary-jlm font-medium border-t border-gray-100">
                                    <i class="fas fa-chalkboard-teacher mr-2.5 text-secondary-jlm text-base"></i>Instructor Portal
                                </a>
                            </div>
                        </div>
                        <a href="{{ route('register') }}" class="border-2 border-[#1b2299] text-[#1b2299] px-6 py-1.5 rounded-lg font-bold text-sm hover:bg-[#1b2299] hover:text-white transition-all duration-200">Register</a>
                    @else
                        <!-- Wishlist & Cart Icons -->
                        <div class="flex items-center space-x-3 mr-2">
                            <a href="{{ route('wishlist.index') }}" class="relative text-gray-600 hover:text-pink-600 p-2 rounded-full hover:bg-pink-50 transition" title="Wishlist">
                                <i class="fas fa-heart text-lg"></i>
                                @php $wishlistCount = Auth::user()->wishlist()->count(); @endphp
                                @if($wishlistCount > 0)
                                    <span class="absolute -top-1 -right-1 bg-pink-500 text-white text-[10px] font-black w-4 h-4 rounded-full flex items-center justify-center border-2 border-white">
                                        {{ $wishlistCount }}
                                    </span>
                                @endif
                            </a>
                            <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-primary-jlm p-2 rounded-full hover:bg-blue-50 transition" title="Shopping Cart">
                                <i class="fas fa-shopping-cart text-lg"></i>
                                @php $cartCount = Auth::user()->cart()->count(); @endphp
                                @if($cartCount > 0)
                                    <span class="absolute -top-1 -right-1 bg-accent-jlm text-primary-jlm text-[10px] font-black w-4 h-4 rounded-full flex items-center justify-center border-2 border-white">
                                        {{ $cartCount }}
                                    </span>
                                @endif
                            </a>
                        </div>

                        <div class="relative" id="userDropdownWrap">
                            <button id="userDropdownBtn" class="flex items-center text-primary-jlm focus:outline-none hover:text-secondary-jlm font-semibold space-x-2">
                                <img src="{{ Auth::user()->avatarUrl() }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full border-2 border-primary-jlm object-cover">
                                <span>{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div id="userDropdownMenu" class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-xl py-2 z-20 hidden border border-gray-100 origin-top-right">
                                <div class="px-4 py-2 border-b border-gray-100 mb-1">
                                    <p class="text-xs text-gray-400 font-medium">Logged in as</p>
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                @if(Auth::user()->role === 'admin' || Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 text-sm font-extrabold text-red-600 hover:bg-red-50 transition border-b border-gray-100"><i class="fas fa-shield-alt mr-3 text-red-500"></i>Admin Dashboard</a>
                                @endif
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary-jlm transition"><i class="fas fa-th-large mr-3 text-gray-400"></i>Dashboard</a>
                                <a href="{{ route('cart.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary-jlm transition"><i class="fas fa-shopping-cart mr-3 text-gray-400"></i>Shopping Cart</a>
                                <a href="{{ route('wishlist.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary-jlm transition"><i class="fas fa-heart mr-3 text-pink-400"></i>My Wishlist</a>

                                @if(Auth::user()->canSwitchRole())
                                    <div class="px-2 py-1.5 bg-gray-50 border-y border-gray-100 my-1">
                                        <form action="{{ route('switch.role') }}" method="POST">
                                            @csrf
                                            @if(session('active_role') === 'student')
                                                <input type="hidden" name="role" value="instructor">
                                                <button type="submit" class="w-full flex items-center justify-between text-xs font-bold text-secondary-jlm bg-pink-50 hover:bg-pink-100 border border-pink-200 px-3 py-2 rounded-lg transition shadow-sm">
                                                    <span><i class="fas fa-chalkboard-teacher mr-1.5"></i>Switch to Instructor</span>
                                                    <i class="fas fa-exchange-alt"></i>
                                                </button>
                                            @else
                                                <input type="hidden" name="role" value="student">
                                                <button type="submit" class="w-full flex items-center justify-between text-xs font-bold text-primary-jlm bg-blue-50 hover:bg-blue-100 border border-blue-200 px-3 py-2 rounded-lg transition shadow-sm">
                                                    <span><i class="fas fa-user-graduate mr-1.5"></i>Switch to Student View</span>
                                                    <i class="fas fa-exchange-alt"></i>
                                                </button>
                                            @endif
                                        </form>
                                    </div>
                                @endif

                                @if(Auth::user()->isInstructor())
                                    <a href="{{ route('instructor.manage.courses') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary-jlm transition"><i class="fas fa-tasks mr-3 text-gray-400"></i>Manage Courses</a>
                                    <a href="{{ route('admin.instructor.applications') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary-jlm transition"><i class="fas fa-user-check mr-3 text-gray-400"></i>Review Instructor Apps</a>
                                @else
                                    <a href="{{ route('student.courses') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary-jlm transition"><i class="fas fa-graduation-cap mr-3 text-gray-400"></i>My Courses</a>
                                    <a href="{{ route('student.progress') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary-jlm transition"><i class="fas fa-chart-pie mr-3 text-gray-400"></i>My Progress</a>
                                    <a href="{{ route('instructor.apply') }}" class="block px-4 py-2.5 text-sm text-secondary-jlm font-bold hover:bg-gray-50 transition"><i class="fas fa-chalkboard-teacher mr-3"></i>Apply as Instructor</a>
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
            <div id="mobileMenu" class="hidden lg:hidden mt-4 bg-gray-50 rounded-2xl p-4 border border-gray-200 space-y-3 shadow-lg">
                <!-- Navigation Links -->
                <div class="space-y-1">
                    <a href="{{ url('/') }}" class="block px-3.5 py-2 text-primary-jlm hover:text-secondary-jlm hover:bg-white rounded-xl transition font-medium text-sm">
                        <i class="fas fa-home mr-2 text-primary-jlm"></i>Home
                    </a>
                    <a href="{{ route('courses') }}" class="block px-3.5 py-2 text-primary-jlm hover:text-secondary-jlm hover:bg-white rounded-xl transition font-medium text-sm">
                        <i class="fas fa-book mr-2 text-primary-jlm"></i>Courses
                    </a>
                    <a href="{{ route('instructors') }}" class="block px-3.5 py-2 text-primary-jlm hover:text-secondary-jlm hover:bg-white rounded-xl transition font-medium text-sm">
                        <i class="fas fa-user-tie mr-2 text-primary-jlm"></i>Instructors
                    </a>
                    @auth
                        @if(Auth::user()->isInstructor())
                            <a href="{{ route('instructor.dashboard') }}" class="block px-3.5 py-2 text-secondary-jlm font-bold hover:bg-white rounded-xl transition text-sm">
                                <i class="fas fa-chalkboard-teacher mr-2"></i>Instructor Dashboard
                            </a>
                        @else
                            <a href="{{ route('instructor.apply') }}" class="block px-3.5 py-2 text-secondary-jlm font-bold hover:bg-white rounded-xl transition text-sm">
                                <i class="fas fa-hand-holding-heart mr-2"></i>Teach on Learnerium
                            </a>
                        @endif
                    @else
                        <a href="{{ route('instructor.apply') }}" class="block px-3.5 py-2 text-secondary-jlm font-bold hover:bg-white rounded-xl transition text-sm">
                            <i class="fas fa-hand-holding-heart mr-2"></i>Teach on Learnerium
                        </a>
                    @endauth
                    <a href="{{ route('about') }}" class="block px-3.5 py-2 text-primary-jlm hover:text-secondary-jlm hover:bg-white rounded-xl transition font-medium text-sm">
                        <i class="fas fa-info-circle mr-2 text-primary-jlm"></i>About Us
                    </a>
                    <a href="{{ route('contact') }}" class="block px-3.5 py-2 text-primary-jlm hover:text-secondary-jlm hover:bg-white rounded-xl transition font-medium text-sm">
                        <i class="fas fa-envelope mr-2 text-primary-jlm"></i>Contact
                    </a>
                </div>

                <hr class="border-gray-200">

                @guest
                    <div class="space-y-2 pt-1">
                        <a href="{{ route('login.student') }}" class="flex items-center justify-center gap-2 bg-primary-jlm text-white py-2.5 rounded-xl font-semibold text-sm shadow-sm">
                            <i class="fas fa-user-graduate"></i>Student Sign In
                        </a>
                        <a href="{{ route('login.instructor') }}" class="flex items-center justify-center gap-2 bg-secondary-jlm text-white py-2.5 rounded-xl font-semibold text-sm shadow-sm">
                            <i class="fas fa-chalkboard-teacher"></i>Instructor Portal
                        </a>
                        <a href="{{ route('register') }}" class="block text-center border border-primary-jlm text-primary-jlm py-2.5 rounded-xl font-semibold text-sm hover:bg-primary-jlm/5">Register Account</a>
                    </div>
                @else
                    <div class="space-y-2 pt-1">
                        <!-- User Card Header -->
                        <div class="flex items-center gap-3 bg-white p-3 rounded-xl border border-gray-200">
                            <img src="{{ Auth::user()->avatarUrl() }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full border-2 border-primary-jlm object-cover">
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] text-gray-400 truncate">{{ Auth::user()->email }}</p>
                            </div>
                        </div>

                        <!-- Switch Role Button -->
                        @if(Auth::user()->canSwitchRole())
                            <form action="{{ route('switch.role') }}" method="POST">
                                @csrf
                                @if(session('active_role') === 'student')
                                    <input type="hidden" name="role" value="instructor">
                                    <button type="submit" class="w-full flex items-center justify-between text-xs font-bold text-secondary-jlm bg-pink-50 hover:bg-pink-100 border border-pink-200 px-3.5 py-2.5 rounded-xl transition shadow-xs">
                                        <span><i class="fas fa-chalkboard-teacher mr-2"></i>Switch to Instructor View</span>
                                        <i class="fas fa-exchange-alt"></i>
                                    </button>
                                @else
                                    <input type="hidden" name="role" value="student">
                                    <button type="submit" class="w-full flex items-center justify-between text-xs font-bold text-primary-jlm bg-blue-50 hover:bg-blue-100 border border-blue-200 px-3.5 py-2.5 rounded-xl transition shadow-xs">
                                        <span><i class="fas fa-user-graduate mr-2"></i>Switch to Student View</span>
                                        <i class="fas fa-exchange-alt"></i>
                                    </button>
                                @endif
                            </form>
                        @endif

                        <div class="space-y-1 pt-1">
                            <a href="{{ route('dashboard') }}" class="block px-3.5 py-2 text-sm font-medium text-gray-700 hover:bg-white rounded-xl transition"><i class="fas fa-th-large mr-2.5 text-gray-400"></i>Dashboard</a>

                            @if(Auth::user()->isInstructor())
                                <a href="{{ route('instructor.manage.courses') }}" class="block px-3.5 py-2 text-sm font-medium text-gray-700 hover:bg-white rounded-xl transition"><i class="fas fa-tasks mr-2.5 text-gray-400"></i>Manage Courses</a>
                                <a href="{{ route('admin.instructor.applications') }}" class="block px-3.5 py-2 text-sm font-medium text-gray-700 hover:bg-white rounded-xl transition"><i class="fas fa-user-check mr-2.5 text-gray-400"></i>Review Instructor Apps</a>
                            @else
                                <a href="{{ route('student.courses') }}" class="block px-3.5 py-2 text-sm font-medium text-gray-700 hover:bg-white rounded-xl transition"><i class="fas fa-graduation-cap mr-2.5 text-gray-400"></i>My Courses</a>
                                <a href="{{ route('student.progress') }}" class="block px-3.5 py-2 text-sm font-medium text-gray-700 hover:bg-white rounded-xl transition"><i class="fas fa-chart-pie mr-2.5 text-gray-400"></i>My Progress</a>
                            @endif

                            <a href="{{ url('/profile') }}" class="block px-3.5 py-2 text-sm font-medium text-gray-700 hover:bg-white rounded-xl transition"><i class="fas fa-user-circle mr-2.5 text-gray-400"></i>Profile</a>
                            <a href="{{ url('/settings') }}" class="block px-3.5 py-2 text-sm font-medium text-gray-700 hover:bg-white rounded-xl transition"><i class="fas fa-cog mr-2.5 text-gray-400"></i>Settings</a>
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="block pt-2">
                            @csrf
                            <button type="submit" class="w-full text-center bg-red-50 text-red-600 py-2.5 rounded-xl font-bold text-sm hover:bg-red-100 transition"><i class="fas fa-sign-out-alt mr-2"></i>Logout</button>
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
                <div class="flex items-center justify-center md:justify-start gap-2.5 mb-3">
                    <img src="{{ asset('logo-only.png') }}" alt="Learnerium Logo" class="h-9 w-auto object-contain bg-white/10 p-1 rounded-lg">
                    <h3 class="text-2xl font-extrabold text-white tracking-tight">Learnerium</h3>
                </div>
                <p class="text-gray-400 text-sm">&copy; {{ date('Y') }} Learnerium. Powered by <a href="https://jlm.com.ng" target="_blank" rel="noopener" class="inline-flex items-center gap-1 bg-accent-jlm text-primary-jlm px-2 py-0.5 rounded-full text-xs font-extrabold hover:bg-yellow-300 transition"><i class="fas fa-external-link-alt text-[8px]"></i>JLM</a>. All rights reserved.</p>

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

                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-accent-jlm">Legal</h4>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('privacy') }}" class="text-gray-400 hover:text-white transition duration-200">Privacy Policy</a></li>
                        <li><a href="{{ route('eua') }}" class="text-gray-400 hover:text-white transition duration-200">Terms of Service</a></li>
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
