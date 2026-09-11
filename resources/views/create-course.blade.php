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
            <form action="{{ route('instructor.courses.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
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
                        <label for="thumbnail_file" class="block text-sm font-semibold text-gray-700 mb-1.5">Upload Thumbnail Image</label>
                        <input type="file" id="thumbnail_file" name="thumbnail_file" accept="image/*"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-primary-jlm text-sm file:mr-4 file:py-1.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary-jlm/10 file:text-primary-jlm hover:file:bg-primary-jlm/20">
                        <p class="text-xs text-gray-400 mt-1">Or paste image URL / relative path below:</p>
                        <input type="text" id="thumbnail" name="thumbnail" value="{{ old('thumbnail') }}"
                               class="w-full mt-1 px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-primary-jlm text-xs"
                               placeholder="e.g. uploads/thumbnails/... or https://...">
                    </div>
                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-700 mb-1.5">Course Price (₦ Naira) <span class="text-gray-400 text-xs font-normal">(Launch / Live Price)</span></label>
                        <input type="number" id="price" name="price" step="0.01" min="0" value="{{ old('price') }}"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-jlm/30 focus:border-primary-jlm transition text-gray-800"
                               placeholder="0.00 for free">
                    </div>
                </div>

                {{-- ⏳ Preorder Settings Card --}}
                @php $preorderEnabled = (bool) old('is_preorder', false); @endphp
                <div class="bg-gradient-to-br from-purple-50 via-indigo-50 to-blue-50 border border-purple-200 rounded-2xl p-5 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center text-white text-sm shadow">
                                <i class="fas fa-bookmark"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-extrabold text-purple-900">Preorder Settings</h3>
                                <p class="text-xs text-purple-500">Sell at an early-bird price before the course officially launches</p>
                            </div>
                        </div>
                        {{-- Toggle --}}
                        <label class="relative inline-flex items-center cursor-pointer gap-2" for="is_preorder_toggle">
                            <input type="hidden" name="is_preorder" value="0">
                            <input type="checkbox" id="is_preorder_toggle" name="is_preorder" value="1"
                                   class="sr-only peer" {{ $preorderEnabled ? 'checked' : '' }}
                                   onchange="togglePreorderFields(this.checked)">
                            <div class="w-10 h-5 bg-gray-300 peer-checked:bg-purple-600 rounded-full transition relative after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition peer-checked:after:translate-x-5"></div>
                            <span class="text-xs font-bold text-purple-800" id="preorder_toggle_label">{{ $preorderEnabled ? 'Enabled' : 'Disabled' }}</span>
                        </label>
                    </div>

                    <div id="preorder_fields" class="{{ $preorderEnabled ? '' : 'hidden' }} space-y-4 border-t border-purple-100 pt-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="preorder_price" class="block text-xs font-bold uppercase tracking-wider text-purple-700 mb-2">
                                    Preorder Price (₦) <span class="text-purple-400 text-[10px] font-normal">— lower than launch price</span>
                                </label>
                                <input id="preorder_price" name="preorder_price" type="number" step="0.01" min="0"
                                       value="{{ old('preorder_price') }}"
                                       placeholder="e.g. 4999 (enter 0 for free preorder)"
                                       class="w-full px-4 py-3 border border-purple-200 bg-white rounded-xl focus:outline-none focus:border-purple-500 text-sm font-bold">
                            </div>
                            <div>
                                <label for="preorder_ends_at" class="block text-xs font-bold uppercase tracking-wider text-purple-700 mb-2">
                                    Preorder Ends (Optional) <span class="text-purple-400 text-[10px] font-normal">— countdown on course page</span>
                                </label>
                                <input id="preorder_ends_at" name="preorder_ends_at" type="datetime-local"
                                       value="{{ old('preorder_ends_at') }}"
                                       class="w-full px-4 py-3 border border-purple-200 bg-white rounded-xl focus:outline-none focus:border-purple-500 text-sm">
                            </div>
                        </div>
                        <div class="bg-purple-100/60 rounded-xl p-3 text-xs text-purple-700 flex items-start gap-2">
                            <i class="fas fa-info-circle mt-0.5 text-purple-500"></i>
                            <span>When enabled, this course will display a <strong>PRE-ORDER</strong> badge. Students will pay the preorder price now, and will automatically gain access when you officially launch/publish the course.</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label for="category" class="block text-sm font-semibold text-gray-700 mb-1.5">Category <span class="text-red-500">*</span></label>
                        <select id="category" name="category"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-jlm/30 focus:border-primary-jlm transition text-gray-800 bg-white" required>
                            <option value="">Select Category</option>
                            @foreach(['Web Development & Programming', 'Data Science & AI', 'Business & Entrepreneurship', 'Graphic Design & UI/UX', 'Digital Marketing & SEO', 'Cyber Security & IT', 'Personal Development', 'Languages & Academics'] as $catOption)
                                <option value="{{ $catOption }}" {{ old('category') === $catOption ? 'selected' : '' }}>{{ $catOption }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="level" class="block text-sm font-semibold text-gray-700 mb-1.5">Level <span class="text-red-500">*</span></label>
                        <select id="level" name="level"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-jlm/30 focus:border-primary-jlm transition text-gray-800 bg-white" required>
                            <option value="">Select Level</option>
                            @foreach(['Beginner', 'Intermediate', 'Advanced', 'All Levels'] as $level)
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

<script>
function togglePreorderFields(enabled) {
    const fields = document.getElementById('preorder_fields');
    const label  = document.getElementById('preorder_toggle_label');
    if (fields) fields.classList.toggle('hidden', !enabled);
    if (label)  label.textContent = enabled ? 'Enabled' : 'Disabled';
}
</script>
@endsection
