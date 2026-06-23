@include('admin.cms.partials.validation-errors')
<div class="row">
    <div class="col-md-8 form-group">
        <label for="title">Title</label>
        <input class="form-control" id="title" name="title" value="{{ old('title', optional($course ?? null)->title) }}">
    </div>
    <div class="col-md-4 form-group">
        <label for="slug">Slug</label>
        <input class="form-control" id="slug" name="slug" value="{{ old('slug', optional($course ?? null)->slug) }}">
    </div>
</div>
<div class="form-group">
    <label for="description">Description</label>
    <textarea class="form-control" id="description" name="description" rows="7">{{ old('description', optional($course ?? null)->description) }}</textarea>
</div>
<div class="form-group">
    <label for="tools_covered">Tools covered</label>
    <textarea class="form-control" id="tools_covered" name="tools_covered[]" rows="3" data-array-field>{{ old('tools_covered', implode(', ', optional($course ?? null)->tools_covered ?? [])) }}</textarea>
    <div class="cms-help mt-1">Enter one tool per line or separate tools with commas.</div>
</div>
<div class="row">
    <div class="col-md-4 form-group">
        <label for="thumbnail">Thumbnail Image</label>
        <div class="custom-file">
            <input type="file" name="thumbnail" class="custom-file-input" id="thumbnail" accept="image/*">
            <label class="custom-file-label" for="thumbnail">Choose file</label>
        </div>
        @if(isset($course) && $course->thumbnail)
            <div class="mt-2" id="thumbnailPreview">
                <img src="{{ asset('storage/' . $course->thumbnail->storage_key) }}" style="max-height: 80px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);">
            </div>
        @else
            <div class="mt-2" id="thumbnailPreview"></div>
        @endif
        <div class="cms-help mt-1">Optional. Upload a new image to replace the existing one.</div>
    </div>
    <div class="col-md-4 form-group">
        <label for="status">Status</label>
        <select class="form-control" id="status" name="status">
            @foreach (['active' => 'Active', 'inactive' => 'Inactive'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', optional($course ?? null)->status ?? 'active') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 form-group">
        <label for="sort_order">Sort order</label>
        <input class="form-control" type="number" min="0" id="sort_order" name="sort_order" value="{{ old('sort_order', optional($course ?? null)->sort_order ?? 0) }}">
    </div>
</div>

<script>
    const thumbnailInput = document.getElementById('thumbnail');
    if (thumbnailInput) {
        thumbnailInput.addEventListener('change', function(e) {
            let fileName = e.target.value.split('\\').pop();
            const label = e.target.nextElementSibling;
            if (label) {
                label.innerHTML = fileName || 'Choose file';
            }
            
            let previewContainer = document.getElementById('thumbnailPreview');
            if (e.target.files && e.target.files[0]) {
                let reader = new FileReader();
                reader.onload = function(evt) {
                    if(previewContainer) {
                        previewContainer.innerHTML = '<img src="'+evt.target.result+'" style="max-height: 80px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);">';
                    }
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    }
</script>
