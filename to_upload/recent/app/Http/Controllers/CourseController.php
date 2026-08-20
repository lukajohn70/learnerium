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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => ['required', 'string', 'max:100'],
            'thumbnail' => ['nullable', 'url'],
            'thumbnail_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'level' => ['required', 'in:Beginner,Intermediate,Advanced,All Levels'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
        ]);

        if ($request->hasFile('thumbnail_file')) {
            $file = $request->file('thumbnail_file');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\._-]/', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/thumbnails'), $filename);
            $data['thumbnail'] = asset('uploads/thumbnails/' . $filename);
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

        if (!$course->published_at) {
            $course->update(['published_at' => now()]);
        }

        return redirect()
            ->route('instructor.manage.courses')
            ->with('status', 'Course published successfully.');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => ['required', 'string', 'max:100'],
            'thumbnail' => ['nullable', 'url'],
            'thumbnail_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'level' => ['required', 'in:Beginner,Intermediate,Advanced,All Levels'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
        ]);

        if ($request->hasFile('thumbnail_file')) {
            $file = $request->file('thumbnail_file');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\._-]/', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/thumbnails'), $filename);
            $data['thumbnail'] = asset('uploads/thumbnails/' . $filename);
        }
        unset($data['thumbnail_file']);

        $data['instructor_id'] = Auth::id();
        $data['slug'] = Str::slug($data['title']);
        $data['price'] = $data['price'] ?? 0;
        $data['published_at'] = now();

        $course = Course::create($data);

        return redirect()
            ->route('instructor.courses.edit', $course)
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
