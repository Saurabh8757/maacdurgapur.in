@extends('admin.layout.admin_layout')

@section('title', 'Add Space E Fic Course')

@section('content')
<div class="content-wrapper cms-page">
    <section class="content-header"><div class="container-fluid"><div class="row align-items-center">
        <div class="col-sm-6"><h1>Add Space E Fic Course</h1></div>
        <div class="col-sm-6 text-sm-right"><a href="{{ route('admin::content.space-e-fic-courses.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left mr-1"></i> Back</a></div>
    </div></div></section>
    
    <section class="content"><div class="container-fluid"><div class="card cms-card">
        <form action="{{ route('admin::content.space-e-fic-courses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
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
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                </div>

                <div class="form-group">
                    <label>Description <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
                    <small class="form-text text-muted">Keep this concise for the course card.</small>
                </div>

                <div class="form-group">
                    <label>Image (Optional)</label>
                    <input type="file" name="image" class="form-control-file" accept="image/*">
                    <small class="form-text text-muted">Upload a square or portrait image for best results on cards.</small>
                </div>

                <div class="form-group">
                    <label>Sort Order</label>
                    <input type="number" name="order" class="form-control" value="{{ old('order', 0) }}" min="0">
                </div>
            </div>
            
            <div class="card-footer cms-form-actions">
                <a href="{{ route('admin::content.space-e-fic-courses.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Save Course</button>
            </div>
        </form>
    </div></div></section>
</div>
@endsection
