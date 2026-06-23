@include('admin.cms.partials.validation-errors')
<div class="row">
    <div class="col-md-8 form-group">
        <label for="title">Title</label>
        <input class="form-control" id="title" name="title" value="{{ old('title', optional($course ?? null)->title) }}">
    </div>
    <div class="col-md-4 form-group">
        <label for="slug">Slug</label>
        <input class="form-control" id="slug" name="slug" value="{{ old('slug', optional($course ?? null)->slug) }}">
        <div class="cms-help mt-1">Leave blank to auto-generate from title.</div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 form-group">
        <label for="course_category">Category</label>
        <input class="form-control" id="course_category" name="course_category" value="{{ old('course_category', optional($course ?? null)->course_category) }}" placeholder="e.g. Design, Development, Marketing">
    </div>
    <div class="col-md-6 form-group">
        <label for="outcome">Outcome</label>
        <input class="form-control" id="outcome" name="outcome" value="{{ old('outcome', optional($course ?? null)->outcome) }}" placeholder="e.g. Graphic Designer">
    </div>
</div>
<div class="form-group">
    <label for="short_description">Short Description</label>
    <textarea class="form-control" id="short_description" name="short_description" rows="3">{{ old('short_description', optional($course ?? null)->short_description) }}</textarea>
</div>
<div class="form-group">
    <label for="skills">Skills</label>
    <textarea class="form-control" id="skills" name="skills[]" rows="3" data-array-field>{{ old('skills', implode(', ', optional($course ?? null)->skills ?? [])) }}</textarea>
    <div class="cms-help mt-1">Enter one skill per line or separate with commas.</div>
</div>
<div class="row">
    <div class="col-md-4 form-group d-none">
        <label for="featured_image_media_id">Featured Image Media ID</label>
        <input class="form-control" type="number" min="1" id="featured_image_media_id" name="featured_image_media_id" value="{{ old('featured_image_media_id', optional($course ?? null)->featured_image_media_id) }}">
    </div>
    <div class="col-md-4 form-group">
        <label for="is_featured">Featured</label>
        <select class="form-control" id="is_featured" name="is_featured">
            <option value="1" @selected(old('is_featured', optional($course ?? null)->is_featured ?? false) == 1)>Yes</option>
            <option value="0" @selected(old('is_featured', optional($course ?? null)->is_featured ?? false) == 0)>No</option>
        </select>
    </div>
    <div class="col-md-4 form-group">
        <label for="is_active">Status</label>
        <select class="form-control" id="is_active" name="is_active">
            <option value="1" @selected(old('is_active', optional($course ?? null)->is_active ?? true) == 1)>Active</option>
            <option value="0" @selected(old('is_active', optional($course ?? null)->is_active ?? true) == 0)>Inactive</option>
        </select>
    </div>
</div>
