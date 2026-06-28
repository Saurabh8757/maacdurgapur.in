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
        <label for="icon_file">Upload Icon</label>
        <input class="form-control" type="file" id="icon_file" name="icon_file" accept="image/*">
        @if(isset($feature) && $feature->icon)
            <div class="mt-2">
                <img src="{{ $feature->icon->url }}" alt="Current Icon" style="max-height: 50px;">
            </div>
        @endif
        <div class="cms-help mt-1">Optional. Upload an image for the feature icon.</div>
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
