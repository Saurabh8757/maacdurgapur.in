@extends('admin.layout.admin_layout')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Blog</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('admin::blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-8">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Blog Content</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" id="title" placeholder="Enter blog title" value="{{ old('title', $blog->title) }}" required>
                                    @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="slug">Slug <span class="text-danger">*</span></label>
                                    <input type="text" name="slug" class="form-control" id="slug" placeholder="Enter unique slug" value="{{ old('slug', $blog->slug) }}" required>
                                    @error('slug') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="excerpt">Excerpt</label>
                                    <textarea name="excerpt" class="form-control" id="excerpt" rows="3" placeholder="Brief summary">{{ old('excerpt', $blog->excerpt) }}</textarea>
                                    @error('excerpt') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="content">Content</label>
                                    <textarea name="content" class="form-control tinymce-editor" id="content" rows="10">{{ old('content', $blog->content) }}</textarea>
                                    @error('content') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">SEO Settings</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="meta_title">Meta Title</label>
                                    <input type="text" name="meta_title" class="form-control" id="meta_title" value="{{ old('meta_title', $blog->meta_title) }}">
                                </div>
                                <div class="form-group">
                                    <label for="meta_description">Meta Description</label>
                                    <textarea name="meta_description" class="form-control" id="meta_description" rows="2">{{ old('meta_description', $blog->meta_description) }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="canonical_url">Canonical URL</label>
                                    <input type="url" name="canonical_url" class="form-control" id="canonical_url" value="{{ old('canonical_url', $blog->canonical_url) }}">
                                </div>
                                <div class="form-group">
                                    <label for="og_title">OG Title</label>
                                    <input type="text" name="og_title" class="form-control" id="og_title" value="{{ old('og_title', $blog->og_title) }}">
                                </div>
                                <div class="form-group">
                                    <label for="og_description">OG Description</label>
                                    <textarea name="og_description" class="form-control" id="og_description" rows="2">{{ old('og_description', $blog->og_description) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Publishing & Metadata</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control" id="status" required>
                                        <option value="draft" {{ old('status', $blog->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="published" {{ old('status', $blog->status) == 'published' ? 'selected' : '' }}>Published</option>
                                    </select>
                                    @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="published_at">Publish Date</label>
                                    <input type="datetime-local" name="published_at" class="form-control" id="published_at" value="{{ old('published_at', $blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : '') }}">
                                </div>
                                <div class="form-group">
                                    <label for="category_id">Category <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-control" id="category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $blog->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="brand_id">Brand</label>
                                    <select name="brand_id" class="form-control" id="brand_id">
                                        <option value="">Global (No Brand)</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ old('brand_id', $blog->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="author">Author</label>
                                    <input type="text" name="author" class="form-control" id="author" value="{{ old('author', $blog->author) }}">
                                </div>
                                <div class="form-group">
                                    <label for="reading_time">Reading Time</label>
                                    <input type="text" name="reading_time" class="form-control" id="reading_time" placeholder="e.g., 5 min read" value="{{ old('reading_time', $blog->reading_time) }}">
                                </div>
                                <div class="form-group">
                                    <label for="tags">Tags (comma separated)</label>
                                    <input type="text" name="tags" class="form-control" id="tags" value="{{ old('tags', $blog->tags ? implode(', ', $blog->tags) : '') }}">
                                </div>
                                <div class="form-group">
                                    <label for="featured_image">Featured Image</label>
                                    @if($blog->featuredImage)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $blog->featuredImage->storage_key) }}" alt="Current Image" width="100%" style="border-radius: 4px;">
                                        </div>
                                    @endif
                                    <input type="file" name="featured_image" class="form-control-file" id="featured_image" accept="image/*">
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $blog->is_featured) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_featured">Featured Blog</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="featured_order">Featured Order</label>
                                    <input type="number" name="featured_order" class="form-control" id="featured_order" value="{{ old('featured_order', $blog->featured_order) }}">
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-block">Update Blog</button>
                                <a href="{{ route('admin::blogs.index') }}" class="btn btn-default btn-block">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection

@section('custom_js')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    document.getElementById('title').addEventListener('input', function() {
        if (document.getElementById('slug').value === '') {
            let slug = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
            document.getElementById('slug').value = slug;
        }
    });

    tinymce.init({
        selector: '.tinymce-editor',
        height: 400,
        plugins: 'advlist autolink lists link image charmap preview anchor pagebreak',
        toolbar_mode: 'floating',
        toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
    });
</script>
@endsection
