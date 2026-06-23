@extends('admin.layout.admin_layout')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Blogs</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-header">
                    <form method="GET" action="{{ route('admin::blogs.index') }}" class="form-inline">
                        <label class="mr-2">Search:</label>
                        <input type="text" name="search" class="form-control mr-2" placeholder="Title..." value="{{ request('search') }}">
                        
                        <label class="mr-2 ml-3">Category:</label>
                        <select name="category_id" class="form-control mr-2">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        <label class="mr-2 ml-3">Status:</label>
                        <select name="status" class="form-control mr-2">
                            <option value="">All Statuses</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        <button type="submit" class="btn btn-primary ml-2">Filter</button>
                        <a href="{{ route('admin::blogs.index') }}" class="btn btn-default ml-2">Clear</a>
                    </form>
                    <div class="card-tools mt-2">
                        <a href="{{ route('admin::blogs.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add Blog
                        </a>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Featured</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($blogs as $blog)
                                <tr>
                                    <td>
                                        @if($blog->featuredImage)
                                            <img src="{{ asset($blog->featuredImage->file_path) }}" alt="img" width="50" height="50" style="object-fit: cover; border-radius: 4px;">
                                        @else
                                            <div style="width: 50px; height: 50px; background: #eee; border-radius: 4px;"></div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ \Illuminate\Support\Str::limit($blog->title, 40) }}</strong><br>
                                        <small class="text-muted">{{ $blog->author ?? 'Admin' }}</small>
                                    </td>
                                    <td>{{ $blog->category ? $blog->category->name : '-' }}</td>
                                    <td>
                                        @if($blog->status === 'published')
                                            <span class="badge badge-success">Published</span>
                                        @else
                                            <span class="badge badge-warning">Draft</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($blog->is_featured)
                                            <span class="badge badge-primary">Yes ({{ $blog->featured_order }})</span>
                                        @else
                                            <span class="badge badge-secondary">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($blog->published_at)
                                            {{ $blog->published_at->format('M d, Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin::blogs.edit', $blog->id) }}" class="btn btn-info btn-sm">Edit</a>
                                        <form action="{{ route('admin::blogs.destroy', $blog->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this blog?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No blogs found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $blogs->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
