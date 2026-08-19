@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="mb-8">
        <a href="{{ route('instructor.manage.courses') }}" class="text-primary-jlm hover:text-secondary-jlm font-semibold transition text-sm">
            <i class="fas fa-arrow-left mr-2"></i>Back to Manage Courses
        </a>
    </div>

    @if(session('status'))
        <div class="mb-6 bg-emerald-50 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-2 text-sm font-medium">
            <i class="fas fa-check-circle text-emerald-500"></i>{{ session('status') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl px-5 py-4">
            <ul class="text-red-700 text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li class="flex items-center gap-2"><i class="fas fa-exclamation-circle"></i>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Course Details -->
        <div class="bg-white rounded-2xl shadow-md overflow-hidden border-t-4 border-secondary-jlm">
            <div class="bg-gray-50 border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-edit mr-2 text-secondary-jlm"></i>Edit Course: {{ $course->title }}</h2>
            </div>
            <form action="{{ route('instructor.courses.update', $course) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-1.5">Course Title <span class="text-red-500">*</span></label>
                    <input id="title" name="title" type="text" value="{{ old('title', $course->title) }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary-jlm/30 focus:border-secondary-jlm transition text-gray-800">
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">Description <span class="text-red-500">*</span></label>
                    <textarea id="description" name="description" rows="5" required
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary-jlm/30 focus:border-secondary-jlm transition text-gray-800 resize-none">{{ old('description', $course->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="thumbnail" class="block text-sm font-semibold text-gray-700 mb-1.5">Thumbnail URL</label>
                        <input id="thumbnail" name="thumbnail" type="url" value="{{ old('thumbnail', $course->thumbnail) }}"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary-jlm/30 focus:border-secondary-jlm transition text-gray-800"
                               placeholder="https://example.com/image.jpg">
                    </div>
                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-700 mb-1.5">Price (₦)</label>
                        <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price', $course->price) }}"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary-jlm/30 focus:border-secondary-jlm transition text-gray-800">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="level" class="block text-sm font-semibold text-gray-700 mb-1.5">Level <span class="text-red-500">*</span></label>
                        <select id="level" name="level" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary-jlm/30 focus:border-secondary-jlm transition text-gray-800 bg-white">
                            @foreach(['Beginner', 'Intermediate', 'Advanced'] as $levelOption)
                                <option value="{{ $levelOption }}" {{ old('level', $course->level) === $levelOption ? 'selected' : '' }}>{{ $levelOption }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="duration_minutes" class="block text-sm font-semibold text-gray-700 mb-1.5">Duration (minutes) <span class="text-red-500">*</span></label>
                        <input id="duration_minutes" name="duration_minutes" type="number" min="1" value="{{ old('duration_minutes', $course->duration_minutes) }}" required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary-jlm/30 focus:border-secondary-jlm transition text-gray-800">
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 flex gap-3">
                    <button type="submit" class="bg-secondary-jlm text-white px-8 py-3 rounded-xl font-bold hover:bg-secondary-jlm/90 transition shadow-md">
                        <i class="fas fa-save mr-2"></i>Save Changes
                    </button>
                    <a href="{{ route('instructor.manage.courses') }}" class="border border-gray-200 text-gray-600 px-6 py-3 rounded-xl font-semibold hover:bg-gray-50 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Add New Lesson -->
        <div class="bg-white rounded-2xl shadow-md overflow-hidden border-t-4 border-green-500">
            <div class="bg-gray-50 border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-plus-circle mr-2 text-green-500"></i>Add New Lesson</h2>
            </div>
            <form action="{{ route('instructor.lessons.store', $course) }}" method="POST" class="p-6 space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="lesson_title" class="block text-sm font-semibold text-gray-700 mb-1.5">Lesson Title <span class="text-red-500">*</span></label>
                        <input id="lesson_title" name="title" type="text" required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-400/30 focus:border-green-500 transition text-gray-800"
                               placeholder="e.g., Introduction to the Course">
                    </div>
                    <div>
                        <label for="lesson_order" class="block text-sm font-semibold text-gray-700 mb-1.5">Order <span class="text-red-500">*</span></label>
                        <input id="lesson_order" name="order" type="number" min="0" required
                               value="{{ $course->lessons->count() }}"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-400/30 focus:border-green-500 transition text-gray-800">
                    </div>
                </div>

                <div>
                    <label for="lesson_description" class="block text-sm font-semibold text-gray-700 mb-1.5">Description</label>
                    <textarea id="lesson_description" name="description" rows="2"
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-400/30 focus:border-green-500 transition text-gray-800 resize-none"
                              placeholder="Brief lesson description..."></textarea>
                </div>

                <div>
                    <label for="lesson_video_url" class="block text-sm font-semibold text-gray-700 mb-1.5">Video URL</label>
                    <input id="lesson_video_url" name="video_url" type="url"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-400/30 focus:border-green-500 transition text-gray-800"
                           placeholder="https://youtube.com/watch?v=...">
                </div>

                <div>
                    <label for="lesson_content" class="block text-sm font-semibold text-gray-700 mb-1.5">Content (HTML)</label>
                    <textarea id="lesson_content" name="content" rows="4"
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-400/30 focus:border-green-500 transition text-gray-800 resize-none"
                              placeholder="Lesson content in HTML or plain text..."></textarea>
                </div>

                <button type="submit" class="bg-green-500 text-white px-7 py-3 rounded-xl font-bold hover:bg-green-600 transition shadow-md">
                    <i class="fas fa-plus mr-2"></i>Add Lesson
                </button>
            </form>
        </div>

        <!-- Existing Lessons -->
        @if($course->lessons->count() > 0)
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-list-ul mr-2 text-primary-jlm"></i>Course Lessons ({{ $course->lessons->count() }})</h2>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($course->lessons->sortBy('order') as $lesson)
                <div class="px-6 py-4 flex items-center gap-4 hover:bg-gray-50 transition">
                    <span class="w-8 h-8 rounded-full bg-primary-jlm/10 text-primary-jlm font-bold text-sm flex items-center justify-center flex-shrink-0">
                        {{ $lesson->order + 1 }}
                    </span>
                    <div class="flex-grow min-w-0">
                        <p class="font-semibold text-gray-800 truncate">{{ $lesson->title }}</p>
                        @if($lesson->description)
                            <p class="text-xs text-gray-400 truncate">{{ Str::limit($lesson->description, 60) }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        @if($lesson->video_url)
                            <span class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded-full"><i class="fas fa-video mr-1"></i>Video</span>
                        @endif
                        <form action="{{ route('instructor.lessons.destroy', [$course, $lesson]) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete this lesson? This cannot be undone.')"
                                    class="text-red-400 hover:text-red-600 transition px-2 py-1 rounded-lg hover:bg-red-50 text-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
