<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function students(Course $course)
    {
        $this->authorizeInstructor($course);
        $students = $course->students()->withPivot('progress_percentage')->get();
        return view('instructor.course-students', compact('course', 'students'));
    }

    public function index(Request $request)
    {
        $query = Course::whereNotNull('published_at')
            ->latest('published_at')
            ->with('instructor');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $courses = $query->get();
        $categories = Course::whereNotNull('published_at')->whereNotNull('category')->distinct()->pluck('category')->values();

        return view('courses', compact('courses', 'categories'));
    }

    public function show(string $slug)
    {
        $course = Course::where('slug', $slug)
            ->with(['instructor', 'lessons', 'enrollments'])
            ->firstOrFail();

        return view('course_detail', compact('course'));
    }

    public function create()
    {
        return view('create-course');
    }

    public function manage()
    {
        $user = Auth::user();
        $courses = $user->coursesTaught()->latest()->get();

        return view('instructor.manage-courses', compact('courses'));
    }

    public function edit(Course $course)
    {
        $this->authorizeInstructor($course);

        return view('instructor.edit-course', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $this->authorizeInstructor($course);

        $data = $request->validate([
            'title'              => ['required', 'string', 'max:255'],
            'description'        => ['required', 'string'],
            'category'           => ['required', 'string', 'max:100'],
            'thumbnail'          => ['nullable', 'string'],
            'thumbnail_file'     => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'price'              => ['nullable', 'numeric', 'min:0'],
            'level'              => ['required', 'in:Beginner,Intermediate,Advanced,All Levels'],
            'duration_minutes'   => ['required', 'integer', 'min:1'],
            'requirements'       => ['nullable', 'array'],
            'requirements.*'     => ['nullable', 'string', 'max:300'],
            'what_you_will_learn'   => ['nullable', 'array'],
            'what_you_will_learn.*' => ['nullable', 'string', 'max:300'],
        ]);

        // Filter out blank items
        $data['requirements']       = array_values(array_filter($request->input('requirements', []), fn($v) => !empty(trim($v))));
        $data['what_you_will_learn'] = array_values(array_filter($request->input('what_you_will_learn', []), fn($v) => !empty(trim($v))));

        if ($request->hasFile('thumbnail_file')) {
            $file = $request->file('thumbnail_file');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\._-]/', '_', $file->getClientOriginalName());
            if (!is_dir(public_path('uploads/thumbnails'))) {
                mkdir(public_path('uploads/thumbnails'), 0775, true);
            }
            $file->move(public_path('uploads/thumbnails'), $filename);
            $data['thumbnail'] = 'uploads/thumbnails/' . $filename;
        }
        unset($data['thumbnail_file']);

        $data['slug'] = \Illuminate\Support\Str::slug($data['title']);
        $data['price'] = $data['price'] ?? 0;

        $course->update($data);

        return redirect()
            ->route('instructor.courses.edit', $course)
            ->with('status', 'Course details updated successfully.');
    }

    public function publish(Course $course)
    {
        $this->authorizeInstructor($course);

        $wasPublished = (bool) $course->published_at;

        if (!$course->published_at) {
            $course->update(['published_at' => now()]);
        }

        if (!$wasPublished) {
            $user = Auth::user();
            try {
                // 1. Notify Instructor
                \App\Models\AppNotification::notify(
                    $user->id,
                    'course',
                    'Course Published Live! 🚀',
                    "Your course \"{$course->title}\" is now live and available to students across the world.",
                    route('course.detail', $course->slug),
                    'fa-rocket',
                    'green'
                );

                // 2. Notify all registered Admins
                \App\Models\AppNotification::notifyAdmins(
                    'course',
                    "🚀 Course Published: {$course->title}",
                    "Instructor {$user->name} published a new course in category: {$course->category}.",
                    route('course.detail', $course->slug),
                    'fa-book-open',
                    'purple'
                );
            } catch (\Throwable $e) {}
        }

        return redirect()
            ->route('instructor.manage.courses')
            ->with('status', 'Course published successfully.');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'              => ['required', 'string', 'max:255'],
            'description'        => ['required', 'string'],
            'category'           => ['required', 'string', 'max:100'],
            'thumbnail'          => ['nullable', 'string'],
            'thumbnail_file'     => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'price'              => ['nullable', 'numeric', 'min:0'],
            'level'              => ['required', 'in:Beginner,Intermediate,Advanced,All Levels'],
            'duration_minutes'   => ['required', 'integer', 'min:1'],
            'requirements'       => ['nullable', 'array'],
            'requirements.*'     => ['nullable', 'string', 'max:300'],
            'what_you_will_learn'   => ['nullable', 'array'],
            'what_you_will_learn.*' => ['nullable', 'string', 'max:300'],
        ]);

        // Filter out blank items
        $data['requirements']        = array_values(array_filter($request->input('requirements', []), fn($v) => !empty(trim($v))));
        $data['what_you_will_learn'] = array_values(array_filter($request->input('what_you_will_learn', []), fn($v) => !empty(trim($v))));

        if ($request->hasFile('thumbnail_file')) {
            $file = $request->file('thumbnail_file');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\._-]/', '_', $file->getClientOriginalName());
            if (!is_dir(public_path('uploads/thumbnails'))) {
                mkdir(public_path('uploads/thumbnails'), 0775, true);
            }
            $file->move(public_path('uploads/thumbnails'), $filename);
            $data['thumbnail'] = 'uploads/thumbnails/' . $filename;
        }
        unset($data['thumbnail_file']);

        $data['instructor_id'] = Auth::id();
        $data['slug'] = Str::slug($data['title']);
        $data['price'] = $data['price'] ?? 0;
        $data['published_at'] = now();

        $course = Course::create($data);

        // Notify instructor and all registered admins
        $user = Auth::user();
        try {
            // 1. Notify Instructor
            \App\Models\AppNotification::notify(
                $user->id,
                'course',
                'Course Created Successfully! 📚',
                "Your course \"{$course->title}\" has been created. You can now add modules and lessons.",
                route('instructor.courses.edit', $course),
                'fa-book-open',
                'green'
            );

            // 2. Notify all registered Admins
            \App\Models\AppNotification::notifyAdmins(
                'course',
                "📚 New Course Created: {$course->title}",
                "Instructor {$user->name} created a new course in {$course->category}.",
                route('course.detail', $course->slug),
                'fa-book',
                'purple'
            );
        } catch (\Throwable $e) {}

        return redirect()
            ->route('instructor.courses.edit', $course)
            ->with('status', 'Course created successfully! Now add your modules and lessons.');
    }

    public function destroy(Course $course)
    {
        $this->authorizeInstructor($course);

        $title = $course->title;
        $course->delete();

        return redirect()
            ->route('instructor.manage.courses')
            ->with('status', "Course \"{$title}\" was permanently deleted.");
    }

    private function authorizeInstructor(Course $course): void
    {
        $user = Auth::user();

        if (!$user || $course->instructor_id !== $user->id) {
            abort(403, 'You are not allowed to manage this course.');
        }
    }
}
