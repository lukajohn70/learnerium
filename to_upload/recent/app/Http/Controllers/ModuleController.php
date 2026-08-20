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

    /**
     * Add material (document or link) to a module.
     */
    public function addMaterial(Request $request, Course $course, Module $module)
    {
        $user = Auth::user();
        if ($course->instructor_id !== $user->id && $user->role !== 'admin') {
            abort(403, 'Unauthorized course modification.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'type'  => 'required|in:document,link',
            'file'  => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip,txt|max:20480',
            'url'   => 'nullable|url',
        ]);

        $urlOrPath = '';
        $fileName  = null;

        if ($request->type === 'document') {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = $file->getClientOriginalName();
                $filenameToStore = time() . '_' . preg_replace('/[^A-Za-z0-9\._-]/', '_', $fileName);
                $file->move(public_path('uploads/materials'), $filenameToStore);
                $urlOrPath = asset('uploads/materials/' . $filenameToStore);
            } else {
                return back()->withErrors(['file' => 'Please select a document file to upload.']);
            }
        } else {
            if (!$request->url) {
                return back()->withErrors(['url' => 'Please enter a valid URL link.']);
            }
            $urlOrPath = $request->url;
        }

        $module->materials()->create([
            'title'       => $request->title,
            'type'        => $request->type,
            'url_or_path' => $urlOrPath,
            'file_name'   => $fileName,
        ]);

        return back()->with('status', 'Module material added successfully!');
    }

    /**
     * Delete a material from a module.
     */
    public function deleteMaterial(Course $course, Module $module, \App\Models\ModuleMaterial $material)
    {
        $user = Auth::user();
        if ($course->instructor_id !== $user->id && $user->role !== 'admin') {
            abort(403, 'Unauthorized course modification.');
        }

        $material->delete();

        return back()->with('status', 'Material removed successfully.');
    }
}
