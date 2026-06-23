@extends('admin.layout.admin_layout')
@section('title', 'Edit Recruiter')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Recruiter</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card card-primary">
                <form action="{{ route('admin::recruiters.update', $recruiter->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="company_name">Company Name *</label>
                            <input type="text" name="company_name" class="form-control" id="company_name" value="{{ $recruiter->company_name }}" required>
                            <small class="form-text text-muted">For text-based logos, this text will be converted to a CSS class.</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="company_website">Company Website</label>
                            <input type="url" name="company_website" class="form-control" id="company_website" value="{{ $recruiter->company_website }}">
                        </div>

                        <div class="form-group">
                            <label for="company_logo">Company Logo Image (Optional)</label>
                            @if($recruiter->logo)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $recruiter->logo->storage_key) }}" alt="Logo" width="100">
                                </div>
                            @endif
                            <input type="file" name="company_logo" class="form-control-file" id="company_logo" accept="image/*">
                            <small class="form-text text-muted">If uploaded, this image will replace the text logo on the frontend.</small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ $recruiter->is_featured ? 'checked' : '' }}>
                                <label for="is_featured" class="custom-control-label">Featured</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="is_active" name="is_active" value="1" {{ $recruiter->is_active ? 'checked' : '' }}>
                                <label for="is_active" class="custom-control-label">Active</label>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('admin::recruiters.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
