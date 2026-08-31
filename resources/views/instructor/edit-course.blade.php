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
                    <i class="fas fa-eye mr-1"></i> Preview Course
                </a>
                <a href="{{ route('student.certificate.view', $course) }}" target="_blank" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold px-3.5 py-2 rounded-xl transition shadow flex items-center gap-1.5" title="Preview Accredited Certificate">
                    <i class="fas fa-certificate"></i> Preview Certificate
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

                {{-- ✨ AI Assistant Panel --}}
                <div class="bg-gradient-to-r from-indigo-50 via-purple-50 to-pink-50 border border-indigo-200 rounded-2xl p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center text-white text-sm font-bold shadow">
                                ✨
                            </div>
                            <div>
                                <h3 class="text-sm font-extrabold text-indigo-900">AI Course Assistant</h3>
                                <p class="text-xs text-indigo-600">Powered by Google Gemini — auto-fill course content</p>
                            </div>
                        </div>
                        <span id="aiStatus" class="text-xs text-indigo-500 font-semibold hidden">
                            <i class="fas fa-spinner fa-spin mr-1"></i> Generating...
                        </span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="aiGenerate('description')"
                                class="bg-white border border-indigo-200 hover:bg-indigo-50 text-indigo-800 text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm flex items-center gap-1.5">
                            <i class="fas fa-magic text-purple-500"></i> Generate Description
                        </button>
                        <button type="button" onclick="aiGenerate('outcomes')"
                                class="bg-white border border-indigo-200 hover:bg-indigo-50 text-indigo-800 text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm flex items-center gap-1.5">
                            <i class="fas fa-list-check text-emerald-500"></i> Generate Learning Outcomes
                        </button>
                        <button type="button" onclick="aiGenerate('requirements')"
                                class="bg-white border border-indigo-200 hover:bg-indigo-50 text-indigo-800 text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm flex items-center gap-1.5">
                            <i class="fas fa-clipboard-list text-amber-500"></i> Generate Requirements
                        </button>
                        <button type="button" onclick="showOutlineModal()"
                                class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:opacity-90 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm flex items-center gap-1.5">
                            <i class="fas fa-layer-group"></i> Generate Course Outline
                        </button>
                    </div>
                </div>

                {{-- What You'll Learn --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                            <i class="fas fa-check-circle text-emerald-500 mr-1"></i> What Students Will Learn
                        </label>
                        <button type="button" onclick="addBullet('outcomes-list', 'what_you_will_learn')"
                                class="text-xs font-bold text-primary-jlm hover:underline flex items-center gap-1">
                            <i class="fas fa-plus"></i> Add Item
                        </button>
                    </div>
                    <div id="outcomes-list" class="space-y-2">
                        @php $outcomes = old('what_you_will_learn', $course->what_you_will_learn ?? []); @endphp
                        @if(empty($outcomes))
                            <div class="outcome-item flex items-center gap-2">
                                <i class="fas fa-grip-vertical text-gray-300 cursor-grab text-xs"></i>
                                <input type="text" name="what_you_will_learn[]" placeholder="e.g. Build full-stack web applications from scratch"
                                       class="flex-1 px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-jlm">
                                <button type="button" onclick="removeBullet(this)" class="text-red-400 hover:text-red-600 text-xs"><i class="fas fa-times"></i></button>
                            </div>
                        @else
                            @foreach($outcomes as $outcome)
                            <div class="outcome-item flex items-center gap-2">
                                <i class="fas fa-grip-vertical text-gray-300 cursor-grab text-xs"></i>
                                <input type="text" name="what_you_will_learn[]" value="{{ $outcome }}" placeholder="e.g. Build full-stack web applications from scratch"
                                       class="flex-1 px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-jlm">
                                <button type="button" onclick="removeBullet(this)" class="text-red-400 hover:text-red-600 text-xs"><i class="fas fa-times"></i></button>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- Requirements / Prerequisites --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                            <i class="fas fa-clipboard-list text-amber-500 mr-1"></i> Requirements / Prerequisites
                        </label>
                        <button type="button" onclick="addBullet('requirements-list', 'requirements')"
                                class="text-xs font-bold text-primary-jlm hover:underline flex items-center gap-1">
                            <i class="fas fa-plus"></i> Add Item
                        </button>
                    </div>
                    <div id="requirements-list" class="space-y-2">
                        @php $reqs = old('requirements', $course->requirements ?? []); @endphp
                        @if(empty($reqs))
                            <div class="req-item flex items-center gap-2">
                                <i class="fas fa-grip-vertical text-gray-300 cursor-grab text-xs"></i>
                                <input type="text" name="requirements[]" placeholder="e.g. Basic knowledge of HTML and CSS"
                                       class="flex-1 px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-jlm">
                                <button type="button" onclick="removeBullet(this)" class="text-red-400 hover:text-red-600 text-xs"><i class="fas fa-times"></i></button>
                            </div>
                        @else
                            @foreach($reqs as $req)
                            <div class="req-item flex items-center gap-2">
                                <i class="fas fa-grip-vertical text-gray-300 cursor-grab text-xs"></i>
                                <input type="text" name="requirements[]" value="{{ $req }}" placeholder="e.g. Basic knowledge of HTML and CSS"
                                       class="flex-1 px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-jlm">
                                <button type="button" onclick="removeBullet(this)" class="text-red-400 hover:text-red-600 text-xs"><i class="fas fa-times"></i></button>
                            </div>
                            @endforeach
                        @endif
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
                                            <form action="{{ route('instructor.lessons.update', [$course, $lesson]) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                                @csrf
                                                @method('PUT')

                                                <div>
                                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Lesson Title <span class="text-secondary-jlm">*</span></label>
                                                    <input type="text" name="title" value="{{ old('title', $lesson->title) }}" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-semibold focus:outline-none focus:border-blue-600">
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Video Source</label>
                                                    {{-- Source Tab Toggle: 3 Options --}}
                                                    <div class="grid grid-cols-3 rounded-xl border border-gray-200 overflow-hidden mb-3 text-xs font-bold" id="vsToggle-{{ $lesson->id }}">
                                                        <button type="button" onclick="switchVideoSource('{{ $lesson->id }}', 'url')"
                                                            class="vs-tab-url-{{ $lesson->id }} px-2 py-2.5 bg-blue-600 text-white transition-colors text-center truncate"
                                                            id="vsTabUrl-{{ $lesson->id }}">
                                                            🔗 Web Link
                                                        </button>
                                                        <button type="button" onclick="switchVideoSource('{{ $lesson->id }}', 'gdrive')"
                                                            class="vs-tab-gdrive-{{ $lesson->id }} px-2 py-2.5 bg-gray-50 text-gray-600 hover:bg-gray-100 transition-colors text-center truncate"
                                                            id="vsTabGdrive-{{ $lesson->id }}">
                                                            ☁️ Drive to Server
                                                        </button>
                                                        <button type="button" onclick="switchVideoSource('{{ $lesson->id }}', 'upload')"
                                                            class="vs-tab-up-{{ $lesson->id }} px-2 py-2.5 bg-gray-50 text-gray-600 hover:bg-gray-100 transition-colors text-center truncate"
                                                            id="vsTabUp-{{ $lesson->id }}">
                                                            📁 Upload File
                                                        </button>
                                                    </div>
                                                    {{-- Option 1: Standard URL (YouTube/Vimeo/Embed) --}}
                                                    <div id="vsUrlWrap-{{ $lesson->id }}">
                                                        <input type="url" name="video_url"
                                                            value="{{ old('video_url', (!$lesson->video_url || str_starts_with($lesson->video_url, 'http')) ? $lesson->video_url : '') }}"
                                                            placeholder="https://youtube.com/watch?v=... or Vimeo link"
                                                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-600">
                                                        <p class="text-[11px] text-gray-400 mt-1">Streams directly from YouTube, Vimeo, or external media server.</p>
                                                    </div>
                                                    {{-- Option 2: Google Drive Direct Import to Server --}}
                                                    <div id="vsGdriveWrap-{{ $lesson->id }}" class="hidden space-y-2.5">
                                                        <input type="hidden" name="imported_video_path" id="gdriveImportedPath-{{ $lesson->id }}" value="">
                                                        <div>
                                                            <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 mb-1">Google Drive Share Link</label>
                                                            <input type="url" id="gdriveInput-{{ $lesson->id }}" name="gdrive_import_url"
                                                                placeholder="https://drive.google.com/file/d/.../view?usp=sharing"
                                                                class="w-full px-4 py-3 border border-indigo-200 bg-indigo-50/30 rounded-xl text-sm focus:outline-none focus:border-indigo-600">
                                                        </div>
                                                        <div class="flex flex-wrap items-center gap-2 pt-0.5">
                                                            <button type="button" onclick="fetchGDriveVideo('{{ $lesson->id }}')" id="gdriveFetchBtn-{{ $lesson->id }}"
                                                                class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm">
                                                                <i class="fas fa-cloud-arrow-down"></i> <span>Download & Save to Server</span>
                                                            </button>
                                                            <span class="text-[11px] text-gray-400">Click to fetch the video into server storage first.</span>
                                                        </div>
                                                        <div id="gdriveStatus-{{ $lesson->id }}" class="hidden text-xs rounded-xl p-3 border"></div>
                                                    </div>
                                                    {{-- Option 3: File Upload Input --}}
                                                    <div id="vsUploadWrap-{{ $lesson->id }}" class="hidden">
                                                        @if($lesson->video_url && !str_starts_with($lesson->video_url, 'http'))
                                                            <p class="text-[11px] text-green-700 bg-green-50 border border-green-200 rounded-xl px-3 py-2 mb-2 font-semibold">
                                                                ✅ Server File: <span class="font-mono">{{ basename($lesson->video_url) }}</span>
                                                            </p>
                                                        @endif
                                                        <input type="file" name="video_file"
                                                            accept="video/mp4,video/webm,video/mov,video/quicktime,video/x-msvideo,.mp4,.webm,.mov,.avi"
                                                            class="w-full px-4 py-2.5 border border-dashed border-blue-300 bg-blue-50/50 rounded-xl text-sm focus:outline-none focus:border-blue-600
                                                                   file:mr-3 file:py-1.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                                                        <p class="text-[11px] text-gray-400 mt-1.5">Accepted: MP4, WebM, MOV, AVI &mdash; max 500 MB. Stored securely on the server.</p>
                                                    </div>
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
                                <form action="{{ route('instructor.lessons.store', $course) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
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
                                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Video Source</label>
                                        {{-- Source Tab Toggle: 3 Options --}}
                                        <div class="grid grid-cols-3 rounded-xl border border-gray-200 overflow-hidden mb-3 text-xs font-bold">
                                            <button type="button" onclick="switchVideoSource('new-{{ $mod->id }}', 'url')"
                                                class="vs-tab-url-new-{{ $mod->id }} px-2 py-2.5 bg-blue-600 text-white transition-colors text-center truncate">
                                                🔗 Web Link
                                            </button>
                                            <button type="button" onclick="switchVideoSource('new-{{ $mod->id }}', 'gdrive')"
                                                class="vs-tab-gdrive-new-{{ $mod->id }} px-2 py-2.5 bg-gray-50 text-gray-600 hover:bg-gray-100 transition-colors text-center truncate">
                                                ☁️ Drive to Server
                                            </button>
                                            <button type="button" onclick="switchVideoSource('new-{{ $mod->id }}', 'upload')"
                                                class="vs-tab-up-new-{{ $mod->id }} px-2 py-2.5 bg-gray-50 text-gray-600 hover:bg-gray-100 transition-colors text-center truncate">
                                                📁 Upload File
                                            </button>
                                        </div>
                                        {{-- Option 1: Standard URL --}}
                                        <div id="vsUrlWrap-new-{{ $mod->id }}">
                                            <input type="url" name="video_url" placeholder="https://youtube.com/watch?v=... or Vimeo link"
                                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-jlm">
                                            <p class="text-[11px] text-gray-400 mt-1">Streams directly from YouTube, Vimeo, or external media server.</p>
                                        </div>
                                        {{-- Option 2: Google Drive Direct Import to Server --}}
                                        <div id="vsGdriveWrap-new-{{ $mod->id }}" class="hidden space-y-2.5">
                                            <input type="hidden" name="imported_video_path" id="gdriveImportedPath-new-{{ $mod->id }}" value="">
                                            <div>
                                                <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 mb-1">Google Drive Share Link</label>
                                                <input type="url" id="gdriveInput-new-{{ $mod->id }}" name="gdrive_import_url"
                                                    placeholder="https://drive.google.com/file/d/.../view?usp=sharing"
                                                    class="w-full px-4 py-3 border border-indigo-200 bg-indigo-50/30 rounded-xl text-sm focus:outline-none focus:border-indigo-600">
                                            </div>
                                            <div class="flex flex-wrap items-center gap-2 pt-0.5">
                                                <button type="button" onclick="fetchGDriveVideo('new-{{ $mod->id }}')" id="gdriveFetchBtn-new-{{ $mod->id }}"
                                                    class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm">
                                                    <i class="fas fa-cloud-arrow-down"></i> <span>Download & Save to Server</span>
                                                </button>
                                                <span class="text-[11px] text-gray-400">Click to fetch the video into server storage first.</span>
                                            </div>
                                            <div id="gdriveStatus-new-{{ $mod->id }}" class="hidden text-xs rounded-xl p-3 border"></div>
                                        </div>
                                        {{-- Option 3: File Upload Input --}}
                                        <div id="vsUploadWrap-new-{{ $mod->id }}" class="hidden">
                                            <input type="file" name="video_file"
                                                accept="video/mp4,video/webm,video/mov,video/quicktime,video/x-msvideo,.mp4,.webm,.mov,.avi"
                                                class="w-full px-4 py-2.5 border border-dashed border-blue-300 bg-blue-50/50 rounded-xl text-sm focus:outline-none focus:border-blue-600
                                                       file:mr-3 file:py-1.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                                            <p class="text-[11px] text-gray-400 mt-1.5">MP4, WebM, MOV, AVI &mdash; max 500 MB.</p>
                                        </div>
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

<!-- ================= AI COURSE OUTLINE MODAL ================= -->
<div id="aiOutlineModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm p-4 sm:p-6 flex items-center justify-center">
    <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6 sm:p-8 shadow-2xl border border-gray-100 space-y-5 my-auto">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-lg font-bold shadow">
                    ✨
                </div>
                <div>
                    <h3 class="text-lg font-extrabold text-gray-900">AI Course Outline Generator</h3>
                    <p class="text-xs text-gray-500">Gemini AI generates full module & lesson curriculum</p>
                </div>
            </div>
            <button onclick="toggleModal('aiOutlineModal')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <div id="aiOutlineLoading" class="py-12 text-center space-y-3">
            <div class="inline-block w-10 h-10 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
            <p class="text-sm font-bold text-indigo-900">Designing your curriculum with Gemini AI...</p>
            <p class="text-xs text-gray-400">Analyzing course title, level, and prerequisites</p>
        </div>

        <div id="aiOutlineContent" class="hidden space-y-4">
            <p class="text-xs text-gray-600">Here is the curriculum structure generated for your course. You can review and copy these into your course modules:</p>
            <div id="aiOutlineTree" class="bg-gray-50 rounded-2xl p-4 max-h-[50vh] overflow-y-auto space-y-3 text-xs border border-gray-200 font-sans">
            </div>
        </div>

        <div class="pt-3 border-t border-gray-100 flex justify-end gap-2">
            <button type="button" onclick="toggleModal('aiOutlineModal')" class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-xs font-bold">Close</button>
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

function addBullet(containerId, inputName) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const div = document.createElement('div');
    div.className = 'flex items-center gap-2';
    div.innerHTML = `
        <i class="fas fa-grip-vertical text-gray-300 cursor-grab text-xs"></i>
        <input type="text" name="${inputName}[]" placeholder="Enter bullet point..."
               class="flex-1 px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-jlm">
        <button type="button" onclick="removeBullet(this)" class="text-red-400 hover:text-red-600 text-xs px-1">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(div);
    div.querySelector('input').focus();
}

function removeBullet(btn) {
    const item = btn.closest('div');
    const container = item.parentElement;
    if (container.children.length > 1) {
        item.remove();
    } else {
        item.querySelector('input').value = '';
    }
}

// ================= AI GENERATOR JAVASCRIPT =================
async function aiGenerate(action) {
    const titleInput = document.getElementById('title');
    const descInput = document.getElementById('description');
    const levelSelect = document.getElementById('level');
    const categorySelect = document.getElementById('category');
    const aiStatus = document.getElementById('aiStatus');

    const title = titleInput ? titleInput.value.trim() : '';
    if (!title) {
        showModal({
            type: 'warning',
            title: 'Course Title Required',
            message: 'Please enter a Course Title first before generating content with AI.'
        });
        if (titleInput) titleInput.focus();
        return;
    }

    if (aiStatus) aiStatus.classList.remove('hidden');

    try {
        const response = await fetch('{{ route("instructor.ai.generate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                action: action,
                title: title,
                level: levelSelect ? levelSelect.value : 'Beginner',
                category: categorySelect ? categorySelect.value : '',
                description: descInput ? descInput.value : ''
            })
        });

        const result = await response.json();
        if (aiStatus) aiStatus.classList.add('hidden');

        if (!result.success) {
            showModal({
                type: 'error',
                title: 'AI Generation Notice',
                message: result.message || 'Unable to generate content. Please verify your Gemini API key in Settings.'
            });
            return;
        }

        if (action === 'description' && descInput) {
            descInput.value = result.data;
            descInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            showToast('Course description generated by Gemini AI ✨', 'success');
        } else if (action === 'outcomes') {
            const container = document.getElementById('outcomes-list');
            if (container && Array.isArray(result.data)) {
                container.innerHTML = '';
                result.data.forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'outcome-item flex items-center gap-2';
                    div.innerHTML = `
                        <i class="fas fa-grip-vertical text-gray-300 cursor-grab text-xs"></i>
                        <input type="text" name="what_you_will_learn[]" value="${item.replace(/"/g, '&quot;')}"
                               class="flex-1 px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-jlm">
                        <button type="button" onclick="removeBullet(this)" class="text-red-400 hover:text-red-600 text-xs px-1"><i class="fas fa-times"></i></button>
                    `;
                    container.appendChild(div);
                });
                container.scrollIntoView({ behavior: 'smooth', block: 'center' });
                showToast('Learning outcomes generated ✨', 'success');
            }
        } else if (action === 'requirements') {
            const container = document.getElementById('requirements-list');
            if (container && Array.isArray(result.data)) {
                container.innerHTML = '';
                result.data.forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'req-item flex items-center gap-2';
                    div.innerHTML = `
                        <i class="fas fa-grip-vertical text-gray-300 cursor-grab text-xs"></i>
                        <input type="text" name="requirements[]" value="${item.replace(/"/g, '&quot;')}"
                               class="flex-1 px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-jlm">
                        <button type="button" onclick="removeBullet(this)" class="text-red-400 hover:text-red-600 text-xs px-1"><i class="fas fa-times"></i></button>
                    `;
                    container.appendChild(div);
                });
                container.scrollIntoView({ behavior: 'smooth', block: 'center' });
                showToast('Requirements generated ✨', 'success');
            }
        }
    } catch (err) {
        if (aiStatus) aiStatus.classList.add('hidden');
        showModal({
            type: 'error',
            title: 'Connection Error',
            message: 'Network or AI service request failed: ' + err.message
        });
    }
}

async function showOutlineModal() {
    const titleInput = document.getElementById('title');
    const descInput = document.getElementById('description');
    const levelSelect = document.getElementById('level');
    const categorySelect = document.getElementById('category');

    const title = titleInput ? titleInput.value.trim() : '';
    if (!title) {
        showModal({
            type: 'warning',
            title: 'Course Title Required',
            message: 'Please enter a Course Title first before generating the curriculum outline.'
        });
        if (titleInput) titleInput.focus();
        return;
    }

    toggleModal('aiOutlineModal');
    const loading = document.getElementById('aiOutlineLoading');
    const content = document.getElementById('aiOutlineContent');
    const tree = document.getElementById('aiOutlineTree');

    loading.classList.remove('hidden');
    content.classList.add('hidden');

    try {
        const response = await fetch('{{ route("instructor.ai.generate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                action: 'outline',
                title: title,
                level: levelSelect ? levelSelect.value : 'Beginner',
                category: categorySelect ? categorySelect.value : '',
                description: descInput ? descInput.value : ''
            })
        });

        const result = await response.json();
        loading.classList.add('hidden');

        if (!result.success) {
            toggleModal('aiOutlineModal');
            showModal({
                type: 'error',
                title: 'AI Outline Notice',
                message: result.message || 'Failed to generate course outline.'
            });
            return;
        }

        content.classList.remove('hidden');
        tree.innerHTML = '';

        if (Array.isArray(result.data)) {
            result.data.forEach((mod, idx) => {
                const modBox = document.createElement('div');
                modBox.className = 'bg-white p-4 rounded-xl border border-gray-200 shadow-sm';
                let lessonsHtml = '<ul class="mt-2 space-y-1 pl-4">';
                if (Array.isArray(mod.lessons)) {
                    mod.lessons.forEach(lesson => {
                        lessonsHtml += `<li class="text-gray-700 list-disc">${lesson}</li>`;
                    });
                }
                lessonsHtml += '</ul>';

                modBox.innerHTML = `
                    <div class="font-extrabold text-indigo-900 flex items-center gap-2">
                        <i class="fas fa-folder text-indigo-500"></i> ${mod.module || 'Module ' + (idx + 1)}
                    </div>
                    ${lessonsHtml}
                `;
                tree.appendChild(modBox);
            });
            showToast('Course curriculum generated ✨', 'success');
        }
    } catch (err) {
        loading.classList.add('hidden');
        toggleModal('aiOutlineModal');
        showModal({
            type: 'error',
            title: 'Generation Failed',
            message: 'Failed to generate course outline: ' + err.message
        });
    }
}

// ── Video Source Switcher (URL tab vs GDrive tab vs Upload tab) ─────────────
function switchVideoSource(id, mode) {
    const urlWrap    = document.getElementById('vsUrlWrap-' + id);
    const gdriveWrap = document.getElementById('vsGdriveWrap-' + id);
    const uploadWrap = document.getElementById('vsUploadWrap-' + id);

    const urlBtns    = document.querySelectorAll('.vs-tab-url-' + id);
    const gdriveBtns = document.querySelectorAll('.vs-tab-gdrive-' + id);
    const upBtns     = document.querySelectorAll('.vs-tab-up-' + id);

    const form = (urlWrap || uploadWrap || gdriveWrap)?.closest('form');

    const urlInput    = urlWrap?.querySelector('input[name="video_url"]');
    const gdriveInput = gdriveWrap?.querySelector('input[name="gdrive_import_url"]');
    const fileInput   = uploadWrap?.querySelector('input[type="file"]');

    // Reset all tabs to inactive style
    const setInactive = (btns) => btns.forEach(b => {
        b.classList.remove('bg-blue-600', 'text-white');
        b.classList.add('bg-gray-50', 'text-gray-600');
    });
    const setActive = (btns) => btns.forEach(b => {
        b.classList.remove('bg-gray-50', 'text-gray-600');
        b.classList.add('bg-blue-600', 'text-white');
    });

    // Hide all input wrappers
    if (urlWrap)    urlWrap.classList.add('hidden');
    if (gdriveWrap) gdriveWrap.classList.add('hidden');
    if (uploadWrap) uploadWrap.classList.add('hidden');

    setInactive(urlBtns);
    setInactive(gdriveBtns);
    setInactive(upBtns);

    if (mode === 'upload') {
        if (uploadWrap) uploadWrap.classList.remove('hidden');
        setActive(upBtns);
        if (fileInput)   fileInput.disabled = false;
        if (urlInput)    urlInput.disabled = true;
        if (gdriveInput) gdriveInput.disabled = true;
        if (form) form.setAttribute('enctype', 'multipart/form-data');
    } else if (mode === 'gdrive') {
        if (gdriveWrap) gdriveWrap.classList.remove('hidden');
        setActive(gdriveBtns);
        if (gdriveInput) gdriveInput.disabled = false;
        if (urlInput)    urlInput.disabled = true;
        if (fileInput)   fileInput.disabled = true;
    } else {
        if (urlWrap) urlWrap.classList.remove('hidden');
        setActive(urlBtns);
        if (urlInput)    urlInput.disabled = false;
        if (gdriveInput) gdriveInput.disabled = true;
        if (fileInput)   fileInput.disabled = true;
    }
}

// Auto-detect upload tab for lessons that already have a server-side video path
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[id^="vsUploadWrap-"]').forEach(wrap => {
        const currentBadge = wrap.querySelector('p');
        if (currentBadge && currentBadge.textContent.includes('Server File:')) {
            const id = wrap.id.replace('vsUploadWrap-', '');
            switchVideoSource(id, 'upload');
        }
    });

    // Add submit progress indicator for lesson forms
    document.querySelectorAll('form[action*="/instructor/lessons"]').forEach(form => {
        form.addEventListener('submit', function (e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            const gdriveInput = form.querySelector('input[name="gdrive_import_url"]');
            const fileInput = form.querySelector('input[type="file"][name="video_file"]');

            if (submitBtn) {
                if (gdriveInput && !gdriveInput.disabled && gdriveInput.value.trim()) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1.5"></i> Downloading from Drive to Server...';
                    submitBtn.classList.add('opacity-75', 'cursor-wait');
                } else if (fileInput && !fileInput.disabled && fileInput.files && fileInput.files.length > 0) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1.5"></i> Uploading Video File...';
                    submitBtn.classList.add('opacity-75', 'cursor-wait');
                }
            }
        });
    });
});

// ── Google Drive Standalone Video Fetcher ──────────────────────────────────
async function fetchGDriveVideo(id) {
    const input      = document.getElementById('gdriveInput-' + id);
    const btn        = document.getElementById('gdriveFetchBtn-' + id);
    const statusBox  = document.getElementById('gdriveStatus-' + id);
    const hiddenPath = document.getElementById('gdriveImportedPath-' + id);

    if (!input || !input.value.trim()) {
        if (statusBox) {
            statusBox.className = 'text-xs rounded-xl p-3 border bg-amber-50 border-amber-200 text-amber-800';
            statusBox.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i> Please paste a valid Google Drive link first.';
            statusBox.classList.remove('hidden');
        }
        return;
    }

    const driveUrl = input.value.trim();
    const originalBtnContent = btn ? btn.innerHTML : '';

    if (btn) {
        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-wait');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1.5"></i> Downloading from Google Drive to Server...';
    }

    if (statusBox) {
        statusBox.className = 'text-xs rounded-xl p-3 border bg-blue-50 border-blue-200 text-blue-800 flex items-center gap-2';
        statusBox.innerHTML = '<i class="fas fa-circle-notch fa-spin text-blue-600 flex-shrink-0"></i> <span>Connecting to Google Drive and streaming video file directly to server storage... Please wait a moment.</span>';
        statusBox.classList.remove('hidden');
    }

    try {
        const response = await fetch('{{ route("instructor.lessons.import-gdrive") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ gdrive_url: driveUrl })
        });

        const data = await response.json();

        if (response.ok && data.success) {
            if (hiddenPath) hiddenPath.value = data.path;

            if (statusBox) {
                statusBox.className = 'text-xs rounded-xl p-3 border bg-emerald-50 border-emerald-200 text-emerald-800 space-y-1';
                statusBox.innerHTML = `
                    <div class="font-bold flex items-center gap-1.5 text-emerald-700">
                        <i class="fas fa-check-circle text-emerald-600"></i> Video downloaded and saved to server storage!
                    </div>
                    <div class="text-[11px] text-emerald-700">
                        Saved file: <span class="font-mono font-semibold">${data.filename}</span> (${data.size}) &bull; Ready to save with lesson.
                    </div>
                `;
                statusBox.classList.remove('hidden');
            }

            if (btn) {
                btn.disabled = false;
                btn.classList.remove('opacity-75', 'cursor-wait', 'bg-indigo-600', 'hover:bg-indigo-700');
                btn.classList.add('bg-emerald-600', 'hover:bg-emerald-700');
                btn.innerHTML = '<i class="fas fa-check mr-1.5"></i> Video Saved on Server (Click to re-download)';
            }
        } else {
            throw new Error(data.message || 'Download failed.');
        }
    } catch (err) {
        if (statusBox) {
            statusBox.className = 'text-xs rounded-xl p-3 border bg-red-50 border-red-200 text-red-800';
            statusBox.innerHTML = `
                <div class="font-bold flex items-center gap-1.5 text-red-700">
                    <i class="fas fa-times-circle text-red-600"></i> Could not download from Google Drive
                </div>
                <div class="text-[11px] text-red-600 mt-1">
                    ${err.message || 'Please check that the file sharing on Google Drive is set to "Anyone with the link can view".'}
                </div>
            `;
            statusBox.classList.remove('hidden');
        }

        if (btn) {
            btn.disabled = false;
            btn.classList.remove('opacity-75', 'cursor-wait');
            btn.innerHTML = originalBtnContent || '<i class="fas fa-cloud-arrow-down mr-1.5"></i> Download & Save to Server';
        }
    }
}

</script>
@endsection

