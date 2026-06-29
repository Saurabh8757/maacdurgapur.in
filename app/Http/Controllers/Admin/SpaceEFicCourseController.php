<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpaceEFicCourse;
use Illuminate\Http\Request;

class SpaceEFicCourseController extends Controller
{
    public function index()
    {
        $courses = SpaceEFicCourse::orderBy('order')->get();
        return view('admin.pages.space-e-fic-courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.pages.space-e-fic-courses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'order' => 'integer',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload/images/space_e_fic'), $name);
            $data['image'] = 'upload/images/space_e_fic/' . $name;
        }

        SpaceEFicCourse::create($data);

        return redirect()->route('admin::content.space-e-fic-courses.index')->with('success', 'Course created successfully.');
    }

    public function edit(SpaceEFicCourse $space_e_fic_course)
    {
        $course = $space_e_fic_course;
        return view('admin.pages.space-e-fic-courses.edit', compact('course'));
    }

    public function update(Request $request, SpaceEFicCourse $space_e_fic_course)
    {
        $course = $space_e_fic_course;
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'order' => 'integer',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($course->image && file_exists(public_path($course->image))) {
                unlink(public_path($course->image));
            }
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload/images/space_e_fic'), $name);
            $data['image'] = 'upload/images/space_e_fic/' . $name;
        }

        $course->update($data);

        return redirect()->route('admin::content.space-e-fic-courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy(SpaceEFicCourse $space_e_fic_course)
    {
        $course = $space_e_fic_course;
        if ($course->image && file_exists(public_path($course->image))) {
            unlink(public_path($course->image));
        }
        $course->delete();
        return redirect()->route('admin::content.space-e-fic-courses.index')->with('success', 'Course deleted successfully.');
    }
}
