@extends('admin.layout.admin_layout')
@section('title', 'Add Recruiter')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add Recruiter</h1>
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
                <form action="{{ route('admin::recruiters.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="company_name">Company Name *</label>
                            <input type="text" name="company_name" class="form-control" id="company_name" value="{{ old('company_name') }}" required>
                            <small class="form-text text-muted">For text-based logos, this text will be converted to a CSS class.</small>
                        </div>

                        <div class="form-group">
                            <label for="company_website">Company Website</label>
                            <input type="url" name="company_website" class="form-control" id="company_website" value="{{ old('company_website') }}" placeholder="https://example.com">
                        </div>

                        <div class="form-group">
                            <label for="company_logo">Company Logo Image (Optional)</label>
                            <input type="file" name="company_logo" class="form-control-file" id="company_logo" accept="image/*">
                            <small class="form-text text-muted">If uploaded, this image will replace the text logo on the frontend.</small>
                        </div>

                        <div class="form-group">
                            <label for="sort_order">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" id="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                <label for="is_featured" class="custom-control-label">Featured</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                                <label for="is_active" class="custom-control-label">Active</label>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Create Recruiter</button>
                        <a href="{{ route('admin::recruiters.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
