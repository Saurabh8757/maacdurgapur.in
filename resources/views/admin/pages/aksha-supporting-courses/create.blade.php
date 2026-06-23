@extends('admin.layout.admin_layout')
@include('admin.cms.partials.page-assets')

@section('title', 'Add Supporting Course')

@section('content')
<div class="content-wrapper cms-page">
    <section class="content-header"><div class="container-fluid"><h1>Add Supporting Course</h1></div></section>
    <section class="content"><div class="container-fluid"><div class="card cms-card cms-form-card">
        <form data-cms-form data-endpoint="{{ route('admin::aksha.supporting_courses.store') }}" data-method="POST" data-redirect="{{ route('admin::content.aksha.supporting-courses.index') }}" novalidate>
            <div class="card-body">
                @include('admin.pages.aksha-supporting-courses._form')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" data-showcase-thumbnail-uploader>
                            <label for="showcase_thumbnail_upload">Featured Image</label>
                            <input class="form-control-file" type="file" id="showcase_thumbnail_upload" accept="image/jpeg,image/png,image/webp,image/gif">
                            <div class="cms-help mt-1">JPG, PNG, WebP or GIF. The image uploads immediately to Media Library.</div>
                            <div class="mt-3 d-none" data-thumbnail-preview-wrap>
                                <img data-thumbnail-preview alt="Thumbnail preview" style="display:block; width:100%; max-width:420px; max-height:260px; object-fit:cover; border-radius:10px; border:1px solid rgba(0,0,0,.12);">
                                <small class="text-success d-block mt-2" data-thumbnail-status></small>
                            </div>
                            <div class="text-danger mt-2 d-none" data-thumbnail-error></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer cms-form-actions"><a href="{{ route('admin::content.aksha.supporting-courses.index') }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Save Course</button></div>
        </form>
    </div></div></section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('[data-cms-form]');
    const mediaId = form.querySelector('[name="featured_image_media_id"]');
    const mediaField = mediaId.closest('.form-group');

    const fileInput = form.querySelector('#showcase_thumbnail_upload');
    const previewWrap = form.querySelector('[data-thumbnail-preview-wrap]');
    const preview = form.querySelector('[data-thumbnail-preview]');
    const status = form.querySelector('[data-thumbnail-status]');
    const error = form.querySelector('[data-thumbnail-error]');

    const submit = form.querySelector('[type="submit"]');

    if (mediaField) mediaField.classList.add('d-none');

    fileInput.addEventListener('change', async function () {
        const file = fileInput.files[0];
        if (!file) return;

        error.classList.add('d-none');
        status.textContent = 'Uploading…';
        preview.src = URL.createObjectURL(file);
        previewWrap.classList.remove('d-none');
        fileInput.disabled = true;
        submit.disabled = true;

        const body = new FormData();
        body.append('thumbnail', file);

        try {
            const response = await fetch(@json(route('admin::cms.showcase_media.store')), {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: body
            });
            const payload = await response.json();
            if (!response.ok) throw new Error(payload.message || 'Image upload failed.');

            mediaId.value = payload.id;
            preview.src = payload.url;
            status.textContent = 'Image uploaded and attached successfully.';
        } catch (uploadError) {
            mediaId.value = '';
            status.textContent = '';
            error.textContent = uploadError.message;
            error.classList.remove('d-none');
        } finally {
            fileInput.disabled = false;
            submit.disabled = false;
        }
    });
});
</script>
@endpush
