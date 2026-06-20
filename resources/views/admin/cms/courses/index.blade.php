@extends('admin.layout.admin_layout')
@include('admin.cms.partials.page-assets')

@section('content')
<div class="content-wrapper cms-page">
    <section class="content-header"><div class="container-fluid"><div class="row align-items-center">
        <div class="col-sm-6"><h1>Courses</h1></div>
        <div class="col-sm-6 text-sm-right">@if ($permissions['create'])<a href="{{ route('admin::content.courses.create') }}" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Add Course</a>@endif</div>
    </div></div></section>
    <section class="content"><div class="container-fluid"><div class="card cms-card">
        <div class="card-header"><form class="cms-toolbar" method="GET"><div class="cms-search">
            <input class="form-control" type="search" name="q" value="{{ $search }}" placeholder="Search title, slug or description">
            <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i> Search</button>
            @if ($search !== '')<a class="btn btn-outline-secondary" href="{{ route('admin::content.courses.index') }}">Clear</a>@endif
        </div></form></div>
        <div class="card-body p-0 table-responsive"><table class="table table-hover mb-0">
            <thead><tr><th>Course</th><th>Slug</th><th>Status</th><th>Sort</th><th>Created</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse ($courses as $course)
                <tr>
                    <td class="cms-title-cell"><strong>{{ $course->title }}</strong><br><small class="text-muted">{{ \Illuminate\Support\Str::limit($course->description, 90) }}</small></td>
                    <td><code>{{ $course->slug }}</code></td>
                    <td><span class="badge badge-{{ $course->status === 'active' ? 'success' : 'secondary' }}">{{ $course->status }}</span></td>
                    <td>{{ $course->sort_order }}</td>
                    <td>{{ optional($course->created_at)->format('d M Y') }}</td>
                    <td><div class="cms-actions">
                        @if ($permissions['edit'])<a class="btn btn-sm btn-outline-primary" href="{{ route('admin::content.courses.edit', $course->id) }}"><i class="fas fa-edit"></i> Edit</a>@endif
                        @if ($permissions['delete'])<button class="btn btn-sm btn-outline-danger" type="button" data-cms-delete data-endpoint="{{ url('v1/cpanel/admin/cms/courses/'.$course->id) }}"><i class="fas fa-trash"></i> Delete</button>@endif
                    </div></td>
                </tr>
            @empty
                <tr><td colspan="6" class="cms-empty">No courses found for the active brand.</td></tr>
            @endforelse
            </tbody>
        </table></div>
        @if ($courses->hasPages())<div class="card-footer">{{ $courses->links('pagination::bootstrap-4') }}</div>@endif
    </div></div></section>
</div>
@endsection
