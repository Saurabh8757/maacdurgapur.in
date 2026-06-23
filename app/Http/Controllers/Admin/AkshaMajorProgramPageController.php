<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AkshaMajorProgram;
use Illuminate\Http\Request;

class AkshaMajorProgramPageController extends Controller
{
    public function index()
    {
        $programs = AkshaMajorProgram::with('featuredImage')->orderBy('sort_order')->get();
        return view('admin.pages.aksha-major-programs.index', compact('programs'));
    }

    public function create()
    {
        return view('admin.pages.aksha-major-programs.create');
    }

    public function edit(AkshaMajorProgram $program)
    {
        return view('admin.pages.aksha-major-programs.edit', compact('program'));
    }
}
