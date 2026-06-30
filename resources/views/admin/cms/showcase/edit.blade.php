@extends('admin.layout.admin_layout')
@include('admin.cms.partials.page-assets')

@section('content')
<div class="content-wrapper cms-page">
    <section class="content-header"><div class="container-fluid"><h1>Edit Showcase Project</h1></div></section>
    <section class="content"><div class="container-fluid"><div class="card cms-card cms-form-card">
        <form data-cms-form data-endpoint="{{ url('v1/cpanel/admin/cms/showcase-projects/'.$project->id) }}" data-method="PUT" data-redirect="{{ route('admin::content.showcase.index') }}" novalidate>
            <div class="card-body">
                @include('admin.cms.showcase._form')
                <div class="row">
                    <div class="col-md-6">
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
                    <div class="col-md-6">
                        <div class="form-group" data-showcase-software-icon-uploader>
                            <label for="showcase_software_icon_upload">Software Icon</label>
                            <input class="form-control-file" type="file" id="showcase_software_icon_upload" accept="image/jpeg,image/png,image/webp,image/gif,image/svg+xml">
                            <div class="cms-help mt-1">Choose a new icon to replace the current one. JPG, PNG, SVG or WebP.</div>
                            <div class="mt-3 @if(!$project->softwareIcon) d-none @endif" data-software-icon-preview-wrap>
                                <img data-software-icon-preview src="{{ $project->softwareIcon ? asset($project->softwareIcon->storage_key) : '' }}" alt="{{ $project->title }} software icon preview" style="display:block; width:64px; height:64px; object-fit:contain; background:rgba(255,255,255,0.1); padding:5px; border-radius:10px; border:1px solid rgba(0,0,0,.12);">
                                <small class="text-success d-block mt-2" data-software-icon-status>{{ $project->softwareIcon ? 'Current icon' : '' }}</small>
                            </div>
                            <div class="text-danger mt-2 d-none" data-software-icon-error></div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    @foreach([2 => 'thumbnail2', 3 => 'thumbnail3', 4 => 'thumbnail4', 5 => 'thumbnail5'] as $num => $rel)
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="showcase_thumbnail_upload_{{ $num }}">Thumbnail image {{ $num }}</label>
                            <input class="form-control-file" type="file" id="showcase_thumbnail_upload_{{ $num }}" accept="image/jpeg,image/png,image/webp,image/gif" data-upload-url="{{ route('admin::cms.showcase_media.store') }}" data-target-input="thumbnail_media_id_{{ $num }}">
                            <div class="mt-2 @if(!$project->$rel) d-none @endif" id="preview_wrap_{{ $num }}">
                                <img id="preview_{{ $num }}" src="{{ $project->$rel ? asset($project->$rel->storage_key) : '' }}" style="width:100%; object-fit:cover; border-radius:5px;">
                                <small class="text-success d-block" id="status_{{ $num }}">{{ $project->$rel ? 'Current thumbnail' : '' }}</small>
                            </div>
                            <div class="text-danger mt-1 d-none" id="error_{{ $num }}"></div>
                        </div>
                    </div>
                    @endforeach
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
    const iconMediaId = form.querySelector('[name="software_icon_media_id"]');
    const iconMediaField = iconMediaId.closest('.form-group');

    const fileInput = form.querySelector('#showcase_thumbnail_upload');
    const previewWrap = form.querySelector('[data-thumbnail-preview-wrap]');
    const preview = form.querySelector('[data-thumbnail-preview]');
    const status = form.querySelector('[data-thumbnail-status]');
    const error = form.querySelector('[data-thumbnail-error]');

    const iconFileInput = form.querySelector('#showcase_software_icon_upload');
    const iconPreviewWrap = form.querySelector('[data-software-icon-preview-wrap]');
    const iconPreview = form.querySelector('[data-software-icon-preview]');
    const iconStatus = form.querySelector('[data-software-icon-status]');
    const iconError = form.querySelector('[data-software-icon-error]');

    const submit = form.querySelector('[type="submit"]');

    mediaField.classList.add('d-none');
    iconMediaField.classList.add('d-none');

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

    [2, 3, 4, 5].forEach(num => {
        const input = document.getElementById('showcase_thumbnail_upload_' + num);
        if (!input) return;
        input.addEventListener('change', async function () {
            const file = input.files[0];
            if (!file) return;

            const targetInputId = input.getAttribute('data-target-input');
            const targetInput = form.querySelector('[name="' + targetInputId + '"]');
            const previewWrap = document.getElementById('preview_wrap_' + num);
            const preview = document.getElementById('preview_' + num);
            const statusLabel = document.getElementById('status_' + num);
            const errorLabel = document.getElementById('error_' + num);

            errorLabel.classList.add('d-none');
            statusLabel.textContent = 'Uploading…';
            preview.src = URL.createObjectURL(file);
            previewWrap.classList.remove('d-none');
            input.disabled = true;
            submit.disabled = true;

            const body = new FormData();
            body.append('thumbnail', file);

            try {
                const response = await fetch(input.getAttribute('data-upload-url'), {
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
                if (!response.ok) throw new Error(payload.message || 'Upload failed.');

                targetInput.value = payload.id;
                preview.src = payload.url;
                statusLabel.textContent = 'Uploaded.';
            } catch (uploadError) {
                targetInput.value = '';
                statusLabel.textContent = '';
                errorLabel.textContent = uploadError.message;
                errorLabel.classList.remove('d-none');
            } finally {
                input.disabled = false;
                submit.disabled = false;
            }
        });
    });

    iconFileInput.addEventListener('change', async function () {
        const file = iconFileInput.files[0];
        if (!file) return;

        iconError.classList.add('d-none');
        iconStatus.textContent = 'Uploading…';
        iconPreview.src = URL.createObjectURL(file);
        iconPreviewWrap.classList.remove('d-none');
        iconFileInput.disabled = true;
        submit.disabled = true;

        const body = new FormData();
        body.append('thumbnail', file); // Reusing the exact same endpoint

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
            if (!response.ok) throw new Error(payload.message || 'Icon upload failed.');

            iconMediaId.value = payload.id;
            iconPreview.src = payload.url;
            iconStatus.textContent = 'Icon uploaded and attached.';
        } catch (uploadError) {
            iconStatus.textContent = iconMediaId.value ? 'Current icon retained.' : '';
            iconError.textContent = uploadError.message;
            iconError.classList.remove('d-none');
        } finally {
            iconFileInput.disabled = false;
            submit.disabled = false;
        }
    });
});
</script>
@endpush
