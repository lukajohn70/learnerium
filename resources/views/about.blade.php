<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learnerium - About Us</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Added FontAwesome CDN for icons in the navbar --}}
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

    <header class="bg-gradient-to-br from-primary-jlm to-secondary-jlm text-white py-20 px-4 text-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: url('https://image.pollinations.ai/prompt/minimalistic%20abstract%20pattern%20soft%20gradients%20blue%20pink'); background-size: cover; background-position: center;"></div>
        <div class="container mx-auto relative z-10">
            <h1 class="text-5xl md:text-6xl font-extrabold leading-tight mb-6">
                Unlock Your Potential with Learnerium
            </h1>
            <p class="text-xl md:text-2xl mb-8 opacity-90 max-w-3xl mx-auto">
                At Learnerium, we believe that education is the key to a brighter future. We are dedicated to providing high-quality, accessible, and engaging online learning experiences for everyone.
            </p>
        </div>
    </header>

    <main class="container mx-auto px-4 py-16">
        <section class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-16 bg-white p-8 rounded-lg shadow-lg">
            <div class="md:order-2">
                <img src="https://placehold.co/600x400/1b2299/f7de7a?text=Our+Mission" alt="Our Mission" class="rounded-lg shadow-md w-full h-auto object-cover">
            </div>
            <div class="md:order-1">
                <h2 class="text-4xl font-bold text-primary-jlm mb-6">Our Mission</h2>
                <p class="text-lg text-gray-700 leading-relaxed mb-4">
                    Our mission is to empower individuals worldwide through accessible, innovative, and impactful online education. We strive to create a vibrant learning community where curiosity is sparked, knowledge is shared, and personal growth is cultivated.
                </p>
                <p class="text-lg text-gray-700 leading-relaxed">
                    We are committed to bridging the gap between aspiring learners and expert instructors, fostering a dynamic environment that supports lifelong learning and skill development.
                </p>
            </div>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-16 bg-white p-8 rounded-lg shadow-lg">
            <div>
                <img src="https://placehold.co/600x400/e4306d/ffffff?text=Our+Vision" alt="Our Vision" class="rounded-lg shadow-md w-full h-auto object-cover">
            </div>
            <div>
                <h2 class="text-4xl font-bold text-primary-jlm mb-6">Our Vision</h2>
                <p class="text-lg text-gray-700 leading-relaxed mb-4">
                    To be the leading global platform for online learning, recognized for its comprehensive course offerings, exceptional instructor quality, and a supportive community that inspires learners to achieve their fullest potential and adapt to the evolving demands of the modern world.
                </p>
                <p class="text-lg text-gray-700 leading-relaxed">
                    We envision a future where continuous learning is not just a necessity but a joyous journey, accessible to everyone, everywhere.
                </p>
            </div>
        </section>

        <section class="text-center mb-16">
            <h2 class="text-4xl font-bold text-primary-jlm mb-8">What We Offer</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-semibold text-gray-900 mb-4">Diverse Course Catalog</h3>
                    <p class="text-gray-700">Explore hundreds of courses across various domains, from technology and business to arts and personal development.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-semibold text-gray-900 mb-4">Expert Instructors</h3>
                    <p class="text-gray-700">Learn from industry leaders and passionate educators who bring real-world experience to their teachings.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-semibold text-gray-900 mb-4">Flexible Learning</h3>
                    <p class="text-gray-700">Study at your own pace, anytime, anywhere, with our mobile-friendly platform and downloadable resources.</p>
                </div>
            </div>
        </section>

        <section class="text-center bg-primary-jlm text-white p-10 rounded-lg shadow-xl">
            <h2 class="text-4xl font-extrabold mb-6">Ready to Start Your Learning Journey?</h2>
            <p class="text-xl mb-8">Join Learnerium today and unlock a world of knowledge and opportunities.</p>
            <a href="/register" class="inline-flex items-center justify-center px-8 py-4 border border-transparent text-lg font-bold rounded-md text-primary-jlm bg-accent-jlm hover:bg-accent-jlm/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-jlm transition duration-300">
                Sign Up Now
            </a>
        </section>
    </main>

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
