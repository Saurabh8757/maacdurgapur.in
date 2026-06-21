@include('admin.cms.partials.validation-errors')
<div class="row">
    <div class="col-md-6 form-group">
        <label for="title">Project title</label>
        <input class="form-control" id="title" name="title" value="{{ old('title', optional($project ?? null)->title) }}">
    </div>
    <div class="col-md-3 form-group">
        <label for="slug">Slug</label>
        <input class="form-control" id="slug" name="slug" value="{{ old('slug', optional($project ?? null)->slug) }}">
    </div>
    <div class="col-md-3 form-group">
        <label for="student_name">Student name</label>
        <input class="form-control" id="student_name" name="student_name" value="{{ old('student_name', optional($project ?? null)->student_name) }}">
    </div>
</div>
<div class="form-group">
    <label for="cms_showcase_category_id">Category</label>
    <select class="form-control" id="cms_showcase_category_id" name="cms_showcase_category_id">
        <option value="">Select a category</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected((string) old('cms_showcase_category_id', optional($project ?? null)->cms_showcase_category_id) === (string) $category->id)>{{ $category->name }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="short_description">Short description</label>
    <textarea class="form-control" id="short_description" name="short_description" rows="6">{{ old('short_description', optional($project ?? null)->short_description) }}</textarea>
</div>
<div class="row">
    <div class="col-md-6 form-group">
        <label for="thumbnail_media_id">Thumbnail media ID</label>
        <input class="form-control" type="number" min="1" id="thumbnail_media_id" name="thumbnail_media_id" value="{{ old('thumbnail_media_id', optional($project ?? null)->thumbnail_media_id) }}">
        <div class="cms-help mt-1">Optional. Uses an existing media asset.</div>
    </div>
    <div class="col-md-6 form-group">
        <label for="software_icon_media_id">Software Icon media ID</label>
        <input class="form-control" type="number" min="1" id="software_icon_media_id" name="software_icon_media_id" value="{{ old('software_icon_media_id', optional($project ?? null)->software_icon_media_id) }}">
        <div class="cms-help mt-1">Optional. Uses an existing media asset.</div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 form-group">
        <label for="status">Status</label>
        <select class="form-control" id="status" name="status">
            @foreach (['draft' => 'Draft', 'published' => 'Published'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', optional($project ?? null)->status ?? 'draft') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 form-group">
        <label for="sort_order">Sort order</label>
        <input class="form-control" type="number" min="0" id="sort_order" name="sort_order" value="{{ old('sort_order', optional($project ?? null)->sort_order ?? 0) }}">
    </div>
</div>
