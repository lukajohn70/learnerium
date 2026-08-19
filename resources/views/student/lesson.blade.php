<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $lesson->title }} - {{ $course->title }} - Learnerium</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
    <nav class="bg-white shadow">
        <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
            <div>
                <a href="{{ route('courses') }}" class="text-blue-600 hover:underline text-sm">← Back to Courses</a>
                <h1 class="text-2xl font-bold">{{ $course->title }}</h1>
            </div>
            <div class="text-sm text-gray-600">
                <span>Progress: {{ $progress ? $progress->progress_percentage : 0 }}%</span>
            </div>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-6 py-8 grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Main Lesson Content -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded shadow-lg p-8">
                <!-- Video Player (if video URL exists) -->
                @if ($lesson->video_url)
                    <div class="mb-8">
                        <div class="bg-black rounded aspect-video flex items-center justify-center mb-4">
                            @if (strpos($lesson->video_url, 'youtube.com') !== false || strpos($lesson->video_url, 'youtu.be') !== false)
                                <iframe width="100%" height="600" src="{{ $lesson->video_url }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            @else
                                <video width="100%" height="600" controls style="max-height: 600px;">
                                    <source src="{{ $lesson->video_url }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Lesson Header -->
                <div class="mb-6">
                    <h2 class="text-3xl font-bold mb-2">{{ $lesson->title }}</h2>
                    @if ($lesson->description)
                        <p class="text-gray-600">{{ $lesson->description }}</p>
                    @endif
                </div>

                <!-- Lesson Content -->
                @if ($lesson->content)
                    <div class="prose max-w-none mb-8 text-gray-700 leading-relaxed">
                        {!! $lesson->content !!}
                    </div>
                @endif

                <!-- Completion Button -->
                @if (auth()->user()->id !== $course->instructor_id)
                    <div class="border-t pt-6">
                        @if ($progress && $progress->completed)
                            <div class="bg-green-50 border border-green-200 rounded p-4 text-green-800">
                                ✓ You completed this lesson on {{ $progress->completed_at->format('M d, Y') }}
                            </div>
                        @else
                            <form action="{{ route('lesson.complete', [$course, $lesson]) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700">
                                    Mark as Complete
                                </button>
                            </form>
                        @endif
                    </div>
                @else
                    <div class="bg-blue-50 border border-blue-200 rounded p-4 text-blue-800">
                        You are viewing this as the course instructor.
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar: Lesson Navigation -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded shadow-lg p-6 sticky top-6">
                <h3 class="text-lg font-bold mb-4">Lessons</h3>

                <div class="space-y-2 max-h-96 overflow-y-auto">
                    @forelse ($lessons as $item)
                        <a
                            href="{{ route('lesson.show', [$course, $item]) }}"
                            class="block p-3 rounded transition {{ $item->id === $lesson->id ? 'bg-blue-600 text-white' : 'bg-gray-100 hover:bg-gray-200' }}"
                        >
                            <div class="text-sm font-medium">Lesson {{ $item->order + 1 }}</div>
                            <div class="text-xs {{ $item->id === $lesson->id ? 'text-blue-100' : 'text-gray-600' }}">
                                {{ Str::limit($item->title, 25) }}
                            </div>
                            @if ($item->id === $lesson->id && $progress && $progress->completed)
                                <div class="text-xs mt-1">✓ Completed</div>
                            @endif
                        </a>
                    @empty
                        <p class="text-gray-500 text-sm italic">No lessons yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</body>
</html>
