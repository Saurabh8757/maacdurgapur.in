@extends('admin.layout.admin_layout')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Lead Forms</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-header">
                    <form method="GET" action="{{ route('admin::lead_forms.index') }}" class="form-inline">
                        <label class="mr-2">Filter by Brand:</label>
                        <select name="brand_id" class="form-control mr-2" onchange="this.form.submit()">
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ $brand_id == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        <label class="mr-2 ml-3">Form Type:</label>
                        <select name="form_type" class="form-control mr-2" onchange="this.form.submit()">
                            <option value="hero" {{ $form_type == 'hero' ? 'selected' : '' }}>Hero Form</option>
                            <option value="global_modal" {{ $form_type == 'global_modal' ? 'selected' : '' }}>Global Modal</option>
                        </select>
                    </form>
                    <div class="card-tools">
                        <a href="{{ route('admin::lead_forms.create', ['brand_id' => $brand_id, 'form_type' => $form_type]) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add Field
                        </a>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Label</th>
                                <th>Field Name</th>
                                <th>Form Type</th>
                                <th>Type</th>
                                <th>Required</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fields as $field)
                                <tr>
                                    <td>{{ $field->sort_order }}</td>
                                    <td>{{ $field->label }}</td>
                                    <td><code>{{ $field->field_name }}</code></td>
                                    <td>
                                    @if($field->form_type === 'global_modal')
                                        <span class="badge badge-info">Global Modal</span>
                                    @else
                                        <span class="badge badge-secondary">Hero</span>
                                    @endif
                                </td>
                                <td>{{ ucfirst($field->type) }}</td>
                                    <td>
                                        @if($field->is_required)
                                            <span class="badge badge-danger">Required</span>
                                        @else
                                            <span class="badge badge-secondary">Optional</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($field->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Disabled</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin::lead_forms.edit', $field->id) }}" class="btn btn-info btn-sm">Edit</a>
                                        <form action="{{ route('admin::lead_forms.destroy', $field->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this field?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No fields configured for this brand.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
