@extends('admin.layout.admin_layout')
@section('title', 'Add Placement Showcase')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add Placement Showcase</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin::placement-showcases.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <form action="{{ route('admin::placement-showcases.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Student Name <span class="text-danger">*</span></label>
                                <input type="text" name="student_name" class="form-control" required value="{{ old('student_name') }}" placeholder="e.g. Priya S.">
                                @error('student_name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label>Designation / Job Role <span class="text-danger">*</span></label>
                                <input type="text" name="designation" class="form-control" required value="{{ old('designation') }}" placeholder="e.g. VFX Artist">
                                @error('designation') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label>Company Name</label>
                                <div class="input-group">
                                    <select name="company_id" class="form-control">
                                        <option value="">-- Select Existing Company --</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <small class="text-muted">OR type a new company below:</small>
                                <input type="text" name="company_name" class="form-control mt-2" value="{{ old('company_name') }}" placeholder="New Company Name">
                            </div>

                            <div class="col-md-6 form-group">
                                <label>Annual Package (Numbers only) <span class="text-danger">*</span></label>
                                <input type="number" name="annual_package" class="form-control" required value="{{ old('annual_package') }}" placeholder="e.g. 379000">
                                <small class="text-muted">Frontend will format as ₹3,79,000 PER ANNUM</small>
                                @error('annual_package') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label>Student Photo <span class="text-danger">*</span></label>
                                <div class="custom-file">
                                    <input type="file" name="student_image" class="custom-file-input" id="studentImage" required accept="image/*">
                                    <label class="custom-file-label" for="studentImage">Choose file</label>
                                </div>
                                <small class="text-muted">Recommended aspect ratio 1:1 or 4:5</small>
                                @error('student_image') <small class="text-danger">{{ $message }}</small> @enderror
                                <div class="mt-2" id="studentImagePreview"></div>
                            </div>

                            <div class="col-md-6 form-group">
                                <label>Company Logo (Optional)</label>
                                <div class="custom-file">
                                    <input type="file" name="company_logo" class="custom-file-input" id="companyLogo" accept="image/*">
                                    <label class="custom-file-label" for="companyLogo">Choose file</label>
                                </div>
                                @error('company_logo') <small class="text-danger">{{ $message }}</small> @enderror
                                <div class="mt-2" id="companyLogoPreview"></div>
                            </div>

                            <div class="col-md-3 form-group">
                                <div class="custom-control custom-switch mt-4">
                                    <input type="checkbox" name="is_featured" class="custom-control-input" id="is_featured" value="1" checked>
                                    <label class="custom-control-label" for="is_featured">Featured Placement</label>
                                </div>
                            </div>

                            <div class="col-md-3 form-group">
                                <div class="custom-control custom-switch mt-4">
                                    <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1" checked>
                                    <label class="custom-control-label" for="is_active">Active (Visible)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Save Placement</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    // File input label change and preview
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);

        // Preview
        let previewId = $(this).attr('id') + 'Preview';
        if (this.files && this.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#' + previewId).html('<img src="'+e.target.result+'" style="max-height: 100px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);">');
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endsection
