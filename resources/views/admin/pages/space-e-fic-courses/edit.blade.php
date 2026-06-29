@extends('admin.layout.admin_layout')

@section('title', 'Edit Space E Fic Course')

@section('content')
<div class="content-wrapper cms-page">
    <section class="content-header"><div class="container-fluid"><div class="row align-items-center">
        <div class="col-sm-6"><h1>Edit Space E Fic Course</h1></div>
        <div class="col-sm-6 text-sm-right"><a href="{{ route('admin::content.space-e-fic-courses.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left mr-1"></i> Back</a></div>
    </div></div></section>
    
    <section class="content"><div class="container-fluid"><div class="card cms-card">
        <form action="{{ route('admin::content.space-e-fic-courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="form-group">
                    <label>Course Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $course->title) }}" required>
                </div>

                <div class="form-group">
                    <label>Description <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control" rows="4" required>{{ old('description', $course->description) }}</textarea>
                    <small class="form-text text-muted">Keep this concise for the course card.</small>
                </div>

                <div class="form-group">
                    <label>Image (Optional)</label><br>
                    @if($course->image)
                        <img src="{{ asset($course->image) }}" alt="Current Image" style="height: 100px; margin-bottom: 10px; border-radius: 5px;">
                    @endif
                    <input type="file" name="image" class="form-control-file" accept="image/*">
                    <small class="form-text text-muted">Upload a new image to replace the current one.</small>
                </div>

                <div class="form-group">
                    <label>Sort Order</label>
                    <input type="number" name="order" class="form-control" value="{{ old('order', $course->order) }}" min="0">
                </div>
            </div>
            
            <div class="card-footer cms-form-actions">
                <a href="{{ route('admin::content.space-e-fic-courses.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Update Course</button>
            </div>
        </form>
    </div></div></section>
</div>
@endsection
