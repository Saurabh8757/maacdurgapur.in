@extends('admin.layout.admin_layout')

@section('title', 'Space E Fic Courses')

@section('content')
<div class="content-wrapper cms-page">
    <section class="content-header"><div class="container-fluid"><div class="row align-items-center">
        <div class="col-sm-6"><h1>Space E Fic Courses</h1></div>
        <div class="col-sm-6 text-sm-right"><a href="{{ route('admin::content.space-e-fic-courses.create') }}" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Add Course</a></div>
    </div></div></section>
    
    <section class="content"><div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="card cms-card">
        <div class="card-header border-bottom-0"><h3 class="card-title">Manage Courses for Space E Fic</h3></div>
        <div class="card-body p-0 table-responsive"><table class="table table-hover mb-0">
            <thead><tr><th>Image</th><th>Course Title</th><th>Description</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse ($courses as $course)
                <tr>
                    <td>
                        @if($course->image)
                            <img src="{{ asset($course->image) }}" alt="Img" style="width:40px;height:40px;object-fit:cover;border-radius:4px;">
                        @else
                            <div style="width:40px;height:40px;border-radius:4px;background:#eee;display:flex;align-items:center;justify-content:center;color:#999;"><i class="fas fa-image"></i></div>
                        @endif
                    </td>
                    <td><strong>{{ $course->title }}</strong></td>
                    <td><small class="text-muted">{{ \Illuminate\Support\Str::limit($course->description, 60) }}</small></td>
                    <td>
                        @if($course->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </td>
                    <td><div class="cms-actions d-flex">
                        <a class="btn btn-sm btn-outline-primary mr-2" href="{{ route('admin::content.space-e-fic-courses.edit', $course->id) }}"><i class="fas fa-edit"></i> Edit</a>
                        <form action="{{ route('admin::content.space-e-fic-courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this course?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i> Delete</button>
                        </form>
                    </div></td>
                </tr>
            @empty
                <tr><td colspan="5" class="cms-empty text-center py-4">No courses found.</td></tr>
            @endforelse
            </tbody>
        </table></div>
    </div></div></section>
</div>
@endsection
