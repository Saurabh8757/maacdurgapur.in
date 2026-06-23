<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Blog::with(['category', 'featuredImage'])
            ->where('status', 'published');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('excerpt', 'like', '%' . $search . '%')
                  ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        $blogs = $query->orderBy('is_featured', 'desc')
                       ->orderBy('featured_order', 'asc')
                       ->orderBy('published_at', 'desc')
                       ->get();

        $categories = \App\Models\BlogCategory::where('status', 'active')->get();
        $courses = \App\Models\OurCourse::where('status', 'Active')->get();

        return view('frontend.pages.blog', compact('blogs', 'categories', 'courses'));
    }

    public function show($slug)
    {
        $blog = \App\Models\Blog::with(['category', 'featuredImage', 'brand'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $relatedPosts = \App\Models\Blog::with(['category', 'featuredImage'])
            ->where('status', 'published')
            ->where('category_id', $blog->category_id)
            ->where('id', '!=', $blog->id)
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        $latestPosts = \App\Models\Blog::with(['category', 'featuredImage'])
            ->where('status', 'published')
            ->where('id', '!=', $blog->id)
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        $courses = \App\Models\OurCourse::where('status', 'Active')->get();

        return view('frontend.pages.blog-detail', compact('blog', 'relatedPosts', 'latestPosts', 'courses'));
    }
}
