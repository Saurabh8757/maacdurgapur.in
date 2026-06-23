<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\MediaAsset;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Brand;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::with(['brand', 'category']);
        
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $blogs = $query->orderBy('created_at', 'desc')->paginate(20);
        $categories = BlogCategory::all();

        return view('admin.pages.blogs.index', compact('blogs', 'categories'));
    }

    public function create()
    {
        $categories = BlogCategory::where('status', 'active')->get();
        $brands = Brand::all();
        return view('admin.pages.blogs.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blogs,slug',
            'category_id' => 'required|exists:blog_categories,id',
            'status' => 'required|in:draft,published',
            'featured_image' => 'nullable|image|max:5120',
        ]);

        $data = $request->only([
            'title', 'slug', 'excerpt', 'content', 'author', 'reading_time',
            'meta_title', 'meta_description', 'canonical_url',
            'og_title', 'og_description',
            'featured_order', 'status', 'published_at',
            'category_id', 'brand_id',
        ]);

        // Handle tags
        if ($request->filled('tags')) {
            $tagsArray = explode(',', $request->tags);
            $data['tags'] = array_values(array_filter(array_map('trim', $tagsArray)));
        }

        // Handle is_featured checkbox
        $data['is_featured'] = $request->has('is_featured');

        // Handle empty brand_id
        if (empty($data['brand_id'])) {
            $data['brand_id'] = null;
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $data['featured_image_media_id'] = $this->uploadFeaturedImage($request->file('featured_image'));
        }

        Blog::create($data);

        return redirect()->route('admin::blogs.index')->with('success', 'Blog created successfully.');
    }

    public function edit(Blog $blog)
    {
        $categories = BlogCategory::where('status', 'active')->get();
        $brands = Brand::all();
        return view('admin.pages.blogs.edit', compact('blog', 'categories', 'brands'));
    }

    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blogs,slug,' . $blog->id,
            'category_id' => 'required|exists:blog_categories,id',
            'status' => 'required|in:draft,published',
            'featured_image' => 'nullable|image|max:5120',
        ]);

        $data = $request->only([
            'title', 'slug', 'excerpt', 'content', 'author', 'reading_time',
            'meta_title', 'meta_description', 'canonical_url',
            'og_title', 'og_description',
            'featured_order', 'status', 'published_at',
            'category_id', 'brand_id',
        ]);

        // Handle tags
        if ($request->filled('tags')) {
            $tagsArray = explode(',', $request->tags);
            $data['tags'] = array_values(array_filter(array_map('trim', $tagsArray)));
        } else {
            $data['tags'] = null;
        }

        // Handle is_featured checkbox
        $data['is_featured'] = $request->has('is_featured');

        // Handle empty brand_id
        if (empty($data['brand_id'])) {
            $data['brand_id'] = null;
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $data['featured_image_media_id'] = $this->uploadFeaturedImage($request->file('featured_image'));
        }

        $blog->update($data);

        return redirect()->route('admin::blogs.index')->with('success', 'Blog updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();
        return redirect()->route('admin::blogs.index')->with('success', 'Blog deleted successfully.');
    }

    /**
     * Upload featured image and create MediaAsset record.
     */
    private function uploadFeaturedImage($file): int
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . time() . '.' . $extension;
        $path = $file->storeAs('blogs', $filename, 'public');

        $asset = MediaAsset::create([
            'uploaded_by' => Auth::id(),
            'storage_disk' => 'public',
            'storage_key' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'display_name' => $file->getClientOriginalName(),
            'extension' => $extension,
            'mime_type' => $file->getMimeType(),
            'media_type' => 'image',
            'size_bytes' => $file->getSize(),
            'checksum_sha256' => hash_file('sha256', $file->getRealPath()),
            'visibility' => 'public',
            'status' => 'ready',
        ]);

        return $asset->id;
    }
}
