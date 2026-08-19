<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrolled Students - {{ $course->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
    <main class="max-w-3xl mx-auto px-6 py-12">
        <h1 class="text-2xl font-bold mb-6">Enrolled Students for {{ $course->title }}</h1>
        <table class="w-full bg-white rounded shadow mb-6">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Progress (%)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $student)
                    <tr>
                        <td class="px-4 py-2">{{ $student->name }}</td>
                        <td class="px-4 py-2">{{ $student->email }}</td>
                        <td class="px-4 py-2">{{ $student->pivot->progress_percentage ?? 0 }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-gray-600">No students enrolled yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <a href="{{ route('instructor.manage.courses') }}" class="text-blue-600 hover:underline">Back to Courses</a>
    </main>
</body>
</html>
