@extends('admin.layout.admin_layout')
@include('admin.cms.partials.page-assets')

@section('content')
<div class="content-wrapper cms-page">
    <section class="content-header"><div class="container-fluid"><div class="row align-items-center">
        <div class="col-sm-6"><h1>Showcase</h1></div>
        <div class="col-sm-6 text-sm-right">@if ($permissions['create'])<a href="{{ route('admin::content.showcase.create') }}" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Add Project</a>@endif</div>
    </div></div></section>
    <section class="content"><div class="container-fluid"><div class="card cms-card">
        <div class="card-header"><form class="cms-toolbar" method="GET"><div class="cms-search">
            <input class="form-control" type="search" name="q" value="{{ $search }}" placeholder="Search project, student or category">
            <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i> Search</button>
            @if ($search !== '')<a class="btn btn-outline-secondary" href="{{ route('admin::content.showcase.index') }}">Clear</a>@endif
        </div></form></div>
        <div class="card-body p-0 table-responsive"><table class="table table-hover mb-0">
            <thead><tr><th>Project</th><th>Category</th><th>Status</th><th>Sort</th><th>Created</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse ($projects as $project)
                <tr>
                    <td class="cms-title-cell"><strong>{{ $project->title }}</strong><br><small class="text-muted">{{ $project->student_name }}</small></td>
                    <td>{{ optional($project->category)->name ?: '—' }}</td>
                    <td><span class="badge badge-{{ $project->status === 'published' ? 'success' : 'warning' }}">{{ $project->status }}</span></td>
                    <td>{{ $project->sort_order }}</td>
                    <td>{{ optional($project->created_at)->format('d M Y') }}</td>
                    <td><div class="cms-actions">
                        @if ($permissions['edit'])<a class="btn btn-sm btn-outline-primary" href="{{ route('admin::content.showcase.edit', $project->id) }}"><i class="fas fa-edit"></i> Edit</a>@endif
                        @if ($permissions['publish'] && $project->status !== 'published')<button class="btn btn-sm btn-outline-success" type="button" data-cms-publish data-endpoint="{{ url('v1/cpanel/admin/cms/showcase-projects/'.$project->id.'/publish') }}"><i class="fas fa-upload"></i> Publish</button>@endif
                        @if ($permissions['delete'])<button class="btn btn-sm btn-outline-danger" type="button" data-cms-delete data-endpoint="{{ url('v1/cpanel/admin/cms/showcase-projects/'.$project->id) }}"><i class="fas fa-trash"></i> Delete</button>@endif
                    </div></td>
                </tr>
            @empty
                <tr><td colspan="6" class="cms-empty">No showcase projects found for the active brand.</td></tr>
            @endforelse
            </tbody>
        </table></div>
        @if ($projects->hasPages())<div class="card-footer">{{ $projects->links('pagination::bootstrap-4') }}</div>@endif
    </div></div></section>
</div>
@endsection
