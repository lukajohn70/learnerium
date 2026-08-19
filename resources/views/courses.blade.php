<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learnerium - Explore Our Diverse Courses</title>
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
                Discover Your Next Skill
            </h1>
            <p class="text-xl md:text-2xl mb-8 opacity-90 max-w-3xl mx-auto">
                Explore thousands of courses designed to empower your learning journey.
            </p>
            <div class="relative max-w-2xl mx-auto">
                <input type="text" placeholder="Search for courses..." class="w-full p-4 pl-12 rounded-full border border-gray-300 focus:ring-2 focus:ring-accent-jlm focus:border-transparent shadow-sm text-gray-800">
                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </div>
        </div>
    </header>

    <main class="py-16 px-4 bg-gray-jlm-light">
        <div class="container mx-auto flex flex-col lg:flex-row gap-8">
            <aside class="lg:w-1/4 bg-white p-6 rounded-lg shadow-md h-fit sticky top-24">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Filter Courses</h2>

                <div class="mb-6">
                    <h3 class="font-semibold text-lg text-primary-jlm mb-3">Categories</h3>
                    <ul class="space-y-2 text-gray-700">
                        <li><a href="#" class="hover:text-secondary-jlm transition duration-200">Technology (120)</a></li>
                        <li><a href="#" class="hover:text-secondary-jlm transition duration-200">Business (85)</a></li>
                        <li><a href="#" class="hover:text-secondary-jlm transition duration-200">Arts & Design (60)</a></li>
                        <li><a href="#" class="hover:text-secondary-jlm transition duration-200">Media Production (45)</a></li>
                        <li><a href="#" class="hover:text-secondary-jlm transition duration-200">Science & Data (70)</a></li>
                        <li><a href="#" class="hover:text-secondary-jlm transition duration-200">Personal Development (90)</a></li>
                    </ul>
                </div>

                <div class="mb-6">
                    <h3 class="font-semibold text-lg text-primary-jlm mb-3">Level</h3>
                    <ul class="space-y-2 text-gray-700">
                        <li><a href="#" class="hover:text-secondary-jlm transition duration-200">Beginner</a></li>
                        <li><a href="#" class="hover:text-secondary-jlm transition duration-200">Intermediate</a></li>
                        <li><a href="#" class="hover:text-secondary-jlm transition duration-200">Advanced</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold text-lg text-primary-jlm mb-3">Price</h3>
                    <ul class="space-y-2 text-gray-700">
                        <li><a href="#" class="hover:text-secondary-jlm transition duration-200">Free</a></li>
                        <li><a href="#" class="hover:text-secondary-jlm transition duration-200">Paid</a></li>
                    </ul>
                </div>
            </aside>

            <section class="lg:w-3/4">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800">All Courses</h2>
                    <div class="relative">
                        <select class="block appearance-none w-full bg-white border border-gray-300 text-gray-700 py-3 px-4 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-primary-jlm shadow-sm">
                            <option>Sort by: Popularity</option>
                            <option>Sort by: Newest</option>
                            <option>Sort by: Rating</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @forelse ($courses as $course)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                            <img src="{{ $course->thumbnail ?? 'https://placehold.co/400x250/f7de7a/1b2299?text=Course' }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                            <div class="p-6">
                                <h3 class="font-bold text-xl text-primary-jlm mb-2">{{ $course->title }}</h3>
                                <p class="text-gray-700 text-sm mb-4">{{ \Illuminate\Support\Str::limit($course->description, 120) }}</p>
                                <div class="flex items-center text-gray-600 text-sm mb-4">
                                    <span class="mr-2">👨‍🏫 {{ $course->instructor?->name ?? 'Instructor' }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-primary-jlm font-bold text-lg">
                                        {{ $course->price > 0 ? '₦' . number_format($course->price, 2) : 'Free' }}
                                    </span>
                                    <a href="{{ route('course.detail', $course->slug) }}" class="bg-secondary-jlm text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-secondary-jlm/90 transition duration-300">View Course</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 bg-white rounded-lg shadow-md p-6">
                            <p class="text-gray-600">No published courses yet.</p>
                        </div>
                    @endforelse
                </div>
                <div class="text-center mt-12">
                    <button class="bg-primary-jlm text-white px-8 py-3 rounded-full text-lg font-semibold hover:bg-primary-jlm-dark transition duration-300 shadow-md transform hover:scale-105">Load More Courses</button>
                </div>
            </section>
        </div>
    </main>

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
