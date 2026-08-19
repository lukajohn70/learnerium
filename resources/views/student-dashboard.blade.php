<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learnerium - Student Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Added FontAwesome CDN for icons in the navbar and general use --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" xintegrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-jlm': '#1b2299',          // Deep Blue
                        'primary-jlm-dark': '#141a73',     // Slightly darker primary for hover
                        'secondary-jlm': '#e4306d',        // Vibrant Pink
                        'accent-jlm': '#f7de7a',           // Soft Yellow
                        'gray-jlm-light': '#f8f8f8',       // Custom light gray for backgrounds
                    },
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'], // Define Inter font family
                    }
                }
            }
        }
    </script>
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

    <nav class="bg-white shadow-md p-4 sticky top-0 z-50">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-center">
            <a href="{{ url('/home') }}" class="text-3xl font-extrabold text-primary-jlm mb-4 md:mb-0">Learnerium</a>
            <div class="flex flex-wrap justify-center md:space-x-4 space-x-2">
                <a href="{{ url('/home') }}" class="text-primary-jlm hover:text-secondary-jlm transition duration-300 px-2 py-1">Home</a>
                <a href="{{ url('/courses') }}" class="text-primary-jlm hover:text-secondary-jlm transition duration-300 px-2 py-1">Courses</a>
                <a href="{{ url('/instructors') }}" class="text-primary-jlm hover:text-secondary-jlm transition duration-300 px-2 py-1">Instructors</a>
                <a href="{{ url('/about') }}" class="text-primary-jlm hover:text-secondary-jlm transition duration-300 px-2 py-1">About Us</a>
                <a href="{{ url('/contact') }}" class="text-primary-jlm hover:text-secondary-jlm transition duration-300 px-2 py-1">Contact</a>

                @auth
                    <div class="relative group ml-4">
                        <button class="flex items-center text-primary-jlm focus:outline-none hover:text-secondary-jlm">
                            <span class="mr-2">{{ Auth::user()->name ?? 'User' }}</span>
                            <i class="fas fa-chevron-down text-sm"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20 opacity-0 group-hover:opacity-100 group-focus-within:opacity-100 transition-opacity duration-300 pointer-events-none group-hover:pointer-events-auto">
                            <a href="/profile" class="block px-4 py-2 text-gray-800 hover:bg-gray-100"><i class="fas fa-user-circle mr-2"></i>Profile</a>
                            <a href="/settings" class="block px-4 py-2 text-gray-800 hover:bg-gray-100"><i class="fas fa-cog mr-2"></i>Settings</a>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100"><i class="fas fa-sign-out-alt mr-2"></i>Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ url('/login') }}" class="bg-primary-jlm text-white px-4 py-2 rounded-lg hover:bg-primary-jlm-dark transition duration-300 shadow-md">Login</a>
                    <a href="{{ url('/register') }}" class="border border-primary-jlm text-primary-jlm px-4 py-2 rounded-lg hover:bg-primary-jlm/10 transition duration-300">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-16">
        <h1 class="text-4xl font-bold text-primary-jlm mb-8 text-center">Your Dashboard</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {{-- Dashboard Card 1: Enrolled Courses --}}
            <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-secondary-jlm flex flex-col items-center text-center">
                <div class="text-secondary-jlm mb-4 text-5xl">
                    <i class="fas fa-book-open"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-2">Enrolled Courses</h3>
                <p class="text-gray-700 mb-4">Continue learning where you left off. Access all your courses here.</p>
                <a href="{{ route('student.courses') }}" class="inline-block bg-secondary-jlm text-white px-6 py-2 rounded-lg hover:bg-secondary-jlm/90 transition duration-300 shadow-md">View My Courses</a>
            </div>

            {{-- Dashboard Card 2: Progress & Performance --}}
            <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-primary-jlm flex flex-col items-center text-center">
                <div class="text-primary-jlm mb-4 text-5xl">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-2">My Progress</h3>
                <p class="text-gray-700 mb-4">Track your course progress, quiz scores, and achievements.</p>
                <a href="{{ route('student.progress') }}" class="inline-block bg-primary-jlm text-white px-6 py-2 rounded-lg hover:bg-primary-jlm-dark transition duration-300 shadow-md">See My Progress</a>
            </div>

            {{-- Dashboard Card 3: Certificates --}}
            <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-accent-jlm flex flex-col items-center text-center">
                <div class="text-accent-jlm mb-4 text-5xl">
                    <i class="fas fa-award"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-2">Certificates</h3>
                <p class="text-gray-700 mb-4">Download your completed course certificates.</p>
                <a href="{{ route('student.certificates') }}" class="inline-block bg-accent-jlm text-gray-900 px-6 py-2 rounded-lg hover:bg-accent-jlm/80 transition duration-300 shadow-md">Get Certificates</a>
            </div>

            {{-- Dashboard Card 4: Explore New Courses (Optional) --}}
            <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-gray-500 flex flex-col items-center text-center">
                <div class="text-gray-500 mb-4 text-5xl">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-2">Explore Courses</h3>
                <p class="text-gray-700 mb-4">Discover new courses and expand your knowledge.</p>
                <a href="/courses" class="inline-block bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-300 shadow-md">Browse All Courses</a>
            </div>

            {{-- Dashboard Card 5: Settings & Profile --}}
            <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-blue-400 flex flex-col items-center text-center">
                <div class="text-blue-400 mb-4 text-5xl">
                    <i class="fas fa-user-cog"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-2">Profile & Settings</h3>
                <p class="text-gray-700 mb-4">Update your personal information and account settings.</p>
                <a href="/profile" class="inline-block bg-blue-400 text-white px-6 py-2 rounded-lg hover:bg-blue-500 transition duration-300 shadow-md">Manage Profile</a>
            </div>

            {{-- Dashboard Card 6: Support & Help --}}
            <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-green-500 flex flex-col items-center text-center">
                <div class="text-green-500 mb-4 text-5xl">
                    <i class="fas fa-question-circle"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-2">Help & Support</h3>
                <p class="text-gray-700 mb-4">Need assistance? Contact our support team.</p>
                <a href="/contact" class="inline-block bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition duration-300 shadow-md">Get Help</a>
            </div>
        </div>
    </div>


    <footer class="bg-gray-900 text-white py-10 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center md:items-start">
            <div class="mb-8 md:mb-0 text-center md:text-left">
                <h3 class="text-2xl font-extrabold text-white mb-2">Learnerium</h3>
                <p class="text-gray-400 text-sm">&copy; {{ date('Y') }} Learnerium. All rights reserved.</p>
            </div>
            <div class="grid grid-cols-2 gap-8 md:grid-cols-3 md:gap-6 text-center md:text-left">
                <div>
                    <h4 class="text-lg font-semibold mb-3">Company</h4>
                    <ul>
                        <li><a href="/about" class="text-gray-400 hover:text-white transition duration-200">About Us</a></li>
                        <li><a href="/contact" class="text-gray-400 hover:text-white transition duration-200">Contact Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-200">Careers</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Explore</h4>
                    <ul>
                        <li><a href="/courses" class="text-gray-400 hover:text-white transition duration-200">Courses</a></li>
                        <li><a href="/instructors" class="text-gray-400 hover:text-white transition duration-200">Instructors</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-200">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Legal</h4>
                    <ul>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-200">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-200">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
