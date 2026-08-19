<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learnerium - Manage Courses</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
    <main class="max-w-5xl mx-auto px-6 py-12">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold">Manage Courses</h1>
            <a href="{{ route('instructor.courses.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Create Course</a>
        </div>

        @if (session('status'))
            <div class="mb-4 bg-green-100 text-green-800 px-4 py-2 rounded">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white rounded shadow">
            <div class="grid grid-cols-12 gap-4 px-4 py-3 border-b text-sm font-semibold text-gray-600">
                <div class="col-span-6">Title</div>
                <div class="col-span-3">Level</div>
                <div class="col-span-3">Actions</div>
            </div>
            @forelse ($courses as $course)
                <div class="grid grid-cols-12 gap-4 px-4 py-3 border-b text-sm items-center">
                    <div class="col-span-6">
                        {{ $course->title }}
                        <div class="mt-1">
                            @php
                                $avgProgress = $course->enrollments->count() ? round($course->enrollments->avg('progress_percentage')) : 0;
                            @endphp
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ $avgProgress }}%"></div>
                            </div>
                            <span class="text-xs text-gray-600">Avg Progress: {{ $avgProgress }}%</span>
                        </div>
                    </div>
                    <div class="col-span-3">
                        {{ $course->level }}
                    </div>
                    <div class="col-span-3">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <a href="{{ route('instructor.courses.edit', $course) }}" class="bg-pink-600 text-white px-6 py-4 rounded">Edit Course</a>
                            <a href="{{ route('instructor.courses.students', $course->id) }}" class="border border-blue-700 text-blue-700 px-6 py-4 rounded">View Students</a>
                            @if (!$course->published_at)
                                <form action="{{ route('instructor.courses.publish', $course->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:underline">Publish</button>
                                </form>
                            @else
                                <span class="text-gray-500">Published {{ $course->published_at->format('M d, Y') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-4 py-6 text-gray-600">No courses yet.</div>
            @endforelse
        </div>

        <div class="mt-6">
            <a href="{{ route('instructor.dashboard') }}" class="text-blue-600 hover:underline">Back to Dashboard</a>
        </div>
    </main>
</body>
</html>
