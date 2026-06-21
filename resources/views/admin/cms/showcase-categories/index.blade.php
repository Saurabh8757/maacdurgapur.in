@extends('admin.layout.admin_layout')
@include('admin.cms.partials.page-assets')

@section('content')
<div class="content-wrapper cms-page">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6"><h1>Showcase Categories</h1></div>
                <div class="col-sm-6 text-sm-right">
                    @if ($permissions['create'])
                        <a href="{{ route('admin::content.showcase-categories.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-1"></i> Add Category
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            @include('admin.layout.message')
            <div class="card cms-card">
                <div class="card-header">
                    <form class="cms-toolbar" method="GET">
                        <div class="cms-search">
                            <input class="form-control" type="search" name="q" value="{{ $search }}" placeholder="Search name or slug">
                            <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i> Search</button>
                            @if ($search !== '')
                                <a class="btn btn-outline-secondary" href="{{ route('admin::content.showcase-categories.index') }}">Clear</a>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr><th>Name</th><th>Slug</th><th>Status</th><th>Sort Order</th><th>Created</th><th>Actions</th></tr>
                        </thead>
                        <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td><strong>{{ $category->name }}</strong></td>
                                <td><code>{{ $category->slug }}</code></td>
                                <td><span class="badge badge-{{ $category->status === 'active' ? 'success' : 'secondary' }}">{{ $category->status }}</span></td>
                                <td>{{ $category->sort_order }}</td>
                                <td>{{ optional($category->created_at)->format('d M Y') }}</td>
                                <td>
                                    <div class="cms-actions">
                                        @if ($permissions['edit'])
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin::content.showcase-categories.edit', $category->id) }}"><i class="fas fa-edit"></i> Edit</a>
                                        @endif
                                        @if ($permissions['delete'] && $category->projects_count === 0)
                                            <form method="POST" action="{{ route('admin::content.showcase-categories.destroy', $category->id) }}" class="d-inline" onsubmit="return confirm('Delete this showcase category?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" type="submit"><i class="fas fa-trash"></i> Delete</button>
                                            </form>
                                        @elseif ($permissions['delete'])
                                            <button class="btn btn-sm btn-outline-secondary" type="button" disabled title="Move or delete the category projects first"><i class="fas fa-lock"></i> In use</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="cms-empty">No showcase categories found for the active brand.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($categories->hasPages())
                    <div class="card-footer">{{ $categories->links('pagination::bootstrap-4') }}</div>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
