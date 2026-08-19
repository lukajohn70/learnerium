<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learnerium - Contact Us</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Added FontAwesome CDN for icons in the navbar and social links --}}
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
        <div class="container mx-auto relative z-10">
            <h1 class="text-5xl md:text-6xl font-extrabold leading-tight mb-6">
                Get In Touch With Us
            </h1>
            <p class="text-xl md:text-2xl mb-8 opacity-90 max-w-3xl mx-auto">
                Have questions, feedback, or need support? We're here to help! Reach out to Learnerium, and we'll get back to you as soon as possible.
            </p>
        </div>
    </header>

    <main class="container mx-auto px-4 py-16">

        <section class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            {{-- Email Us Card --}}
            <div class="bg-white p-8 rounded-lg shadow-lg text-center flex flex-col items-center justify-center transform transition-transform hover:scale-105 duration-300 border-t-4 border-primary-jlm">
                <div class="text-primary-jlm mb-4 text-5xl">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-2">Email Us</h3>
                <p class="text-gray-700 mb-4">Send us an email anytime!</p>
                <a href="mailto:support@learnerium.com" class="text-primary-jlm hover:underline font-medium text-lg">support@learnerium.com</a>
            </div>

            {{-- Call Us Card --}}
            <div class="bg-white p-8 rounded-lg shadow-lg text-center flex flex-col items-center justify-center transform transition-transform hover:scale-105 duration-300 border-t-4 border-secondary-jlm">
                <div class="text-secondary-jlm mb-4 text-5xl">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-2">Call Us</h3>
                <p class="text-gray-700 mb-4">Our support team is available during business hours.</p>
                <a href="tel:+2348012345678" class="text-secondary-jlm hover:underline font-medium text-lg">+234 (80) 123 45678</a>
            </div>

            {{-- Visit Us Card --}}
            <div class="bg-white p-8 rounded-lg shadow-lg text-center flex flex-col items-center justify-center transform transition-transform hover:scale-105 duration-300 border-t-4 border-accent-jlm">
                <div class="text-accent-jlm mb-4 text-5xl">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-2">Visit Us</h3>
                <p class="text-gray-700 mb-4">Our office is located at:</p>
                <address class="not-italic text-gray-700 text-lg">
                    123 Main Street, Victoria Island,<br> Lagos, Nigeria
                </address>
            </div>
        </section>

        <section class="bg-white p-8 rounded-lg shadow-lg max-w-2xl mx-auto border-t-4 border-primary-jlm">
            <h2 class="text-3xl font-bold text-primary-jlm mb-8 text-center">Send Us a Message</h2>
            <form action="#" method="POST" class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Your Name</label>
                    <input type="text" id="name" name="name" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary-jlm focus:border-primary-jlm sm:text-base" placeholder="John Doe" required>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Your Email</label>
                    <input type="email" id="email" name="email" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary-jlm focus:border-primary-jlm sm:text-base" placeholder="john.doe@example.com" required>
                </div>
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                    <input type="text" id="subject" name="subject" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary-jlm focus:border-primary-jlm sm:text-base" placeholder="Inquiry about courses" required>
                </div>
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Your Message</label>
                    <textarea id="message" name="message" rows="6" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary-jlm focus:border-primary-jlm sm:text-base" placeholder="Type your message here..." required></textarea>
                </div>
                <div>
                    <button type="submit" class="w-full inline-flex justify-center py-3 px-6 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-primary-jlm hover:bg-primary-jlm-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-jlm transition duration-300">
                        Send Message
                    </button>
                </div>
            </form>
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
