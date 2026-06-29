<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpaceEFicCourse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'order' => 'integer',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            
            $path = 'upload/images/space_e_fic';
            if (!is_dir(public_path($path))) {
                File::ensureDirectoryExists(public_path($path), 0755, true);
            }
            
            $image->move(public_path($path), $name);
            $data['image'] = $path . '/' . $name;
            $this->mirrorCourseImageToDocumentRoot($data['image']);
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'order' => 'integer',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($course->image) {
                $this->deleteCourseImageFiles($course->image);
            }
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            
            $path = 'upload/images/space_e_fic';
            if (!is_dir(public_path($path))) {
                File::ensureDirectoryExists(public_path($path), 0755, true);
            }
            
            $image->move(public_path($path), $name);
            $data['image'] = $path . '/' . $name;
            $this->mirrorCourseImageToDocumentRoot($data['image']);
        }

        $course->update($data);

        return redirect()->route('admin::content.space-e-fic-courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy(SpaceEFicCourse $space_e_fic_course)
    {
        $course = $space_e_fic_course;
        if ($course->image) {
            $this->deleteCourseImageFiles($course->image);
        }
        $course->delete();
        return redirect()->route('admin::content.space-e-fic-courses.index')->with('success', 'Course deleted successfully.');
    }

    private function mirrorCourseImageToDocumentRoot(?string $relativePath): void
    {
        if (empty($relativePath)) {
            return;
        }

        $source = public_path($relativePath);
        $documentRoot = rtrim((string) request()->server('DOCUMENT_ROOT'), DIRECTORY_SEPARATOR);

        if ($documentRoot === '' || !file_exists($source)) {
            return;
        }

        $target = $documentRoot . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relativePath);

        if (realpath(dirname($source)) === realpath(dirname($target))) {
            return;
        }

        File::ensureDirectoryExists(dirname($target), 0755, true);
        File::copy($source, $target);
    }

    private function deleteCourseImageFiles(?string $relativePath): void
    {
        if (empty($relativePath)) {
            return;
        }

        $paths = [public_path($relativePath)];
        $documentRoot = rtrim((string) request()->server('DOCUMENT_ROOT'), DIRECTORY_SEPARATOR);

        if ($documentRoot !== '') {
            $paths[] = $documentRoot . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relativePath);
        }

        foreach (array_unique($paths) as $path) {
            if (file_exists($path)) {
                @unlink($path);
            }
        }
    }
}
