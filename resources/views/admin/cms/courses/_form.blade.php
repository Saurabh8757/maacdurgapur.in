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
        <label for="thumbnail_media_id">Thumbnail media ID</label>
        <input class="form-control" type="number" min="1" id="thumbnail_media_id" name="thumbnail_media_id" value="{{ old('thumbnail_media_id', optional($course ?? null)->thumbnail_media_id) }}">
        <div class="cms-help mt-1">Optional. Uses an existing media asset; no upload is performed here.</div>
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
