@extends('admin.layout.admin_layout')
@include('admin.cms.partials.page-assets')

@section('content')
<div class="content-wrapper cms-page">
    <section class="content-header"><div class="container-fluid"><h1>Edit Showcase Project</h1></div></section>
    <section class="content"><div class="container-fluid"><div class="card cms-card cms-form-card">
        <form data-cms-form data-endpoint="{{ url('v1/cpanel/admin/cms/showcase-projects/'.$project->id) }}" data-method="PUT" data-redirect="{{ route('admin::content.showcase.index') }}" novalidate>
            <div class="card-body">
                @include('admin.cms.showcase._form')
                <div class="form-group" data-showcase-thumbnail-uploader>
                    <label for="showcase_thumbnail_upload">Thumbnail image</label>
                    <input class="form-control-file" type="file" id="showcase_thumbnail_upload" accept="image/jpeg,image/png,image/webp,image/gif">
                    <div class="cms-help mt-1">Choose a new image to replace the current thumbnail. JPG, PNG, WebP or GIF, up to 10 MB.</div>
                    <div class="mt-3 @if(!$project->thumbnail) d-none @endif" data-thumbnail-preview-wrap>
                        <img data-thumbnail-preview src="{{ $project->thumbnail ? asset($project->thumbnail->storage_key) : '' }}" alt="{{ $project->title }} thumbnail preview" style="display:block; width:100%; max-width:420px; max-height:260px; object-fit:cover; border-radius:10px; border:1px solid rgba(0,0,0,.12);">
                        <small class="text-success d-block mt-2" data-thumbnail-status>{{ $project->thumbnail ? 'Current thumbnail' : '' }}</small>
                    </div>
                    <div class="text-danger mt-2 d-none" data-thumbnail-error></div>
                </div>
            </div>
            <div class="card-footer cms-form-actions"><a href="{{ route('admin::content.showcase.index') }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Save changes</button></div>
        </form>
    </div></div></section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('[data-cms-form]');
    const mediaId = form.querySelector('[name="thumbnail_media_id"]');
    const mediaField = mediaId.closest('.form-group');
    const projectLink = form.querySelector('[name="video_url"]');
    const fileInput = form.querySelector('#showcase_thumbnail_upload');
    const previewWrap = form.querySelector('[data-thumbnail-preview-wrap]');
    const preview = form.querySelector('[data-thumbnail-preview]');
    const status = form.querySelector('[data-thumbnail-status]');
    const error = form.querySelector('[data-thumbnail-error]');
    const submit = form.querySelector('[type="submit"]');

    mediaField.classList.add('d-none');
    projectLink.closest('.form-group').querySelector('label').textContent = 'Project Link';
    projectLink.placeholder = 'https://youtube.com/... or https://behance.net/...';

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
            if (!response.ok) throw new Error(payload.message || 'Thumbnail upload failed.');

            mediaId.value = payload.id;
            preview.src = payload.url;
            status.textContent = 'Thumbnail uploaded and attached.';
        } catch (uploadError) {
            status.textContent = mediaId.value ? 'Current thumbnail retained.' : '';
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
