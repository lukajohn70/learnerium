<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    /**
     * Store a newly created module for a course.
     */
    public function store(Request $request, Course $course)
    {
        $user = Auth::user();
        if ($course->instructor_id !== $user->id && $user->role !== 'admin') {
            abort(403, 'Unauthorized course modification.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $nextOrder = $course->modules()->count() + 1;

        $course->modules()->create([
            'title' => $request->title,
            'description' => $request->description,
            'order' => $nextOrder,
        ]);

        return back()->with('status', 'Module added successfully!');
    }

    /**
     * Update an existing module.
     */
    public function update(Request $request, Course $course, Module $module)
    {
        $user = Auth::user();
        if ($course->instructor_id !== $user->id && $user->role !== 'admin') {
            abort(403, 'Unauthorized course modification.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $module->update([
            'title' => $request->title,
            'description' => $request->description,
            'order' => $request->order ?? $module->order,
        ]);

        return back()->with('status', 'Module updated successfully!');
    }

    /**
     * Delete a module.
     */
    public function destroy(Course $course, Module $module)
    {
        $user = Auth::user();
        if ($course->instructor_id !== $user->id && $user->role !== 'admin') {
            abort(403, 'Unauthorized course modification.');
        }

        $module->delete();

        return back()->with('status', 'Module deleted successfully!');
    }
}
