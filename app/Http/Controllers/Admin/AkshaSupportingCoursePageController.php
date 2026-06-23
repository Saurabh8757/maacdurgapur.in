<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AkshaSupportingCoursePageController extends Controller
{
    public function index()
    {
        $courses = \App\Models\AkshaSupportingCourse::with('featuredImage')->orderBy('sort_order')->get();
        return view('admin.pages.aksha-supporting-courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.pages.aksha-supporting-courses.create');
    }

    public function edit(\App\Models\AkshaSupportingCourse $course)
    {
        return view('admin.pages.aksha-supporting-courses.edit', compact('course'));
    }
}
