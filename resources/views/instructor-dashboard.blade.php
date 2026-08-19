<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learnerium - Instructor Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
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

    <nav class="bg-white shadow-sm p-4 sticky top-0 z-50">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-center">
            <a href="/" class="text-3xl font-extrabold text-primary-jlm mb-4 md:mb-0">Learnerium</a>
            <div class="flex flex-wrap justify-center md:space-x-4 space-x-2">
                <a href="/" class="text-gray-700 hover:text-primary-jlm transition duration-300 px-2 py-1">Home</a>
                <a href="/courses" class="text-gray-700 hover:text-primary-jlm transition duration-300 px-2 py-1">Courses</a>
                <a href="/instructors" class="text-primary-jlm font-semibold transition duration-300 px-2 py-1">Instructors</a>
                <a href="/about" class="text-gray-700 hover:text-primary-jlm transition duration-300 px-2 py-1">About Us</a>
                <a href="/contact" class="text-gray-700 hover:text-primary-jlm transition duration-300 px-2 py-1">Contact</a>
                
                <div class="relative group">
                    <button class="flex items-center space-x-2 text-gray-700 hover:text-primary-jlm transition duration-300 px-2 py-1 focus:outline-none">
                        <img src="https://placehold.co/32x32/secondary-jlm/ffffff?text=TD" alt="Instructor Profile" class="w-8 h-8 rounded-full border-2 border-secondary-jlm">
                        <span class="hidden md:inline-block">Instructor Name</span> <svg class="h-4 w-4 text-gray-700 group-hover:text-primary-jlm" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20 hidden group-hover:block">
                        <a href="/instructor/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Manage Courses</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Student Analytics</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile Settings</a>
                        <a href="/logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 border-t border-gray-100">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <header class="bg-gradient-to-br from-primary-jlm to-secondary-jlm text-white py-20 px-4 text-center relative overflow-hidden">
        <div class="container mx-auto relative z-10">
            <h1 class="text-5xl md:text-6xl font-extrabold leading-tight mb-6">
                Instructor Dashboard
            </h1>
            <p class="text-xl md:text-2xl mb-8 opacity-90 max-w-3xl mx-auto">
                Manage your courses, engage with students, and track your impact.
            </p>
        </div>
    </header>

    <main class="container mx-auto px-4 py-16">

        <section class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16 text-center">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-2xl font-semibold text-gray-900 mb-2">Total Courses</h3>
                <p class="text-5xl font-extrabold text-primary-jlm">3</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-2xl font-semibold text-gray-900 mb-2">Total Students</h3>
                <p class="text-5xl font-extrabold text-primary-jlm">150</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-2xl font-semibold text-gray-900 mb-2">Avg. Rating</h3>
                <p class="text-5xl font-extrabold text-primary-jlm">4.8 <span class="text-xl">/ 5</span></p>
            </div>
        </section>

        <section class="mb-16">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-4xl font-bold text-primary-jlm">My Courses</h2>
                <a href="{{ route('instructor.courses.create') }}" class="bg-primary-jlm text-white px-6 py-3 rounded-lg hover:bg-primary-jlm-dark transition duration-300 shadow-md">
                    + Create New Course
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col">
                    <img src="https://placehold.co/400x250/f7de7a/1b2299?text=Your+Course+1" alt="Course Thumbnail" class="w-full h-48 object-cover">
                    <div class="p-6 flex-grow">
                        <h3 class="text-2xl font-semibold text-gray-900 mb-2">Advanced JavaScript for Web Development</h3>
                        <p class="text-gray-600 text-sm mb-4">Enrolled Students: 50</p>
                        <div class="flex justify-between space-x-2">
                            <a href="#" class="flex-1 text-center bg-secondary-jlm text-white px-4 py-2 rounded-lg hover:bg-secondary-jlm/90 transition duration-300 font-medium text-sm">Edit Course</a>
                            <a href="#" class="flex-1 text-center border border-primary-jlm text-primary-jlm px-4 py-2 rounded-lg hover:bg-primary-jlm/10 transition duration-300 font-medium text-sm">View Students</a>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col">
                    <img src="https://placehold.co/400x250/1b2299/f7de7a?text=Your+Course+2" alt="Course Thumbnail" class="w-full h-48 object-cover">
                    <div class="p-6 flex-grow">
                        <h3 class="text-2xl font-semibold text-gray-900 mb-2">Creative Writing Masterclass</h3>
                        <p class="text-gray-600 text-sm mb-4">Enrolled Students: 30</p>
                        <div class="flex justify-between space-x-2">
                            <a href="#" class="flex-1 text-center bg-secondary-jlm text-white px-4 py-2 rounded-lg hover:bg-secondary-jlm/90 transition duration-300 font-medium text-sm">Edit Course</a>
                            <a href="#" class="flex-1 text-center border border-primary-jlm text-primary-jlm px-4 py-2 rounded-lg hover:bg-primary-jlm/10 transition duration-300 font-medium text-sm">View Students</a>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col">
                    <img src="https://placehold.co/400x250/e4306d/ffffff?text=Your+Course+3" alt="Course Thumbnail" class="w-full h-48 object-cover">
                    <div class="p-6 flex-grow">
                        <h3 class="text-2xl font-semibold text-gray-900 mb-2">Introduction to Python for Data Science</h3>
                        <p class="text-gray-600 text-sm mb-4">Enrolled Students: 70</p>
                        <div class="flex justify-between space-x-2">
                            <a href="#" class="flex-1 text-center bg-secondary-jlm text-white px-4 py-2 rounded-lg hover:bg-secondary-jlm/90 transition duration-300 font-medium text-sm">Edit Course</a>
                            <a href="#" class="flex-1 text-center border border-primary-jlm text-primary-jlm px-4 py-2 rounded-lg hover:bg-primary-jlm/10 transition duration-300 font-medium text-sm">View Students</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <footer class="bg-gray-900 text-white py-10 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center md:items-start">
            <div class="mb-8 md:mb-0 text-center md:text-left">
                <h3 class="text-2xl font-extrabold text-white mb-2">Learnerium</h3>
                <p class="text-gray-400 text-sm">&copy; 2025 Learnerium. All rights reserved.</p>
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
