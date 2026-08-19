@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="mb-8">
        <a href="{{ route('instructor.manage.courses') }}" class="text-primary-jlm hover:text-secondary-jlm font-semibold transition text-sm">
            <i class="fas fa-arrow-left mr-2"></i>Back to Manage Courses
        </a>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">Create New Course</h1>
            <p class="text-gray-500 mt-1">Set up your course details and then add lessons.</p>
        </div>

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl px-5 py-4">
                <ul class="text-red-700 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="flex items-center gap-2"><i class="fas fa-exclamation-circle"></i>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-md overflow-hidden border-t-4 border-primary-jlm">
            <div class="bg-gray-50 border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-info-circle mr-2 text-primary-jlm"></i>Course Details</h2>
            </div>
            <form action="{{ route('instructor.courses.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div>
                    <label for="course-title" class="block text-sm font-semibold text-gray-700 mb-1.5">Course Title <span class="text-red-500">*</span></label>
                    <input type="text" id="course-title" name="title" value="{{ old('title') }}"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-jlm/30 focus:border-primary-jlm transition text-gray-800"
                           placeholder="e.g., Introduction to Web Development" required>
                </div>

                <div>
                    <label for="course-description" class="block text-sm font-semibold text-gray-700 mb-1.5">Course Description <span class="text-red-500">*</span></label>
                    <textarea id="course-description" name="description" rows="5"
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-jlm/30 focus:border-primary-jlm transition text-gray-800 resize-none"
                              placeholder="Provide a detailed description of your course..." required>{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="thumbnail" class="block text-sm font-semibold text-gray-700 mb-1.5">Thumbnail URL</label>
                        <input type="url" id="thumbnail" name="thumbnail" value="{{ old('thumbnail') }}"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-jlm/30 focus:border-primary-jlm transition text-gray-800"
                               placeholder="https://example.com/image.jpg">
                    </div>
                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-700 mb-1.5">Price (₦)</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" value="{{ old('price') }}"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-jlm/30 focus:border-primary-jlm transition text-gray-800"
                               placeholder="0.00 for free">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="level" class="block text-sm font-semibold text-gray-700 mb-1.5">Level <span class="text-red-500">*</span></label>
                        <select id="level" name="level"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-jlm/30 focus:border-primary-jlm transition text-gray-800 bg-white" required>
                            <option value="">Select Level</option>
                            @foreach(['Beginner', 'Intermediate', 'Advanced'] as $level)
                                <option value="{{ $level }}" {{ old('level') === $level ? 'selected' : '' }}>{{ $level }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="duration_minutes" class="block text-sm font-semibold text-gray-700 mb-1.5">Duration (minutes) <span class="text-red-500">*</span></label>
                        <input type="number" id="duration_minutes" name="duration_minutes" min="1" value="{{ old('duration_minutes') }}"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-jlm/30 focus:border-primary-jlm transition text-gray-800"
                               placeholder="e.g., 180" required>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 flex gap-3">
                    <button type="submit" class="bg-primary-jlm text-white px-8 py-3 rounded-xl font-bold hover:bg-primary-jlm-dark transition shadow-md">
                        <i class="fas fa-plus mr-2"></i>Create Course
                    </button>
                    <a href="{{ route('instructor.manage.courses') }}" class="border border-gray-200 text-gray-600 px-6 py-3 rounded-xl font-semibold hover:bg-gray-50 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
