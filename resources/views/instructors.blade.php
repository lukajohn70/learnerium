<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learnerium - Meet Our Expert Instructors</title>
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
                Meet Our Expert Instructors
            </h1>
            <p class="text-xl md:text-2xl mb-8 opacity-90 max-w-3xl mx-auto">
                Learn from industry leaders, creative minds, and passionate educators.
            </p>
            <div class="relative max-w-2xl mx-auto">
                <input type="text" placeholder="Search for an instructor..." class="w-full p-4 pl-12 rounded-full border border-gray-300 focus:ring-2 focus:ring-accent-jlm focus:border-transparent shadow-sm text-gray-800">
                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </div>
        </div>
    </header>

    <main class="py-16 px-4 bg-gray-jlm-light">
        <div class="container mx-auto">
            <h2 class="text-4xl font-bold text-center text-gray-900 mb-12">Our Talented Educators</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <div class="bg-white rounded-lg shadow-md overflow-hidden text-center p-6 hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                    <img src="https://placehold.co/150x150/1b2299/f7de7a?text=SK" alt="Instructor Sarah K." class="w-32 h-32 rounded-full object-cover mx-auto mb-4 border-4 border-primary-jlm">
                    <h3 class="font-bold text-xl text-primary-jlm mb-1">Sarah K.</h3>
                    <p class="text-secondary-jlm text-sm mb-3">Web Development Expert</p>
                    <p class="text-gray-700 text-sm mb-4 line-clamp-3">Passionate about teaching and simplifying complex web technologies. She has over a decade of experience building cutting-edge web applications.</p>
                    <div class="flex items-center justify-center text-gray-600 text-sm mb-4">
                        <span class="mr-3">⭐ 4.8</span>
                        <span>🎓 5 Courses</span>
                    </div>
                    <a href="#" class="bg-accent-jlm text-primary-jlm px-6 py-2 rounded-full text-sm font-semibold hover:bg-accent-jlm/90 transition duration-300 shadow-md">View Profile</a>
                </div>

                <div class="bg-white rounded-lg shadow-md overflow-hidden text-center p-6 hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                    <img src="https://placehold.co/150x150/e4306d/ffffff?text=MT" alt="Instructor Michael T." class="w-32 h-32 rounded-full object-cover mx-auto mb-4 border-4 border-secondary-jlm">
                    <h3 class="font-bold text-xl text-primary-jlm mb-1">Michael T.</h3>
                    <p class="text-secondary-jlm text-sm mb-3">Music Production Guru</p>
                    <p class="text-gray-700 text-sm mb-4 line-clamp-3">Award-winning music producer and sound engineer. Michael brings real-world studio insights to his comprehensive music courses.</p>
                    <div class="flex items-center justify-center text-gray-600 text-sm mb-4">
                        <span class="mr-3">⭐ 4.9</span>
                        <span>🎓 3 Courses</span>
                    </div>
                    <a href="#" class="bg-accent-jlm text-primary-jlm px-6 py-2 rounded-full text-sm font-semibold hover:bg-accent-jlm/90 transition duration-300 shadow-md">View Profile</a>
                </div>

                <div class="bg-white rounded-lg shadow-md overflow-hidden text-center p-6 hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                    <img src="https://placehold.co/150x150/f7de7a/1b2299?text=DA" alt="Instructor Dr. Ada Obi" class="w-32 h-32 rounded-full object-cover mx-auto mb-4 border-4 border-accent-jlm">
                    <h3 class="font-bold text-xl text-primary-jlm mb-1">Dr. Ada Obi</h3>
                    <p class="text-secondary-jlm text-sm mb-3">Data Science Lead</p>
                    <p class="text-gray-700 text-sm mb-4 line-clamp-3">A data science veteran with a knack for simplifying complex algorithms. Dr. Obi's courses are perfect for aspiring data professionals.</p>
                    <div class="flex items-center justify-center text-gray-600 text-sm mb-4">
                        <span class="mr-3">⭐ 4.7</span>
                        <span>🎓 7 Courses</span>
                    </div>
                    <a href="#" class="bg-accent-jlm text-primary-jlm px-6 py-2 rounded-full text-sm font-semibold hover:bg-accent-jlm/90 transition duration-300 shadow-md">View Profile</a>
                </div>

                <div class="bg-white rounded-lg shadow-md overflow-hidden text-center p-6 hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                    <img src="https://placehold.co/150x150/1b2299/ffffff?text=NE" alt="Instructor Nkechi Eze" class="w-32 h-32 rounded-full object-cover mx-auto mb-4 border-4 border-primary-jlm">
                    <h3 class="font-bold text-xl text-primary-jlm mb-1">Nkechi Eze</h3>
                    <p class="text-secondary-jlm text-sm mb-3">Graphic Design Innovator</p>
                    <p class="text-gray-700 text-sm mb-4 line-clamp-3">A creative powerhouse, Nkechi brings fresh perspectives to design principles, helping students unleash their artistic potential.</p>
                    <div class="flex items-center justify-center text-gray-600 text-sm mb-4">
                        <span class="mr-3">⭐ 4.6</span>
                        <span>🎓 4 Courses</span>
                    </div>
                    <a href="#" class="bg-accent-jlm text-primary-jlm px-6 py-2 rounded-full text-sm font-semibold hover:bg-accent-jlm/90 transition duration-300 shadow-md">View Profile</a>
                </div>

                <div class="bg-white rounded-lg shadow-md overflow-hidden text-center p-6 hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                    <img src="https://placehold.co/150x150/e4306d/f7de7a?text=DO" alt="Instructor David Okoro" class="w-32 h-32 rounded-full object-cover mx-auto mb-4 border-4 border-secondary-jlm">
                    <h3 class="font-bold text-xl text-primary-jlm mb-1">David Okoro</h3>
                    <p class="text-secondary-jlm text-sm mb-3">Business Strategist</p>
                    <p class="text-gray-700 text-sm mb-4 line-clamp-3">An experienced entrepreneur and business consultant, David provides practical strategies for starting and growing successful ventures.</p>
                    <div class="flex items-center justify-center text-gray-600 text-sm mb-4">
                        <span class="mr-3">⭐ 4.9</span>
                        <span>🎓 6 Courses</span>
                    </div>
                    <a href="#" class="bg-accent-jlm text-primary-jlm px-6 py-2 rounded-full text-sm font-semibold hover:bg-accent-jlm/90 transition duration-300 shadow-md">View Profile</a>
                </div>

                <div class="bg-white rounded-lg shadow-md overflow-hidden text-center p-6 hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                    <img src="https://placehold.co/150x150/f7de7a/e4306d?text=AF" alt="Instructor Adeola F." class="w-32 h-32 rounded-full object-cover mx-auto mb-4 border-4 border-accent-jlm">
                    <h3 class="font-bold text-xl text-primary-jlm mb-1">Adeola F.</h3>
                    <p class="text-secondary-jlm text-sm mb-3">Professional Photographer</p>
                    <p class="text-gray-700 text-sm mb-4 line-clamp-3">Adeola's passion for photography is infectious. She shares her secrets to capturing stunning images, from beginner to advanced techniques.</p>
                    <div class="flex items-center justify-center text-gray-600 text-sm mb-4">
                        <span class="mr-3">⭐ 4.5</span>
                        <span>🎓 2 Courses</span>
                    </div>
                    <a href="#" class="bg-accent-jlm text-primary-jlm px-6 py-2 rounded-full text-sm font-semibold hover:bg-accent-jlm/90 transition duration-300 shadow-md">View Profile</a>
                </div>

            </div>
            <div class="text-center mt-12">
                <button class="bg-primary-jlm text-white px-8 py-3 rounded-full text-lg font-semibold hover:bg-primary-jlm-dark transition duration-300 shadow-md transform hover:scale-105">View More Instructors</button>
            </div>
        </div>
    </main>

    <section class="bg-primary-jlm text-white py-16 px-4 text-center mt-16">
        <div class="container mx-auto">
            <h2 class="text-4xl md:text-5xl font-extrabold mb-6">Want to Share Your Knowledge?</h2>
            <p class="text-xl md:text-2xl mb-10 max-w-3xl mx-auto opacity-90">
                Join Learnerium's growing community of expert instructors and impact learners worldwide.
            </p>
            <a href="#" class="bg-accent-jlm text-primary-jlm px-10 py-4 rounded-full text-lg font-semibold hover:bg-accent-jlm/90 transition duration-300 shadow-lg transform hover:scale-105">
                Become an Instructor
            </a>
        </div>
    </section>

    <footer class="bg-gray-800 text-white py-8 px-4 mt-16">
        <div class="container mx-auto text-center text-gray-400">
            <p>&copy; {{ date('Y') }} Learnerium. Powered by JLM. Creative. Fast. Personalised.</p>
            <div class="mt-4 space-x-4 text-sm">
                <a href="#" class="hover:text-secondary-jlm">Privacy Policy</a>
                <a href="#" class="hover:text-secondary-jlm">Terms of Service</a>
                <a href="#" class="hover:text-secondary-jlm">Sitemap</a>
            </div>
        </div>
    </footer>

</body>
</html>
