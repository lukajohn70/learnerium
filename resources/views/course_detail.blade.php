<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learnerium - Course: Introduction to Web Development</title>
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
                <a href="{{ url('/') }}" class="text-gray-700 hover:text-primary-jlm transition duration-300 px-2 py-1">Home</a>
<a href="{{ url('/courses') }}" class="text-gray-700 hover:text-primary-jlm transition duration-300 px-2 py-1">Courses</a>
<a href="{{ url('/instructors') }}" class="text-gray-700 hover:text-primary-jlm transition duration-300 px-2 py-1">Instructors</a>
<a href="{{ url('/about') }}" class="text-gray-700 hover:text-primary-jlm transition duration-300 px-2 py-1">About Us</a>
<a href="{{ url('/contact') }}" class="text-gray-700 hover:text-primary-jlm transition duration-300 px-2 py-1">Contact</a>
<a href="{{ url('/login') }}" class="bg-primary-jlm text-white px-4 py-2 rounded-lg hover:bg-primary-jlm-dark transition duration-300 shadow-md">Login</a>
<a href="{{ url('/register') }}" class="border border-primary-jlm text-primary-jlm px-4 py-2 rounded-lg hover:bg-primary-jlm/10 transition duration-300">Register</a>
            </div>
        </div>
    </nav>

    <section class="bg-gray-900 text-white py-16 px-4">
        <div class="container mx-auto flex flex-col lg:flex-row items-center lg:items-start gap-8">
            <div class="lg:w-1/2 w-full">
                <img src="https://placehold.co/700x400/1b2299/f7de7a?text=Course+Video+Thumbnail" alt="Course Thumbnail" class="rounded-lg shadow-lg w-full">
            </div>
            <div class="lg:w-1/2 w-full lg:text-left text-center">
                <h1 class="text-4xl md:text-5xl font-extrabold mb-4 leading-tight">{{ $course->title }}</h1>
                <p class="text-xl opacity-90 mb-6">{{ $course->description }}</p>

                <div class="flex items-center lg:justify-start justify-center mb-6 space-x-4">
                    <div class="flex items-center">
                        <span class="text-accent-jlm text-2xl mr-2">⭐</span>
                        <span class="text-2xl font-bold">4.8</span>
                        <span class="text-gray-400 text-lg ml-2">(8,500 ratings)</span>
                    </div>
                    <div class="flex items-center">
                        <img src="https://placehold.co/40x40/e4306d/ffffff?text=SK" alt="Instructor Profile" class="rounded-full mr-2">
                        <a href="#" class="text-lg font-semibold text-secondary-jlm hover:underline">{{ $course->instructor?->name ?? 'Instructor' }}</a>
                    </div>
                </div>

                <p class="text-gray-300 text-sm mb-6">Last updated: {{ optional($course->published_at)->format('M Y') ?? optional($course->created_at)->format('M Y') }}</p>

                <div class="text-center lg:text-left">
                    <span class="text-accent-jlm font-bold text-4xl mr-4">
                        {{ $course->price > 0 ? '₦' . number_format($course->price, 2) : 'Free' }}
                    </span>
                    @auth
                        @php
                            $isEnrolled = auth()->user()->coursesEnrolled->contains($course->id);
                        @endphp
                        @if ($isEnrolled)
                            <span class="inline-block bg-green-600 text-white px-6 py-3 rounded-full text-lg font-semibold">Enrolled</span>
                        @else
                            <form action="{{ route('courses.enroll', $course) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="bg-secondary-jlm text-white px-8 py-4 rounded-full text-xl font-semibold hover:bg-secondary-jlm/90 transition duration-300 shadow-lg transform hover:scale-105">Enroll Now</button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="bg-secondary-jlm text-white px-8 py-4 rounded-full text-xl font-semibold hover:bg-secondary-jlm/90 transition duration-300 shadow-lg transform hover:scale-105 inline-block">Login to Enroll</a>
                    @endauth
                </div>
                @if (session('status'))
                    <div class="mt-4 text-green-300">{{ session('status') }}</div>
                @endif
            </div>
        </div>
    </section>

    <main class="py-16 px-4 bg-gray-jlm-light">
        <div class="container mx-auto flex flex-col lg:flex-row gap-12">
            <div class="lg:w-2/3 w-full space-y-12">
                <section class="bg-white p-8 rounded-lg shadow-md">
                    <h2 class="text-3xl font-bold text-primary-jlm mb-6">What you'll learn</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-secondary-jlm mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Build responsive web pages with HTML5 and CSS3.</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-secondary-jlm mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Add interactivity to websites using JavaScript.</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-secondary-jlm mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Understand fundamental web development concepts.</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-secondary-jlm mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Deploy your projects live on the internet.</span>
                        </div>
                    </div>
                </section>

                <section class="bg-white p-8 rounded-lg shadow-md">
                    <h2 class="text-3xl font-bold text-primary-jlm mb-6">Course Content</h2>
                    <div id="accordion-curriculum">
                        <div class="border-b border-gray-200 py-4">
                            <button class="flex justify-between items-center w-full text-left font-semibold text-xl text-gray-800 focus:outline-none">
                                <span>Module 1: HTML Fundamentals</span>
                                <svg class="w-5 h-5 text-gray-500 transform rotate-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div class="mt-3 text-gray-600 hidden">
                                <ul class="list-disc list-inside space-y-2">
                                    <li>Lesson 1.1: Introduction to HTML</li>
                                    <li>Lesson 1.2: HTML Structure and Elements</li>
                                    <li>Lesson 1.3: Text Formatting and Links</li>
                                    <li>Lesson 1.4: Images and Multimedia</li>
                                </ul>
                            </div>
                        </div>
                        <div class="border-b border-gray-200 py-4">
                            <button class="flex justify-between items-center w-full text-left font-semibold text-xl text-gray-800 focus:outline-none">
                                <span>Module 2: CSS Styling</span>
                                <svg class="w-5 h-5 text-gray-500 transform rotate-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div class="mt-3 text-gray-600 hidden">
                                <ul class="list-disc list-inside space-y-2">
                                    <li>Lesson 2.1: CSS Introduction and Selectors</li>
                                    <li>Lesson 2.2: The Box Model</li>
                                    <li>Lesson 2.3: Flexbox and Grid</li>
                                    <li>Lesson 2.4: Responsive Design</li>
                                </ul>
                            </div>
                        </div>
                        <div class="py-4">
                            <button class="flex justify-between items-center w-full text-left font-semibold text-xl text-gray-800 focus:outline-none">
                                <span>Module 3: JavaScript Interactivity</span>
                                <svg class="w-5 h-5 text-gray-500 transform rotate-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div class="mt-3 text-gray-600 hidden">
                                <ul class="list-disc list-inside space-y-2">
                                    <li>Lesson 3.1: Basics of JavaScript</li>
                                    <li>Lesson 3.2: DOM Manipulation</li>
                                    <li>Lesson 3.3: Events and Functions</li>
                                    <li>Lesson 3.4: Project: Interactive To-Do List</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="bg-white p-8 rounded-lg shadow-md">
                    <h2 class="text-3xl font-bold text-primary-jlm mb-6">About the Instructor</h2>
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                        <img src="https://placehold.co/120x120/e4306d/ffffff?text=SK" alt="Instructor Profile Picture" class="rounded-full w-32 h-32 object-cover shadow-lg">
                        <div class="text-center sm:text-left">
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">Sarah K.</h3>
                            <p class="text-secondary-jlm font-semibold mb-3">Experienced Web Developer & Educator</p>
                            <p class="text-gray-700 leading-relaxed">Sarah is a passionate web developer with over 10 years of experience. She loves sharing her knowledge and empowering others to build amazing things online. Her teaching style is practical, clear, and focused on real-world application.</p>
                            <div class="flex items-center sm:justify-start justify-center text-gray-600 text-sm mt-4">
                                <span class="mr-4">⭐ 4.8 Instructor Rating</span>
                                <span>🎓 5 Courses</span>
                            </div>
                            <a href="#" class="mt-4 inline-block text-primary-jlm hover:underline font-semibold">View Instructor Profile</a>
                        </div>
                    </div>
                </section>

                <section class="bg-white p-8 rounded-lg shadow-md">
                    <h2 class="text-3xl font-bold text-primary-jlm mb-6">Student Feedback</h2>
                    <div class="flex items-center mb-6">
                        <span class="text-accent-jlm text-5xl font-bold mr-4">4.8</span>
                        <div>
                            <div class="flex items-center text-accent-jlm mb-1">
                                ⭐⭐⭐⭐⭐
                            </div>
                            <p class="text-gray-600 text-sm">(8,500 ratings)</p>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <div class="border-b pb-4 border-gray-100">
                            <div class="flex items-center mb-2">
                                <img src="https://placehold.co/40x40/1b2299/f7de7a?text=JD" alt="User Avatar" class="w-10 h-10 rounded-full mr-3">
                                <div>
                                    <p class="font-semibold text-gray-900">John D.</p>
                                    <p class="text-gray-500 text-sm">2 days ago</p>
                                </div>
                            </div>
                            <div class="flex items-center text-accent-jlm text-sm mb-2">
                                ⭐⭐⭐⭐⭐
                            </div>
                            <p class="text-gray-700 leading-relaxed">"Absolutely fantastic course! Sarah explains complex topics in a very easy-to-understand way. I learned so much and feel confident in building my own websites now."</p>
                        </div>

                        <div class="border-b pb-4 border-gray-100">
                            <div class="flex items-center mb-2">
                                <img src="https://placehold.co/40x40/f7de7a/1b2299?text=AT" alt="User Avatar" class="w-10 h-10 rounded-full mr-3">
                                <div>
                                    <p class="font-semibold text-gray-900">Aisha T.</p>
                                    <p class="text-gray-500 text-sm">1 week ago</p>
                                </div>
                            </div>
                            <div class="flex items-center text-accent-jlm text-sm mb-2">
                                ⭐⭐⭐⭐
                            </div>
                            <p class="text-gray-700 leading-relaxed">"A solid introduction to web development. I appreciated the practical exercises. A bit fast-paced at times, but overall a great learning experience."</p>
                        </div>

                        <div>
                            <div class="flex items-center mb-2">
                                <img src="https://placehold.co/40x40/e4306d/ffffff?text=CM" alt="User Avatar" class="w-10 h-10 rounded-full mr-3">
                                <div>
                                    <p class="font-semibold text-gray-900">Chisom M.</p>
                                    <p class="text-gray-500 text-sm">3 weeks ago</p>
                                </div>
                            </div>
                            <div class="flex items-center text-accent-jlm text-sm mb-2">
                                ⭐⭐⭐⭐⭐
                            </div>
                            <p class="text-gray-700 leading-relaxed">"Highly recommend this course! The content is comprehensive, and the instructor is very engaging. Learnerium makes learning enjoyable."</p>
                        </div>
                    </div>
                </section>
            </div>

            <aside class="lg:w-1/3 w-full">
                <div class="bg-white p-6 rounded-lg shadow-xl sticky top-24 border-t-4 border-primary-jlm">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Course Includes:</h3>
                    <ul class="space-y-3 text-gray-700 text-lg mb-6">
                        <li class="flex items-center">
                            <svg class="w-6 h-6 text-secondary-jlm mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-3 1m8-14v-3m-2 3v-2.25M13.5 16h-11V6h11v10zm-4-9h4m-4 4h4m-4 4h4m-4-10H3.75L3 18l3-1 2-2zM15 11l-3-3m0 0l-3 3m3-3v8"></path></svg>
                            <span>12 hours on-demand video</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-6 h-6 text-secondary-jlm mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14v6m-3-3h6m-1-4l-8-8-6 6v6h14z"></path></svg>
                            <span>15 downloadable resources</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-6 h-6 text-secondary-jlm mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-3 3z"></path></svg>
                            <span>Full lifetime access</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-6 h-6 text-secondary-jlm mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Certificate of completion</span>
                        </li>
                    </ul>
                    <div class="text-center">
                        <span class="text-primary-jlm font-bold text-5xl block mb-4">$49.99</span>
                        <a href="#" class="bg-accent-jlm text-primary-jlm px-8 py-4 rounded-full text-xl font-bold hover:bg-accent-jlm/90 transition duration-300 shadow-xl transform hover:scale-105 block w-full mb-4">Buy Now</a>
                        <button class="bg-gray-100 text-primary-jlm border border-primary-jlm px-8 py-4 rounded-full text-xl font-bold hover:bg-gray-200 transition duration-300 shadow-md block w-full">Add to Cart</button>
                    </div>
                </div>
            </aside>
        </div>
    </main>

    <footer class="bg-gray-800 text-white py-8 px-4 mt-16">
        <div class="container mx-auto text-center text-gray-400">
            <p>&copy; 2025 Learnerium. Powered by JLM. Creative. Fast. Personalised.</p>
            <div class="mt-4 space-x-4 text-sm">
                <a href="#" class="hover:text-secondary-jlm">Privacy Policy</a>
                <a href="#" class="hover:text-secondary-jlm">Terms of Service</a>
                <a href="#" class="hover:text-secondary-jlm">Sitemap</a>
            </div>
        </div>
    </footer>

    <script>
        // Simple accordion functionality for curriculum
        document.addEventListener('DOMContentLoaded', () => {
            const accordionButtons = document.querySelectorAll('#accordion-curriculum button');

            accordionButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const content = button.nextElementSibling;
                    const icon = button.querySelector('svg');

                    content.classList.toggle('hidden');
                    icon.classList.toggle('rotate-180'); // Rotate arrow icon
                });
            });
        });
    </script>
</body>
</html>
