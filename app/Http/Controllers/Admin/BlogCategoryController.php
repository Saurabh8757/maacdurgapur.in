<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = \App\Models\BlogCategory::orderBy('name')->paginate(20);
        return view('admin.pages.blog_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.pages.blog_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_categories,slug',
            'status' => 'required|in:active,inactive',
        ]);

        \App\Models\BlogCategory::create($request->only([
            'name',
            'slug',
            'status',
        ]));

        return redirect()->route('admin::blog-categories.index')->with('success', 'Blog Category created successfully.');
    }

    public function edit(\App\Models\BlogCategory $blogCategory)
    {
        return view('admin.pages.blog_categories.edit', compact('blogCategory'));
    }

    public function update(Request $request, \App\Models\BlogCategory $blogCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_categories,slug,' . $blogCategory->id,
            'status' => 'required|in:active,inactive',
        ]);

        $blogCategory->update($request->only([
            'name',
            'slug',
            'status',
        ]));

        return redirect()->route('admin::blog-categories.index')->with('success', 'Blog Category updated successfully.');
    }

    public function destroy(\App\Models\BlogCategory $blogCategory)
    {
        $blogCategory->delete();
        return redirect()->route('admin::blog-categories.index')->with('success', 'Blog Category deleted successfully.');
    }
}
