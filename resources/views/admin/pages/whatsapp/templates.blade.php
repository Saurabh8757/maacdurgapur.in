@extends('admin.layout.admin_layout')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">WhatsApp Templates</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Add/Edit Template</h3>
                        </div>
                        <form action="{{ route('admin::whatsapp.templates.save') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Template Unique Name *</label>
                                    <input type="text" name="template_name" class="form-control" required placeholder="e.g. LeadAcknowledgement">
                                </div>
                                <div class="form-group">
                                    <label>Template Type *</label>
                                    <select name="template_type" class="form-control" required>
                                        <option value="Lead Acknowledgement">Lead Acknowledgement</option>
                                        <option value="Followup Reminder">Followup Reminder</option>
                                        <option value="General">General</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Content *</label>
                                    <textarea name="content" class="form-control" rows="5" required placeholder="Hi @{{name}}, ..."></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Active</label>
                                    <select name="is_active" class="form-control" required>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Save Template</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Content snippet</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($templates as $t)
                                    <tr>
                                        <td>{{ $t->template_name }}</td>
                                        <td>{{ $t->template_type }}</td>
                                        <td>{{ Str::limit($t->content, 40) }}</td>
                                        <td>
                                            @if($t->is_active)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No templates defined.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
