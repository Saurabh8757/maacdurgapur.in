@extends('admin.layout.admin_layout')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Lead Form Field</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <form method="POST" action="{{ route('admin::lead_forms.update', $field->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label>Brand</label>
                            <select name="brand_id" class="form-control" required>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ $field->brand_id == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Label</label>
                            <input type="text" name="label" class="form-control" value="{{ $field->label }}" required>
                        </div>
                        <div class="form-group">
                            <label>Field Name (used for 'name' attribute)</label>
                            <input type="text" name="field_name" class="form-control" value="{{ $field->field_name }}" required pattern="^[a-zA-Z0-9_]+$">
                            <small class="text-muted">Only letters, numbers, and underscores.</small>
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <select name="type" class="form-control" required id="fieldTypeSelect">
                                <option value="text" {{ $field->type == 'text' ? 'selected' : '' }}>Text</option>
                                <option value="email" {{ $field->type == 'email' ? 'selected' : '' }}>Email</option>
                                <option value="phone" {{ $field->type == 'phone' ? 'selected' : '' }}>Phone</option>
                                <option value="select" {{ $field->type == 'select' ? 'selected' : '' }}>Select</option>
                                <option value="textarea" {{ $field->type == 'textarea' ? 'selected' : '' }}>Textarea</option>
                                <option value="checkbox" {{ $field->type == 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Placeholder</label>
                            <input type="text" name="placeholder" class="form-control" value="{{ $field->placeholder }}">
                        </div>
                        <div class="form-group" id="optionsGroup" style="display: {{ $field->type == 'select' ? 'block' : 'none' }};">
                            <label>Options (For Select fields)</label>
                            <input type="text" name="options" class="form-control" value="{{ $optionsString }}">
                            <small class="text-muted">Comma separated values.</small>
                        </div>
                        <div class="form-group">
                            <label>Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ $field->sort_order }}" required>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="is_required" id="reqCheck" value="1" {{ $field->is_required ? 'checked' : '' }}>
                            <label class="form-check-label" for="reqCheck">Required Field</label>
                        </div>
                        <div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input" name="is_active" id="actCheck" value="1" {{ $field->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="actCheck">Active</label>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update Field</button>
                        <a href="{{ route('admin::lead_forms.index', ['brand_id' => $field->brand_id]) }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('fieldTypeSelect');
    const optionsGroup = document.getElementById('optionsGroup');
    
    function toggleOptions() {
        if (typeSelect.value === 'select') {
            optionsGroup.style.display = 'block';
        } else {
            optionsGroup.style.display = 'none';
        }
    }
    
    typeSelect.addEventListener('change', toggleOptions);
});
</script>
@endsection
