@include('admin.cms.partials.validation-errors')
<div class="row">
    <div class="col-md-8 form-group">
        <label for="title">Title</label>
        <input class="form-control" id="title" name="title" value="{{ old('title', optional($feature ?? null)->title) }}">
    </div>
    <div class="col-md-4 form-group">
        <label for="slug">Slug</label>
        <input class="form-control" id="slug" name="slug" value="{{ old('slug', optional($feature ?? null)->slug) }}">
    </div>
</div>
<div class="form-group">
    <label for="description">Description</label>
    <textarea class="form-control" id="description" name="description" rows="7">{{ old('description', optional($feature ?? null)->description) }}</textarea>
</div>
<div class="row">
    <div class="col-md-4 form-group">
        <label for="icon_media_id">Icon media ID</label>
        <input class="form-control" type="number" min="1" id="icon_media_id" name="icon_media_id" value="{{ old('icon_media_id', optional($feature ?? null)->icon_media_id) }}">
        <div class="cms-help mt-1">Optional. Uses an existing media asset.</div>
    </div>
    <div class="col-md-4 form-group">
        <label for="status">Status</label>
        <select class="form-control" id="status" name="status">
            @foreach (['active' => 'Active', 'inactive' => 'Inactive'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', optional($feature ?? null)->status ?? 'active') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 form-group">
        <label for="sort_order">Sort order</label>
        <input class="form-control" type="number" min="0" id="sort_order" name="sort_order" value="{{ old('sort_order', optional($feature ?? null)->sort_order ?? 0) }}">
    </div>
</div>
