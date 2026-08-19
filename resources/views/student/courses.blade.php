<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learnerium - My Courses</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
    <main class="max-w-5xl mx-auto px-6 py-12">
        <h1 class="text-3xl font-bold mb-6">My Courses</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse ($courses as $course)
                <div class="bg-white rounded shadow p-4">
                    <h2 class="text-xl font-semibold mb-1">{{ $course->title }}</h2>
                    <p class="text-sm text-gray-600 mb-2">Instructor: {{ $course->instructor?->name ?? 'Instructor' }}</p>
                    <div class="mb-3">
                        <p class="text-sm text-gray-600">Progress: {{ $course->pivot?->progress_percentage ?? 0 }}%</p>
                        <div class="w-full bg-gray-200 rounded-full h-3 mt-1">
                            <div class="bg-blue-500 h-3 rounded-full" style="width: {{ $course->pivot?->progress_percentage ?? 0 }}%"></div>
                        </div>
                    </div>
                    <a href="{{ route('course.detail', $course->slug) }}" class="text-blue-600 hover:underline">Go to Course</a>
                </div>
            @empty
                <div class="bg-white rounded shadow p-6 text-gray-600">
                    You are not enrolled in any courses yet.
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            <a href="{{ route('student.dashboard') }}" class="text-blue-600 hover:underline">Back to Dashboard</a>
        </div>
    </main>
</body>
</html>
