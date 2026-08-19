<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learnerium - Empower Your Learning Journey with JLM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f8f8;
        }
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
       
            <h1 class="text-5xl sm:text-6xl md:text-7xl font-extrabold leading-tight mb-6">
                Learning, Elevated by Creativity.
            </h1>
            <p class="text-xl sm:text-2xl md:text-3xl mb-10 opacity-95 max-w-4xl mx-auto">
                Unlock your potential with Learnerium, powered by JLM's creative, fast, and personalized approach to education.
            </p>
            <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ url('/courses') }}" class="bg-accent-jlm text-primary-jlm px-10 py-4 rounded-full text-lg font-semibold hover:bg-accent-jlm/90 transition duration-300 shadow-lg transform hover:scale-105">
                    Explore Courses
                </a>
                <a href="{{ route('register.instructor') }}" class="border-2 border-white text-white px-10 py-4 rounded-full text-lg font-semibold hover:bg-white hover:text-primary-jlm transition duration-300 transform hover:scale-105">
                    Become an Instructor
                </a>
            </div>
        </div>
    </header>

    <section class="py-12 px-4 bg-gray-jlm-light">
        <div class="container mx-auto">
            <h2 class="text-3xl font-bold text-gray-800 text-center mb-8">What do you want to learn today?</h2>
            <div class="flex justify-center mb-12">
                <div class="relative w-full max-w-2xl">
                    <input type="text" placeholder="Search for courses, skills, or topics..." class="w-full p-4 pl-12 rounded-full border border-gray-300 focus:ring-2 focus:ring-primary-jlm focus:border-transparent shadow-sm">
                    <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </div>
            </div>

            <h3 class="text-2xl font-bold text-gray-800 text-center mb-6">Popular Categories</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6">
                <a href="{{ url('/courses') }}" class="flex flex-col items-center p-4 bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 transform hover:-translate-y-1">
                    <img src="https://placehold.co/60x60/f7de7a/1b2299?text=Tech" alt="Technology" class="mb-3 rounded-full">
                    <span class="text-lg font-semibold text-primary-jlm text-center">Technology</span>
                </a>
                <a href="{{ url('/courses') }}" class="flex flex-col items-center p-4 bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 transform hover:-translate-y-1">
                    <img src="https://placehold.co/60x60/e4306d/ffffff?text=Biz" alt="Business" class="mb-3 rounded-full">
                    <span class="text-lg font-semibold text-primary-jlm text-center">Business</span>
                </a>
                <a href="{{ url('/courses') }}" class="flex flex-col items-center p-4 bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 transform hover:-translate-y-1">
                    <img src="https://placehold.co/60x60/1b2299/f7de7a?text=Arts" alt="Arts & Design" class="mb-3 rounded-full">
                    <span class="text-lg font-semibold text-primary-jlm text-center">Arts & Design</span>
                </a>
                <a href="{{ url('/courses') }}" class="flex flex-col items-center p-4 bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 transform hover:-translate-y-1">
                    <img src="https://placehold.co/60x60/f7de7a/1b2299?text=Media" alt="Media Production" class="mb-3 rounded-full">
                    <span class="text-lg font-semibold text-primary-jlm text-center">Media Production</span>
                </a>
                <a href="{{ url('/courses') }}" class="flex flex-col items-center p-4 bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 transform hover:-translate-y-1">
                    <img src="https://placehold.co/60x60/e4306d/ffffff?text=Sci" alt="Science & Data" class="mb-3 rounded-full">
                    <span class="text-lg font-semibold text-primary-jlm text-center">Science & Data</span>
                </a>
            </div>
        </div>
    </section>

    <section class="py-16 px-4 bg-white">
        <div class="container mx-auto text-center">
            <h2 class="text-4xl font-bold text-gray-800 mb-12">Our Core Values (Powered by JLM)</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="p-8 rounded-lg shadow-md border-t-4 border-primary-jlm bg-white hover:shadow-xl transition duration-300 transform hover:-translate-y-2">
                    <div class="text-primary-jlm text-5xl mb-4">⭐</div>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-4">Excellence</h3>
                    <p class="text-gray-600">We deliver learning experiences with precision and quality, ensuring your mastery.</p>
                </div>
                <div class="p-8 rounded-lg shadow-md border-t-4 border-secondary-jlm bg-white hover:shadow-xl transition duration-300 transform hover:-translate-y-2">
                    <div class="text-secondary-jlm text-5xl mb-4">⚡</div>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-4">Speed</h3>
                    <p class="text-gray-600">Efficient learning paths and prompt support to accelerate your progress.</p>
                </div>
                <div class="p-8 rounded-lg shadow-md border-t-4 border-accent-jlm bg-white hover:shadow-xl transition duration-300 transform hover:-translate-y-2">
                    <div class="text-accent-jlm text-5xl mb-4">🚀</div>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-4">Innovation</h3>
                    <p class="text-gray-600">Leveraging the latest tools and creative approaches for engaging e-learning.</p>
                </div>
                <div class="p-8 rounded-lg shadow-md border-t-4 border-primary-jlm bg-white hover:shadow-xl transition duration-300 transform hover:-translate-y-2">
                    <div class="text-primary-jlm text-5xl mb-4">👤</div>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-4">Personalisation</h3>
                    <p class="text-gray-600">Tailored learning paths and content designed to meet your unique needs.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 px-4 bg-gray-jlm-light">
        <div class="container mx-auto text-center">
            <h2 class="text-4xl font-bold text-gray-800 mb-12">What Our Learners Say</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-8 rounded-lg shadow-md border-l-4 border-secondary-jlm">
                    <p class="text-gray-700 text-lg italic mb-6">"Learnerium has opened up so many new opportunities for me. The courses are incredibly well-structured, and the instructors are truly inspiring!"</p>
                    <div class="flex items-center justify-center">
                        <img src="https://placehold.co/60x60/1b2299/f7de7a?text=JD" alt="John Doe" class="w-16 h-16 rounded-full mr-4">
                        <div>
                            <p class="font-semibold text-gray-900">Sarah K.</p>
                            <p class="text-gray-500 text-sm">Web Developer</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-8 rounded-lg shadow-md border-l-4 border-primary-jlm">
                    <p class="text-gray-700 text-lg italic mb-6">"I appreciate Learnerium's diverse course offerings and the flexibility to learn at my own pace. It's truly empowering!"</p>
                    <div class="flex items-center justify-center">
                        <img src="https://placehold.co/60x60/e4306d/ffffff?text=AS" alt="Jane Smith" class="w-16 h-16 rounded-full mr-4">
                        <div>
                            <p class="font-semibold text-gray-900">Michael T.</p>
                            <p class="text-gray-500 text-sm">Marketing Professional</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-primary-jlm text-white py-20 px-4 text-center">
        <div class="container mx-auto">
            <h2 class="text-4xl md:text-5xl font-bold mb-6">Ready to Elevate Your Skills?</h2>
            <p class="text-xl md:text-2xl mb-10 opacity-90 max-w-3xl mx-auto">
                Join the Learnerium community today and start your journey towards unlocking your full potential.
            </p>
            <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ url('/register') }}" class="bg-accent-jlm text-primary-jlm px-10 py-4 rounded-full text-lg font-semibold hover:bg-accent-jlm/90 transition duration-300 shadow-lg transform hover:scale-105">
                    Sign Up Now
                </a>
                <a href="{{ url('/courses') }}" class="border-2 border-white text-white px-10 py-4 rounded-full text-lg font-semibold hover:bg-white hover:text-primary-jlm transition duration-300 transform hover:scale-105">
                    View All Courses
                </a>
            </div>
        </div>
    </section>

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
                        <li><a href="{{ url('/about') }}" class="text-gray-400 hover:text-white transition duration-200">About Us</a></li>
                        <li><a href="{{ url('/contact') }}" class="text-gray-400 hover:text-white transition duration-200">Contact Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-200">Careers</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Explore</h4>
                    <ul>
                        <li><a href="{{ url('/courses') }}" class="text-gray-400 hover:text-white transition duration-200">Courses</a></li>
                        <li><a href="{{ url('/instructors') }}" class="text-gray-400 hover:text-white transition duration-200">Instructors</a></li>
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

    <script>
        document.getElementById('nav-toggle').onclick = function () {
            document.getElementById('nav-content').classList.toggle('hidden');
        }

        // Optional: Close dropdown when clicking outside (for the user menu)
        document.addEventListener('click', function(event) {
            const userMenuButton = document.querySelector('.group > button');
            const userMenuDropdown = document.querySelector('.group > div');

            if (userMenuButton && userMenuDropdown) {
                if (!userMenuButton.contains(event.target) && !userMenuDropdown.contains(event.target)) {
                    userMenuDropdown.classList.add('opacity-0', 'pointer-events-none');
                    userMenuDropdown.classList.remove('opacity-100', 'pointer-events-auto');
                }
            }
        });
    </script>

</body>
</html>