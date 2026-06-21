@include('admin.cms.partials.validation-errors')
<div class="row">
    <div class="col-md-8 form-group">
        <label for="name">Name</label>
        <input class="form-control" id="name" name="name" value="{{ old('name', optional($category ?? null)->name) }}" required>
    </div>
    <div class="col-md-4 form-group">
        <label for="slug">Slug</label>
        <input class="form-control" id="slug" name="slug" value="{{ old('slug', optional($category ?? null)->slug) }}" required>
    </div>
</div>
<div class="row">
    <div class="col-md-6 form-group">
        <label for="status">Status</label>
        <select class="form-control" id="status" name="status" required>
            @foreach (['active' => 'Active', 'inactive' => 'Inactive'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', optional($category ?? null)->status ?? 'active') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 form-group">
        <label for="sort_order">Sort order</label>
        <input class="form-control" type="number" min="0" id="sort_order" name="sort_order" value="{{ old('sort_order', optional($category ?? null)->sort_order ?? 0) }}" required>
    </div>
</div>
