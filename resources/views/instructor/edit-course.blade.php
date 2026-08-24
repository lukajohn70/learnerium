@extends('layouts.app')

@section('title', 'Edit Course — ' . $course->title)

@section('content')
<div class="container mx-auto px-4 py-10">
    <div class="max-w-5xl mx-auto space-y-6">

        <!-- Top Navigation & Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <a href="{{ route('instructor.manage.courses') }}" class="text-primary-jlm hover:text-secondary-jlm font-semibold transition text-xs flex items-center gap-1 mb-1">
                    <i class="fas fa-arrow-left"></i> Back to Manage Courses
                </a>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 leading-tight">
                    Edit Course: <span class="text-primary-jlm">{{ $course->title }}</span>
                </h1>
            </div>
            <div class="flex items-center gap-2">
                @if($course->status === 'published')
                    <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider">Published</span>
                @else
                    <form action="{{ route('instructor.courses.publish', $course) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow">
                            <i class="fas fa-rocket mr-1"></i> Publish Course
                        </button>
                    </form>
                @endif
                <a href="{{ route('course.detail', $course->slug) }}" target="_blank" class="border border-gray-300 text-gray-700 hover:bg-gray-50 text-xs font-bold px-3.5 py-2 rounded-xl transition">
                    <i class="fas fa-eye mr-1"></i> Preview
                </a>
                <form action="{{ route('instructor.courses.destroy', $course) }}" method="POST" class="inline" onsubmit="return confirm('⚠️ Are you sure you want to PERMANENTLY DELETE this course?\n\nThis action cannot be undone!');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 text-xs font-bold px-3.5 py-2 rounded-xl transition" title="Delete Entire Course">
                        <i class="fas fa-trash-alt mr-1"></i> Delete Course
                    </button>
                </form>
            </div>
        </div>

        @if(session('status'))
            <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 px-5 py-3.5 rounded-2xl flex items-center gap-2 text-sm font-medium shadow-xs">
                <i class="fas fa-check-circle text-emerald-500 text-base"></i>{{ session('status') }}
            </div>
        @endif

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 px-5 py-3.5 rounded-2xl flex items-center gap-2 text-sm font-medium shadow-xs">
                <i class="fas fa-check-circle text-emerald-500 text-base"></i>{{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-2xl px-5 py-4">
                <ul class="text-red-700 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="flex items-center gap-2"><i class="fas fa-exclamation-circle text-red-500"></i>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Tab Navigation Bar -->
        <div class="flex border-b border-gray-200 bg-white rounded-t-2xl px-4 pt-3 shadow-xs">
            <button onclick="switchTab('detailsTab', 'contentTab', this)" id="btnDetails" class="px-6 py-3 font-bold text-sm border-b-2 border-primary-jlm text-primary-jlm transition flex items-center gap-2 focus:outline-none">
                <i class="fas fa-edit text-base"></i>
                <span>1. Course Details</span>
            </button>
            <button onclick="switchTab('contentTab', 'detailsTab', this)" id="btnContent" class="px-6 py-3 font-bold text-sm border-b-2 border-transparent text-gray-500 hover:text-gray-900 transition flex items-center gap-2 focus:outline-none">
                <i class="fas fa-layer-group text-base"></i>
                <span>2. Course Modules & Lessons</span>
                <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full font-semibold">{{ $course->modules->count() }} Modules · {{ $course->lessons->count() }} Lessons</span>
            </button>
        </div>

        <!-- ================= TAB 1: COURSE DETAILS ================= -->
        <div id="detailsTab" class="bg-white rounded-b-2xl shadow-md p-6 sm:p-8 border border-gray-100 space-y-6">
            <form action="{{ route('instructor.courses.update', $course) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="title" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
                        Course Title <span class="text-secondary-jlm">*</span>
                    </label>
                    <input id="title" name="title" type="text" value="{{ old('title', $course->title) }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary-jlm text-sm font-semibold text-gray-900">
                </div>

                <div>
                    <label for="description" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
                        Course Overview / Description <span class="text-secondary-jlm">*</span>
                    </label>
                    <textarea id="description" name="description" rows="5" required
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary-jlm text-sm text-gray-800 leading-relaxed">{{ old('description', $course->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="thumbnail_file" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Upload Thumbnail Cover Image</label>
                        <input id="thumbnail_file" name="thumbnail_file" type="file" accept="image/*"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-primary-jlm text-sm file:mr-4 file:py-1.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary-jlm/10 file:text-primary-jlm hover:file:bg-primary-jlm/20">
                        @if($course->thumbnail)
                            <div class="mt-2 flex items-center gap-2">
                                <img src="{{ $course->thumbnailUrl() }}" alt="Thumbnail" class="w-12 h-12 object-cover rounded-lg border">
                                <span class="text-xs text-gray-400">Current Thumbnail</span>
                            </div>
                        @endif
                        <p class="text-xs text-gray-400 mt-1">Or paste direct URL below:</p>
                        <input id="thumbnail" name="thumbnail" type="text" value="{{ old('thumbnail', $course->thumbnail) }}"
                               class="w-full mt-1 px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-primary-jlm text-xs"
                               placeholder="e.g. uploads/thumbnails/... or https://...">
                    </div>
                    <div>
                        <label for="price" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Course Price (₦ Naira)</label>
                        <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price', $course->price) }}"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary-jlm text-sm font-bold">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label for="category" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Course Category <span class="text-secondary-jlm">*</span></label>
                        <select id="category" name="category" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary-jlm text-sm bg-white font-medium">
                            <option value="">Select Category</option>
                            @foreach(['Web Development & Programming', 'Data Science & AI', 'Business & Entrepreneurship', 'Graphic Design & UI/UX', 'Digital Marketing & SEO', 'Cyber Security & IT', 'Personal Development', 'Languages & Academics'] as $catOption)
                                <option value="{{ $catOption }}" {{ old('category', $course->category) === $catOption ? 'selected' : '' }}>{{ $catOption }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="level" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Difficulty Level <span class="text-secondary-jlm">*</span></label>
                        <select id="level" name="level" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary-jlm text-sm bg-white font-medium">
                            @foreach(['Beginner', 'Intermediate', 'Advanced', 'All Levels'] as $levelOption)
                                <option value="{{ $levelOption }}" {{ old('level', $course->level) === $levelOption ? 'selected' : '' }}>{{ $levelOption }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="duration_minutes" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Estimated Duration (Minutes) <span class="text-secondary-jlm">*</span></label>
                        <input id="duration_minutes" name="duration_minutes" type="number" min="1" value="{{ old('duration_minutes', $course->duration_minutes) }}" required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary-jlm text-sm">
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                    <button type="submit" class="bg-secondary-jlm hover:bg-secondary-jlm/90 text-white px-8 py-3.5 rounded-2xl font-bold text-sm transition shadow-md">
                        <i class="fas fa-save mr-2"></i>Save Course Details
                    </button>
                    <button type="button" onclick="switchTab('contentTab', 'detailsTab', document.getElementById('btnContent'))" class="text-primary-jlm font-bold text-sm hover:underline flex items-center gap-1">
                        Next: Manage Modules & Lessons <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>


        <!-- ================= TAB 2: COURSE CONTENT (MODULES & LESSONS) ================= -->
        <div id="contentTab" class="hidden space-y-6">

            <!-- Create Module Form -->
            <div class="bg-gradient-to-r from-primary-jlm via-indigo-900 to-primary-jlm text-white rounded-3xl p-6 sm:p-8 shadow-lg border border-indigo-800">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-accent-jlm text-lg font-bold">1</div>
                    <div>
                        <h2 class="text-xl font-extrabold text-white">Create Course Module</h2>
                        <p class="text-xs text-white/70">Step 1: Create ordered modules first. Students must complete all lessons in Module 1 before unlocking Module 2.</p>
                    </div>
                </div>
                <form action="{{ route('instructor.modules.store', $course) }}" method="POST" class="mt-4 flex flex-col sm:flex-row gap-3">
                    @csrf
                    <input type="text" name="title" required placeholder="Enter Module Title (e.g., Module 1: Introduction to Web Design)"
                           class="flex-1 px-5 py-3 rounded-2xl text-gray-900 text-sm font-semibold focus:outline-none shadow-inner">
                    <button type="submit" class="bg-accent-jlm hover:bg-yellow-300 text-primary-jlm px-7 py-3 rounded-2xl font-extrabold text-sm transition shadow-md flex items-center justify-center gap-2 flex-shrink-0">
                        <i class="fas fa-plus-circle"></i>Save Module
                    </button>
                </form>
            </div>

            <!-- Existing Modules List with Nested Lessons -->
            <div class="space-y-6">
                @if($course->modules->count() > 0)
                    @foreach($course->modules as $modIndex => $mod)
                        <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">

                            <!-- Module Header Card -->
                            <div class="bg-gray-50/80 px-6 py-5 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <div>
                                    <span class="text-xs font-extrabold uppercase tracking-wider text-secondary-jlm bg-pink-50 border border-pink-200 px-3 py-1 rounded-full">
                                        Module {{ $modIndex + 1 }}
                                    </span>
                                    <h3 class="text-lg font-extrabold text-gray-900 mt-1.5">{{ $mod->title }}</h3>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $mod->lessons->count() }} {{ Str::plural('lesson', $mod->lessons->count()) }} inside this module</p>
                                </div>
                                <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                                    <button onclick="toggleModal('addMaterialModal-{{ $mod->id }}')" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold px-3.5 py-2 rounded-xl transition shadow-xs flex items-center gap-1.5" title="Add Material (PDF, DOCX, Link)">
                                        <i class="fas fa-paperclip"></i> Add Material
                                    </button>
                                    <button onclick="toggleModal('addLessonModal-{{ $mod->id }}')" class="bg-primary-jlm hover:bg-primary-jlm-dark text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-xs flex items-center gap-1.5">
                                        <i class="fas fa-plus"></i> Add Lesson to Module
                                    </button>
                                    <form action="{{ route('instructor.modules.destroy', [$course, $mod]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Delete this module and all its lessons?')" class="text-red-500 hover:text-red-700 hover:bg-red-50 text-xs font-bold px-3 py-2 rounded-xl transition">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Attached Module Materials -->
                            @if($mod->materials->count() > 0)
                                <div class="bg-amber-50/50 border-b border-amber-100 px-6 py-3">
                                    <span class="text-[11px] font-bold text-amber-900 uppercase tracking-wider block mb-1.5"><i class="fas fa-paperclip mr-1"></i> Module Materials / Attachments:</span>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($mod->materials as $mat)
                                            <div class="inline-flex items-center gap-2 bg-white border border-amber-200 px-3 py-1.5 rounded-xl text-xs font-medium text-amber-900 shadow-2xs">
                                                @if($mat->type === 'document')
                                                    <i class="fas fa-file-alt text-amber-600"></i>
                                                @else
                                                    <i class="fas fa-link text-blue-500"></i>
                                                @endif
                                                <a href="{{ $mat->url_or_path }}" target="_blank" class="hover:underline font-bold">{{ $mat->title }}</a>
                                                <form action="{{ route('instructor.modules.materials.destroy', [$course, $mod, $mat]) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Remove this material?')" class="text-gray-400 hover:text-red-600 ml-1">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Lessons Inside Module -->
                            <div class="divide-y divide-gray-100 bg-white">
                                @forelse($mod->lessons as $lessonIndex => $lesson)
                                    <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-gray-50/60 transition">
                                        <div class="flex items-center gap-3.5 min-w-0">
                                            <span class="w-8 h-8 rounded-full bg-primary-jlm/10 text-primary-jlm font-bold text-xs flex items-center justify-center flex-shrink-0">
                                                {{ $lessonIndex + 1 }}
                                            </span>
                                            <div class="min-w-0">
                                                <h4 class="font-bold text-gray-900 text-sm truncate">{{ $lesson->title }}</h4>
                                                @if($lesson->description)
                                                    <p class="text-xs text-gray-400 truncate max-w-md">{{ $lesson->description }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2 flex-wrap flex-shrink-0">
                                            @if($lesson->video_url)
                                                <span class="text-xs bg-blue-50 text-blue-600 px-2.5 py-1 rounded-lg font-semibold"><i class="fas fa-play-circle mr-1"></i>Video</span>
                                            @endif

                                            <!-- Edit Lesson Button -->
                                            <button onclick="toggleModal('editLessonModal-{{ $lesson->id }}')" class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-xs" title="Edit Lesson Details & Video Link">
                                                <i class="fas fa-edit"></i> Edit Lesson
                                            </button>

                                            <!-- Task Gates -->
                                            <a href="{{ route('lessons.tasks.index', $lesson->id) }}" class="inline-flex items-center gap-1.5 bg-orange-500 hover:bg-orange-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-xs">
                                                <i class="fas fa-tasks"></i> Task Gates ({{ $lesson->tasks->count() }})
                                            </a>

                                            <!-- Quizzes -->
                                            <a href="{{ route('lessons.quizzes.index', $lesson->id) }}" class="inline-flex items-center gap-1.5 bg-purple-600 hover:bg-purple-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-xs">
                                                <i class="fas fa-question-circle"></i> Quizzes ({{ $lesson->quizzes->count() }})
                                            </a>

                                            <!-- Delete Lesson -->
                                            <form action="{{ route('instructor.lessons.destroy', [$course, $lesson]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Delete this lesson?')" class="inline-flex items-center gap-1 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 px-2.5 py-1.5 rounded-lg text-xs font-bold transition shadow-xs" title="Delete Lesson">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Edit Lesson Modal for {{ $lesson->title }} -->
                                    <div id="editLessonModal-{{ $lesson->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm p-4 sm:p-6 flex items-center justify-center">
                                        <div class="bg-white rounded-3xl max-w-xl w-full max-h-[90vh] overflow-y-auto p-6 sm:p-8 shadow-2xl border border-gray-100 space-y-5 text-left my-auto">
                                            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                                                <div>
                                                    <span class="text-xs font-bold text-blue-600 uppercase">Editing Lesson</span>
                                                    <h3 class="text-lg font-extrabold text-gray-900">{{ $lesson->title }}</h3>
                                                </div>
                                                <button onclick="toggleModal('editLessonModal-{{ $lesson->id }}')" class="text-gray-400 hover:text-gray-600">
                                                    <i class="fas fa-times text-lg"></i>
                                                </button>
                                            </div>
                                            <form action="{{ route('instructor.lessons.update', [$course, $lesson]) }}" method="POST" class="space-y-4">
                                                @csrf
                                                @method('PUT')

                                                <div>
                                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Lesson Title <span class="text-secondary-jlm">*</span></label>
                                                    <input type="text" name="title" value="{{ old('title', $lesson->title) }}" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-semibold focus:outline-none focus:border-blue-600">
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Video / Media URL (YouTube, Vimeo, MP4, Google Drive)</label>
                                                    <input type="url" name="video_url" value="{{ old('video_url', $lesson->video_url) }}" placeholder="https://youtube.com/watch?v=..." class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-600">
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Module Section</label>
                                                    <select name="module_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm bg-white font-medium focus:outline-none focus:border-blue-600">
                                                        <option value="">No Module (Standalone Lesson)</option>
                                                        @foreach($course->modules as $mOption)
                                                            <option value="{{ $mOption->id }}" {{ $lesson->module_id == $mOption->id ? 'selected' : '' }}>{{ $mOption->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Description (Optional)</label>
                                                    <textarea name="description" rows="2" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-600">{{ old('description', $lesson->description) }}</textarea>
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Lesson Text / HTML Content (Optional)</label>
                                                    <textarea name="content" rows="4" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-600">{{ old('content', $lesson->content) }}</textarea>
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Display Order</label>
                                                    <input type="number" name="order" value="{{ old('order', $lesson->order) }}" min="0" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-600">
                                                </div>

                                                <!-- Drip Schedule Options -->
                                                <div class="bg-indigo-50/70 border border-indigo-100 rounded-2xl p-4 space-y-3">
                                                    <div class="flex items-center gap-2">
                                                        <i class="fas fa-hourglass-half text-indigo-600 text-sm"></i>
                                                        <span class="text-xs font-extrabold text-indigo-900 uppercase tracking-wider">Drip Release Schedule (Optional)</span>
                                                    </div>
                                                    <p class="text-[11px] text-indigo-700/80 leading-relaxed">Leave blank to make available immediately. Or choose when students can access this lesson:</p>
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                        <div>
                                                            <label class="block text-[10px] font-bold uppercase tracking-wider text-indigo-900 mb-1">Release on Specific Date/Time</label>
                                                            <input type="datetime-local" name="drip_date" value="{{ $lesson->drip_date ? $lesson->drip_date->format('Y-m-d\TH:i') : '' }}"
                                                                   class="w-full px-3 py-2 border border-indigo-200 bg-white rounded-xl text-xs focus:outline-none focus:border-indigo-600">
                                                        </div>
                                                        <div>
                                                            <label class="block text-[10px] font-bold uppercase tracking-wider text-indigo-900 mb-1">Days After Enrollment</label>
                                                            <input type="number" name="drip_days" min="0" value="{{ $lesson->drip_days }}" placeholder="e.g. 7 (days)"
                                                                   class="w-full px-3 py-2 border border-indigo-200 bg-white rounded-xl text-xs focus:outline-none focus:border-indigo-600">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="pt-3 border-t border-gray-100 flex justify-end gap-2">
                                                    <button type="button" onclick="toggleModal('editLessonModal-{{ $lesson->id }}')" class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-xs font-bold">Cancel</button>
                                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-xs font-bold shadow-md">
                                                        <i class="fas fa-save mr-1"></i>Update Lesson
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-8 text-center bg-gray-50/50">
                                        <p class="text-xs text-gray-400 font-semibold mb-3">No lessons in {{ $mod->title }} yet.</p>
                                        <button onclick="toggleModal('addLessonModal-{{ $mod->id }}')" class="bg-primary-jlm text-white text-xs font-bold px-4 py-2 rounded-xl shadow-xs">
                                            <i class="fas fa-plus mr-1"></i> Add First Lesson to {{ $mod->title }}
                                        </button>
                                    </div>
                                @endforelse
                            </div>

                        </div>

                        <!-- Add Lesson Modal for this Module -->
                        <div id="addLessonModal-{{ $mod->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm p-4 sm:p-6 flex items-center justify-center">
                            <div class="bg-white rounded-3xl max-w-lg w-full max-h-[90vh] overflow-y-auto p-6 sm:p-8 shadow-2xl border border-gray-100 space-y-5 my-auto">
                                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                                    <div>
                                        <span class="text-xs font-bold text-secondary-jlm uppercase">Adding Lesson to</span>
                                        <h3 class="text-lg font-extrabold text-gray-900">{{ $mod->title }}</h3>
                                    </div>
                                    <button onclick="toggleModal('addLessonModal-{{ $mod->id }}')" class="text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-times text-lg"></i>
                                    </button>
                                </div>
                                <form action="{{ route('instructor.lessons.store', $course) }}" method="POST" class="space-y-4">
                                    @csrf
                                    <input type="hidden" name="module_id" value="{{ $mod->id }}">
                                    <input type="hidden" name="order" value="{{ $mod->lessons->count() + 1 }}">

                                    <div>
                                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Lesson Title <span class="text-secondary-jlm">*</span></label>
                                        <input type="text" name="title" required placeholder="e.g., Lesson 1: Getting Started with HTML" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-jlm">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Description (Optional)</label>
                                        <textarea name="description" rows="2" placeholder="Brief lesson description..." class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-jlm"></textarea>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Video / Media URL (Optional)</label>
                                        <input type="url" name="video_url" placeholder="https://youtube.com/watch?v=..." class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-jlm">
                                    </div>

                                    <!-- Drip Schedule Options -->
                                    <div class="bg-indigo-50/70 border border-indigo-100 rounded-2xl p-4 space-y-3">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-hourglass-half text-indigo-600 text-sm"></i>
                                            <span class="text-xs font-extrabold text-indigo-900 uppercase tracking-wider">Drip Release Schedule (Optional)</span>
                                        </div>
                                        <p class="text-[11px] text-indigo-700/80 leading-relaxed">Leave blank to make available immediately. Or set a drip lock:</p>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-[10px] font-bold uppercase tracking-wider text-indigo-900 mb-1">Release on Specific Date/Time</label>
                                                <input type="datetime-local" name="drip_date"
                                                       class="w-full px-3 py-2 border border-indigo-200 bg-white rounded-xl text-xs focus:outline-none focus:border-indigo-600">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold uppercase tracking-wider text-indigo-900 mb-1">Days After Enrollment</label>
                                                <input type="number" name="drip_days" min="0" placeholder="e.g. 7 (days)"
                                                       class="w-full px-3 py-2 border border-indigo-200 bg-white rounded-xl text-xs focus:outline-none focus:border-indigo-600">
                                            </div>
                                        </div>
                                    </div>


                                    <div class="pt-3 border-t border-gray-100 flex justify-end gap-2">
                                        <button type="button" onclick="toggleModal('addLessonModal-{{ $mod->id }}')" class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-xs font-bold">Cancel</button>
                                        <button type="submit" class="bg-primary-jlm hover:bg-primary-jlm-dark text-white px-6 py-2.5 rounded-xl text-xs font-bold shadow-md">
                                            <i class="fas fa-plus mr-1"></i>Save Lesson
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Add Material Modal for this Module -->
                        <div id="addMaterialModal-{{ $mod->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm p-4 sm:p-6 flex items-center justify-center">
                            <div class="bg-white rounded-3xl max-w-lg w-full max-h-[90vh] overflow-y-auto p-6 sm:p-8 shadow-2xl border border-gray-100 space-y-5 my-auto">
                                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                                    <div>
                                        <span class="text-xs font-bold text-amber-600 uppercase">Attach Material to</span>
                                        <h3 class="text-lg font-extrabold text-gray-900">{{ $mod->title }}</h3>
                                    </div>
                                    <button onclick="toggleModal('addMaterialModal-{{ $mod->id }}')" class="text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-times text-lg"></i>
                                    </button>
                                </div>
                                <form action="{{ route('instructor.modules.materials.store', [$course, $mod]) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Material Title <span class="text-secondary-jlm">*</span></label>
                                        <input type="text" name="title" required placeholder="e.g. Chapter 1 Lecture Notes PDF" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-jlm">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Material Type <span class="text-secondary-jlm">*</span></label>
                                        <select name="type" onchange="toggleMaterialInputs(this.value, '{{ $mod->id }}')" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm bg-white font-medium">
                                            <option value="document">📄 Upload Document (PDF, DOCX, PPT, ZIP)</option>
                                            <option value="link">🔗 External Link (Google Drive, Dropbox, Website)</option>
                                        </select>
                                    </div>

                                    <div id="fileInputWrap-{{ $mod->id }}">
                                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Select Document File</label>
                                        <input type="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.txt" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm">
                                    </div>

                                    <div id="urlInputWrap-{{ $mod->id }}" class="hidden">
                                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">External Resource Link URL</label>
                                        <input type="url" name="url" placeholder="https://drive.google.com/file/d/..." class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                                    </div>

                                    <div class="pt-3 border-t border-gray-100 flex justify-end gap-2">
                                        <button type="button" onclick="toggleModal('addMaterialModal-{{ $mod->id }}')" class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-xs font-bold">Cancel</button>
                                        <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-2.5 rounded-xl text-xs font-bold shadow-md">
                                            <i class="fas fa-paperclip mr-1"></i>Attach Material
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="bg-white rounded-3xl p-12 text-center border border-gray-200 shadow-sm">
                        <div class="w-16 h-16 bg-primary-jlm/10 text-primary-jlm rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <h3 class="text-lg font-extrabold text-gray-900 mb-1">No Modules Created Yet</h3>
                        <p class="text-gray-500 text-sm max-w-md mx-auto mb-6">
                            Start building your course structure by creating your first module in the box above!
                        </p>
                    </div>
                @endif
            </div>

        </div>

    </div>
</div>

<script>
function switchTab(showId, hideId, activeBtn) {
    document.getElementById(showId).classList.remove('hidden');
    document.getElementById(hideId).classList.add('hidden');

    document.getElementById('btnDetails').className = "px-6 py-3 font-bold text-sm border-b-2 border-transparent text-gray-500 hover:text-gray-900 transition flex items-center gap-2 focus:outline-none";
    document.getElementById('btnContent').className = "px-6 py-3 font-bold text-sm border-b-2 border-transparent text-gray-500 hover:text-gray-900 transition flex items-center gap-2 focus:outline-none";

    activeBtn.className = "px-6 py-3 font-bold text-sm border-b-2 border-primary-jlm text-primary-jlm transition flex items-center gap-2 focus:outline-none";
}

function toggleModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.toggle('hidden');
    }
}

function toggleMaterialInputs(type, modId) {
    const fileWrap = document.getElementById('fileInputWrap-' + modId);
    const urlWrap = document.getElementById('urlInputWrap-' + modId);
    if (fileWrap && urlWrap) {
        if (type === 'document') {
            fileWrap.classList.remove('hidden');
            urlWrap.classList.add('hidden');
        } else {
            fileWrap.classList.add('hidden');
            urlWrap.classList.remove('hidden');
        }
    }
}
</script>
@endsection
