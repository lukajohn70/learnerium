<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learnerium - Edit Course</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
    <main class="max-w-4xl mx-auto px-6 py-12">
        <h1 class="text-3xl font-bold mb-6">Edit Course</h1>

        <form action="{{ route('instructor.courses.update', $course) }}" method="POST" class="space-y-6 bg-white p-6 rounded shadow">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-1" for="title">Title</label>
                <input id="title" name="title" type="text" value="{{ old('title', $course->title) }}" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="description">Description</label>
                <textarea id="description" name="description" rows="5" class="w-full border rounded px-3 py-2" required>{{ old('description', $course->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-1" for="thumbnail">Thumbnail URL</label>
                    <input id="thumbnail" name="thumbnail" type="url" value="{{ old('thumbnail', $course->thumbnail) }}" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" for="price">Price (₦)</label>
                    <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price', $course->price) }}" class="w-full border rounded px-3 py-2">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-1" for="level">Level</label>
                    <select id="level" name="level" class="w-full border rounded px-3 py-2" required>
                        @foreach (['Beginner', 'Intermediate', 'Advanced'] as $levelOption)
                            <option value="{{ $levelOption }}" {{ old('level', $course->level) === $levelOption ? 'selected' : '' }}>{{ $levelOption }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" for="duration_minutes">Duration (minutes)</label>
                    <input id="duration_minutes" name="duration_minutes" type="number" min="1" value="{{ old('duration_minutes', $course->duration_minutes) }}" class="w-full border rounded px-3 py-2" required>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save Changes</button>
                <a href="{{ route('instructor.manage.courses') }}" class="text-blue-600 hover:underline">Cancel</a>
            </div>
        </form>

        <!-- Lessons Section -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-6">Course Lessons</h2>

            <!-- Add Lesson Form -->
            <div class="bg-white p-6 rounded shadow mb-8">
                <h3 class="text-lg font-semibold mb-4">Add New Lesson</h3>
                <form action="{{ route('instructor.lessons.store', $course) }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1" for="lesson_title">Lesson Title</label>
                            <input id="lesson_title" name="title" type="text" placeholder="e.g., Introduction to Course" class="w-full border rounded px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" for="lesson_order">Order</label>
                            <input id="lesson_order" name="order" type="number" min="0" placeholder="0" class="w-full border rounded px-3 py-2" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1" for="lesson_description">Description</label>
                        <textarea id="lesson_description" name="description" rows="3" placeholder="Brief lesson description..." class="w-full border rounded px-3 py-2"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1" for="lesson_video_url">Video URL</label>
                        <input id="lesson_video_url" name="video_url" type="url" placeholder="https://youtube.com/watch?v=..." class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1" for="lesson_content">Content (HTML)</label>
                        <textarea id="lesson_content" name="content" rows="4" placeholder="Lesson content in HTML or markdown..." class="w-full border rounded px-3 py-2"></textarea>
                    </div>

                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Add Lesson</button>
                </form>
            </div>

            <!-- Lessons List -->
            @if ($course->lessons->count() > 0)
                <div class="bg-white rounded shadow overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-left px-6 py-3 text-sm font-semibold">Order</th>
                                <th class="text-left px-6 py-3 text-sm font-semibold">Title</th>
                                <th class="text-left px-6 py-3 text-sm font-semibold">Description</th>
                                <th class="text-left px-6 py-3 text-sm font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($course->lessons as $lesson)
                                <tr class="border-t">
                                    <td class="px-6 py-3">{{ $lesson->order }}</td>
                                    <td class="px-6 py-3 font-medium">{{ $lesson->title }}</td>
                                    <td class="px-6 py-3 text-sm text-gray-600">{{ Str::limit($lesson->description, 50) }}</td>
                                    <td class="px-6 py-3 flex items-center gap-3">
                                        <button onclick="editLesson({{ $lesson->id }})" class="text-blue-600 hover:underline text-sm">Edit</button>
                                        <form action="{{ route('instructor.lessons.destroy', [$course, $lesson]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline text-sm" onclick="return confirm('Delete this lesson?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 italic">No lessons yet. Create your first lesson above!</p>
            @endif
        </div>
    </main>
</body>
</html>

<script>
function editLesson(lessonId) {
    // TODO: Implement lesson edit modal or redirect
    alert('Lesson edit feature coming soon!');
}
</script>
