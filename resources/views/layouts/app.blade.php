<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Search Console Verification -->
    <meta name="google-site-verification" content="googlef58fe20d0eef6322">

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
    @stack('head')
    @stack('styles')

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

                </div>

                <!-- Auth/Menu (JLM CTA Style) -->
                <div class="hidden lg:flex items-center">
                    @guest
                        <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-primary-jlm p-2 rounded-full hover:bg-blue-50 transition mr-1" title="Shopping Cart">
                            <i class="fas fa-shopping-cart text-lg text-primary-jlm"></i>
                            @php $guestCartCount = App\Http\Controllers\CartController::getCartCount(); @endphp
                            @if($guestCartCount > 0)
                                <span class="absolute -top-1 -right-1 bg-accent-jlm text-primary-jlm text-[10px] font-black w-4 h-4 rounded-full flex items-center justify-center border-2 border-white">
                                    {{ $guestCartCount }}
                                </span>
                            @endif
                        </a>
                        <div class="relative inline-block text-left" id="loginDropdownWrap">
                            <button id="loginDropdownBtn" class="bg-primary-jlm text-white px-5 py-2 rounded-lg font-bold text-sm hover:bg-primary-jlm-dark transition flex items-center gap-1.5 shadow-sm">
                                <span>Sign In</span>
                                <i class="fas fa-chevron-down text-[10px] opacity-80"></i>
                            </button>
                            <div id="loginDropdownMenu" class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-xl py-2 z-20 hidden border border-gray-100 origin-top-right">
                                <a href="{{ route('login.student') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-primary-jlm/5 hover:text-primary-jlm font-medium">
                                    <i class="fas fa-user-graduate mr-2.5 text-primary-jlm text-base"></i>Student Sign In
                                </a>
                                <a href="{{ route('login.instructor') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-secondary-jlm/5 hover:text-secondary-jlm font-medium border-t border-gray-100">
                                    <i class="fas fa-chalkboard-teacher mr-2.5 text-secondary-jlm text-base"></i>Instructor Portal
                                </a>
                            </div>
                        </div>
                        <a href="{{ route('register') }}" class="ml-3 border-2 border-[#1b2299] text-[#1b2299] px-6 py-1.5 rounded-lg font-bold text-sm hover:bg-[#1b2299] hover:text-white transition-all duration-200">Register</a>
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
                                @php $cartCount = App\Http\Controllers\CartController::getCartCount(); @endphp
                                @if($cartCount > 0)
                                    <span class="absolute -top-1 -right-1 bg-accent-jlm text-primary-jlm text-[10px] font-black w-4 h-4 rounded-full flex items-center justify-center border-2 border-white">
                                        {{ $cartCount }}
                                    </span>
                                @endif
                            </a>

                            {{-- Notification Bell --}}
                            <div class="relative" id="notifBellWrap">
                                <button id="notifBellBtn" class="relative text-gray-600 hover:text-primary-jlm p-2 rounded-full hover:bg-blue-50 transition" title="Notifications">
                                    <i class="fas fa-bell text-lg"></i>
                                    <span id="notifBadge" class="absolute -top-1 -right-1 bg-secondary-jlm text-white text-[10px] font-black w-4 h-4 rounded-full items-center justify-center border-2 border-white hidden">0</span>
                                </button>
                                <div id="notifPanel" class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 z-30 hidden origin-top-right">
                                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                                        <span class="font-bold text-gray-900 text-sm">Notifications</span>
                                        <button onclick="markAllRead()" class="text-xs text-primary-jlm font-bold hover:underline">Mark all read</button>
                                    </div>
                                    <div id="notifList" class="max-h-80 overflow-y-auto divide-y divide-gray-50">
                                        <div class="p-6 text-center text-gray-400 text-sm" id="notifEmpty">
                                            <i class="fas fa-bell-slash text-2xl mb-2 block text-gray-200"></i>
                                            No notifications yet
                                        </div>
                                    </div>
                                    <div class="px-4 py-2.5 border-t border-gray-100 text-center">
                                        <a href="{{ route('notifications.preferences') }}" class="text-xs text-gray-400 hover:text-primary-jlm font-semibold transition">
                                            <i class="fas fa-sliders-h mr-1"></i>Notification Settings
                                        </a>
                                    </div>
                                </div>
                            </div>
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
                                    {{-- Admin link is intentionally not shown here; access via footer shield icon --}}
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
                                <a href="{{ route('user.inbox') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary-jlm transition"><i class="fas fa-inbox mr-3 text-purple-400"></i>My Messages</a>
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

                <!-- Mobile Wishlist, Cart & Hamburger Button -->
                <div class="flex items-center space-x-1 lg:hidden">
                    @auth
                        <a href="{{ route('wishlist.index') }}" class="relative text-gray-600 hover:text-pink-600 p-2 rounded-full hover:bg-pink-50 transition" title="Wishlist">
                            <i class="fas fa-heart text-lg text-pink-500"></i>
                            @php $mobileWishlistCount = Auth::user()->wishlist()->count(); @endphp
                            @if($mobileWishlistCount > 0)
                                <span class="absolute -top-0.5 -right-0.5 bg-pink-500 text-white text-[10px] font-black w-4 h-4 rounded-full flex items-center justify-center border-2 border-white">
                                    {{ $mobileWishlistCount }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-primary-jlm p-2 rounded-full hover:bg-blue-50 transition" title="Shopping Cart">
                            <i class="fas fa-shopping-cart text-lg text-primary-jlm"></i>
                            @php $mobileCartCount = App\Http\Controllers\CartController::getCartCount(); @endphp
                            @if($mobileCartCount > 0)
                                <span class="absolute -top-0.5 -right-0.5 bg-accent-jlm text-primary-jlm text-[10px] font-black w-4 h-4 rounded-full flex items-center justify-center border-2 border-white">
                                    {{ $mobileCartCount }}
                                </span>
                            @endif
                        </a>
                    @else
                        <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-primary-jlm p-2 rounded-full hover:bg-blue-50 transition" title="Shopping Cart">
                            <i class="fas fa-shopping-cart text-lg text-primary-jlm"></i>
                            @php $mobileGuestCartCount = App\Http\Controllers\CartController::getCartCount(); @endphp
                            @if($mobileGuestCartCount > 0)
                                <span class="absolute -top-0.5 -right-0.5 bg-accent-jlm text-primary-jlm text-[10px] font-black w-4 h-4 rounded-full flex items-center justify-center border-2 border-white">
                                    {{ $mobileGuestCartCount }}
                                </span>
                            @endif
                        </a>
                    @endauth

                    <button id="mobileMenuBtn" class="text-primary-jlm focus:outline-none p-2 rounded-lg hover:bg-gray-100 transition ml-1">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
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
                    <a href="{{ route('cart.index') }}" class="block px-3.5 py-2 text-primary-jlm hover:text-secondary-jlm hover:bg-white rounded-xl transition font-medium text-sm">
                        <i class="fas fa-shopping-cart mr-2 text-primary-jlm"></i>Cart
                        @auth
                            @if(Auth::user()->cart()->count() > 0)
                                <span class="ml-2 bg-accent-jlm text-primary-jlm text-xs font-extrabold px-2 py-0.5 rounded-full">{{ Auth::user()->cart()->count() }}</span>
                            @endif
                        @endauth
                    </a>
                    <a href="{{ route('wishlist.index') }}" class="block px-3.5 py-2 text-primary-jlm hover:text-secondary-jlm hover:bg-white rounded-xl transition font-medium text-sm">
                        <i class="fas fa-heart mr-2 text-pink-500"></i>Wishlist
                        @auth
                            @if(Auth::user()->wishlist()->count() > 0)
                                <span class="ml-2 bg-pink-500 text-white text-xs font-extrabold px-2 py-0.5 rounded-full">{{ Auth::user()->wishlist()->count() }}</span>
                            @endif
                        @endauth
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

                            <a href="{{ route('user.inbox') }}" class="block px-3.5 py-2 text-sm font-medium text-gray-700 hover:bg-white rounded-xl transition"><i class="fas fa-inbox mr-2.5 text-purple-400"></i>My Messages</a>
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

    <!-- Unified Professional Footer -->
    <footer class="bg-[#0b0f19] text-gray-300 pt-16 pb-12 border-t border-slate-800/80 mt-auto relative overflow-hidden">
        {{-- Subtle top glow accent line --}}
        <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-[#1b2299] to-[#e4306d] opacity-50"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-slate-800/80">
                
                {{-- Column 1: Brand & Bio (2 cols wide on LG) --}}
                <div class="lg:col-span-2 space-y-4">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-3 group">
                        <img src="{{ asset('logo-only.png') }}" alt="Learnerium Logo" class="h-10 w-auto object-contain bg-white/10 p-1.5 rounded-xl shadow-inner group-hover:scale-105 transition">
                        <span class="text-2xl font-black tracking-tight text-white">Learnerium</span>
                    </a>
                    <p class="text-gray-400 text-sm leading-relaxed max-w-sm">
                        Elevating education through creative, interactive, and personalized learning. Empowering students and professionals across Africa and beyond.
                    </p>
                    
                </div>

                {{-- Column 2: Explore --}}
                <div>
                    <h4 class="text-sm font-bold tracking-wider text-white uppercase mb-4 border-l-2 border-[#1b2299] pl-2.5">Explore</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('courses') }}" class="text-gray-400 hover:text-white transition duration-200 flex items-center gap-1.5"><i class="fas fa-angle-right text-[10px] text-primary-jlm"></i> Browse Courses</a></li>
                        <li><a href="{{ route('instructors') }}" class="text-gray-400 hover:text-white transition duration-200 flex items-center gap-1.5"><i class="fas fa-angle-right text-[10px] text-primary-jlm"></i> Expert Instructors</a></li>
                        <li><a href="{{ route('instructor.apply') }}" class="text-gray-400 hover:text-white transition duration-200 flex items-center gap-1.5"><i class="fas fa-angle-right text-[10px] text-primary-jlm"></i> Teach on Learnerium</a></li>
                    </ul>
                </div>

                {{-- Column 3: Company --}}
                <div>
                    <h4 class="text-sm font-bold tracking-wider text-white uppercase mb-4 border-l-2 border-[#e4306d] pl-2.5">Company</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-white transition duration-200 flex items-center gap-1.5"><i class="fas fa-angle-right text-[10px] text-[#e4306d]"></i> About Us</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-white transition duration-200 flex items-center gap-1.5"><i class="fas fa-angle-right text-[10px] text-[#e4306d]"></i> Contact Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-200 flex items-center gap-1.5"><i class="fas fa-angle-right text-[10px] text-[#e4306d]"></i> Careers</a></li>
                    </ul>
                </div>

                {{-- Column 4: Legal & Policy --}}
                <div>
                    <h4 class="text-sm font-bold tracking-wider text-white uppercase mb-4 border-l-2 border-accent-jlm pl-2.5">Legal</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('privacy') }}" class="text-gray-400 hover:text-white transition duration-200 flex items-center gap-1.5"><i class="fas fa-angle-right text-[10px] text-accent-jlm"></i> Privacy Policy</a></li>
                        <li><a href="{{ route('eua') }}" class="text-gray-400 hover:text-white transition duration-200 flex items-center gap-1.5"><i class="fas fa-angle-right text-[10px] text-accent-jlm"></i> Terms of Service</a></li>
                        <li><a href="{{ route('eua') }}" class="text-gray-400 hover:text-white transition duration-200 flex items-center gap-1.5"><i class="fas fa-angle-right text-[10px] text-accent-jlm"></i> Cookie Policy</a></li>
                    </ul>
                </div>

            </div>

            {{-- Bottom Sub-Footer Bar --}}
            <div class="pt-8 flex flex-col sm:flex-row justify-between items-center text-xs text-gray-400 gap-4">
                <div class="flex items-center gap-2">
                    <span>&copy; {{ date('Y') }} Learnerium Inc. All rights reserved.</span>
                </div>

                {{-- Subtle Admin Portal Access (shield icon, low-key) --}}
                @auth
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                           title="Admin Portal"
                           class="inline-flex items-center gap-1 text-gray-600/40 hover:text-amber-500/80 transition-colors duration-300 group">
                            <i class="fas fa-shield-halved text-sm group-hover:scale-110 transition-transform"></i>
                        </a>
                    @endif
                @endauth
                @guest
                    <a href="{{ route('login.admin') }}"
                       title="Admin Portal"
                       class="inline-flex items-center gap-1 text-gray-700/20 hover:text-amber-500/60 transition-colors duration-300 group">
                        <i class="fas fa-shield-halved text-sm group-hover:scale-110 transition-transform"></i>
                    </a>
                @endguest

                {{-- JLM Partner Pill --}}
                <div class="flex items-center gap-2">
                    <span>Powered by</span>
                    <a href="https://jlm.com.ng" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 bg-accent-jlm text-primary-jlm px-2.5 py-1 rounded-full font-bold hover:bg-yellow-300 transition shadow-sm">
                        <i class="fas fa-external-link-alt text-[9px]"></i>
                        <span>JLM</span>
                    </a>
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

        // Generic sticky-hover + click dropdown: keeps menu open when moving from trigger to menu
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
                leaveTimer = setTimeout(() => menu.classList.add('hidden'), 150);
            }

            // Click toggle
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                menu.classList.toggle('hidden');
            });

            // Hover interactions
            btn.addEventListener('mouseenter', showMenu);
            btn.addEventListener('mouseleave', hideMenu);
            menu.addEventListener('mouseenter', showMenu);
            menu.addEventListener('mouseleave', hideMenu);

            // Close on click outside
            document.addEventListener('click', (e) => {
                if (!wrap.contains(e.target)) menu.classList.add('hidden');
            });
        }

        stickyDropdown('loginDropdownWrap', 'loginDropdownBtn', 'loginDropdownMenu');
        stickyDropdown('userDropdownWrap',  'userDropdownBtn',  'userDropdownMenu');

        // ─── Notification Bell ───────────────────────────────────────────────
        @auth
        const notifBtn   = document.getElementById('notifBellBtn');
        const notifPanel = document.getElementById('notifPanel');
        const notifBadge = document.getElementById('notifBadge');
        const notifList  = document.getElementById('notifList');
        const notifEmpty = document.getElementById('notifEmpty');

        const colorMap = {
            green: 'text-emerald-500', blue: 'text-blue-500',
            red: 'text-red-500', amber: 'text-amber-500', purple: 'text-purple-500'
        };

        function loadNotifications() {
            fetch('{{ route("notifications.index") }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.json())
                .then(notifications => {
                    const unread = notifications.filter(n => !n.is_read).length;
                    if (unread > 0) {
                        notifBadge.textContent = unread > 9 ? '9+' : unread;
                        notifBadge.classList.remove('hidden');
                        notifBadge.classList.add('flex');
                    } else {
                        notifBadge.classList.add('hidden');
                        notifBadge.classList.remove('flex');
                    }

                    if (notifications.length === 0) {
                        notifEmpty.classList.remove('hidden');
                        return;
                    }
                    notifEmpty.classList.add('hidden');

                    // Clear and rebuild list (keep notifEmpty node)
                    [...notifList.children].forEach(c => { if (c.id !== 'notifEmpty') c.remove(); });

                    notifications.slice(0, 15).forEach(n => {
                        const iconColor = colorMap[n.color] || 'text-blue-500';
                        const readClass = n.is_read ? 'opacity-60' : 'bg-blue-50/40';
                        const item = document.createElement('div');
                        item.className = `px-4 py-3 hover:bg-gray-50 transition cursor-pointer ${readClass}`;
                        item.innerHTML = `
                            <div class="flex gap-3 items-start">
                                <i class="fas ${n.icon} mt-0.5 flex-shrink-0 ${iconColor}"></i>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-bold text-gray-900 leading-tight">${n.title}</p>
                                    <p class="text-xs text-gray-500 mt-0.5 leading-snug">${n.message}</p>
                                    <p class="text-[10px] text-gray-300 mt-1">${new Date(n.created_at).toLocaleString()}</p>
                                </div>
                            </div>`;
                        item.addEventListener('click', () => {
                            fetch(`/notifications/${n.id}/read`, {
                                method: 'POST',
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' }
                            }).then(() => {
                                item.classList.remove('bg-blue-50/40');
                                item.classList.add('opacity-60');
                                if (n.action_url) window.location.href = n.action_url;
                            });
                        });
                        notifList.appendChild(item);
                    });
                }).catch(() => {});
        }

        function markAllRead() {
            fetch('{{ route("notifications.read-all") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' }
            }).then(() => loadNotifications());
        }

        if (notifBtn) {
            notifBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                notifPanel.classList.toggle('hidden');
                if (!notifPanel.classList.contains('hidden')) loadNotifications();
            });
            document.addEventListener('click', (e) => {
                if (!notifPanel.contains(e.target) && e.target !== notifBtn) notifPanel.classList.add('hidden');
            });
            // Load count on page load
            fetch('{{ route("notifications.count") }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.json())
                .then(data => {
                    if (data.count > 0) {
                        notifBadge.textContent = data.count > 9 ? '9+' : data.count;
                        notifBadge.classList.remove('hidden');
                        notifBadge.classList.add('flex');
                    }
                }).catch(() => {});
        }
        @endauth

        // ================= UNIVERSAL MODAL & TOAST SYSTEM =================
        const universalModal = document.getElementById('universalModal');
        const modalBackdrop = document.getElementById('modalBackdrop');
        const modalDialog = document.getElementById('modalDialog');
        const modalIconWrap = document.getElementById('modalIconWrap');
        const modalIcon = document.getElementById('modalIcon');
        const modalTitle = document.getElementById('modalTitle');
        const modalMessage = document.getElementById('modalMessage');
        const modalCancelBtn = document.getElementById('modalCancelBtn');
        const modalConfirmBtn = document.getElementById('modalConfirmBtn');
        const toastContainer = document.getElementById('toastContainer');

        let currentModalResolve = null;

        window.showModal = function(options = {}) {
            return new Promise((resolve) => {
                currentModalResolve = resolve;
                const type = options.type || 'info';
                const title = options.title || (type === 'error' ? 'Notice' : (type === 'success' ? 'Success' : 'Information'));
                const message = options.message || '';
                const confirmText = options.confirmText || 'OK';
                const cancelText = options.cancelText || null;
                const isConfirm = Boolean(cancelText || options.isConfirm);

                modalTitle.textContent = title;
                modalMessage.innerHTML = message;
                modalConfirmBtn.textContent = confirmText;

                // Configure Icon & Color
                const typeStyles = {
                    info: { icon: 'fa-info-circle', color: 'text-primary-jlm', bg: 'bg-blue-50 border-blue-100', btn: 'bg-primary-jlm hover:bg-primary-jlm-dark text-white' },
                    success: { icon: 'fa-check-circle', color: 'text-emerald-600', bg: 'bg-emerald-50 border-emerald-100', btn: 'bg-emerald-600 hover:bg-emerald-700 text-white' },
                    warning: { icon: 'fa-exclamation-triangle', color: 'text-amber-600', bg: 'bg-amber-50 border-amber-100', btn: 'bg-amber-600 hover:bg-amber-700 text-white' },
                    error: { icon: 'fa-times-circle', color: 'text-rose-600', bg: 'bg-rose-50 border-rose-100', btn: 'bg-rose-600 hover:bg-rose-700 text-white' },
                    ai: { icon: 'fa-wand-magic-sparkles', color: 'text-purple-600', bg: 'bg-purple-50 border-purple-100', btn: 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white' }
                };

                const style = typeStyles[type] || typeStyles.info;
                modalIconWrap.className = `w-12 h-12 rounded-2xl flex items-center justify-center text-xl border mx-auto mb-3.5 shadow-sm ${style.bg}`;
                modalIcon.className = `fas ${style.icon} ${style.color}`;
                modalConfirmBtn.className = `px-6 py-2.5 rounded-xl font-bold text-xs shadow-md transition ${style.btn}`;

                if (isConfirm) {
                    modalCancelBtn.classList.remove('hidden');
                    modalCancelBtn.textContent = cancelText || 'Cancel';
                } else {
                    modalCancelBtn.classList.add('hidden');
                }

                universalModal.classList.remove('hidden');
                setTimeout(() => {
                    modalDialog.classList.remove('scale-95', 'opacity-0');
                    modalDialog.classList.add('scale-100', 'opacity-100');
                }, 10);
            });
        };

        window.closeUniversalModal = function(confirmed = false) {
            modalDialog.classList.remove('scale-100', 'opacity-100');
            modalDialog.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                universalModal.classList.add('hidden');
                if (currentModalResolve) {
                    currentModalResolve(confirmed);
                    currentModalResolve = null;
                }
            }, 150);
        };

        modalConfirmBtn.addEventListener('click', () => closeUniversalModal(true));
        modalCancelBtn.addEventListener('click', () => closeUniversalModal(false));

        window.showAlert = function(message, title = '', type = 'info') {
            return showModal({ title, message, type, confirmText: 'Got It' });
        };

        window.showToast = function(message, type = 'success', duration = 4000) {
            if (!toastContainer) return;
            const toast = document.createElement('div');
            const colorClass = type === 'error' ? 'bg-rose-900/90 text-rose-100 border-rose-700' : (type === 'warning' ? 'bg-amber-900/90 text-amber-100 border-amber-700' : 'bg-slate-900/90 text-white border-slate-700');
            const icon = type === 'error' ? 'fa-exclamation-circle text-rose-400' : (type === 'warning' ? 'fa-exclamation-triangle text-amber-400' : 'fa-check-circle text-emerald-400');

            toast.className = `flex items-center gap-3 px-4 py-3 rounded-2xl shadow-xl backdrop-blur-md border text-xs font-semibold transform transition-all duration-300 translate-y-3 opacity-0 ${colorClass}`;
            toast.innerHTML = `<i class="fas ${icon} text-sm flex-shrink-0"></i><span class="flex-1 leading-snug">${message}</span>`;

            toastContainer.appendChild(toast);
            setTimeout(() => toast.classList.remove('translate-y-3', 'opacity-0'), 10);

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-3');
                setTimeout(() => toast.remove(), 300);
            }, duration);
        };

        // Standardize browser native alert() to call beautiful modal
        window.alert = function(msg) {
            showAlert(msg, 'Notice', 'info');
        };

    </script>

    <!-- Global Responsive Universal Modal Dialog -->
    <div id="universalModal" class="fixed inset-0 z-[9999] hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm p-4 sm:p-6 flex items-center justify-center">
        <div id="modalDialog" class="bg-white rounded-3xl max-w-sm sm:max-w-md w-full p-6 sm:p-8 shadow-2xl border border-gray-100 text-center transform transition-all duration-200 scale-95 opacity-0 my-auto">
            <div id="modalIconWrap" class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl border mx-auto mb-3.5 shadow-sm bg-blue-50 border-blue-100">
                <i id="modalIcon" class="fas fa-info-circle text-primary-jlm"></i>
            </div>
            <h3 id="modalTitle" class="text-base font-extrabold text-gray-900 mb-1.5 leading-snug">Information</h3>
            <div id="modalMessage" class="text-xs text-gray-600 leading-relaxed mb-6"></div>
            <div class="flex items-center justify-center gap-2.5">
                <button id="modalCancelBtn" type="button" class="hidden px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-xs font-bold hover:bg-gray-50 transition">Cancel</button>
                <button id="modalConfirmBtn" type="button" class="px-6 py-2.5 rounded-xl font-bold text-xs shadow-md transition bg-primary-jlm text-white hover:bg-primary-jlm-dark">OK</button>
            </div>
        </div>
    </div>

    <!-- Global Toast Container -->
    <div id="toastContainer" class="fixed bottom-5 right-5 z-[99999] flex flex-col gap-2 max-w-sm w-full pointer-events-none"></div>

    @stack('scripts')
    @yield('scripts')
</body>
</html>

