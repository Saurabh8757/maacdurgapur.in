@extends('admin.layout.admin_layout')
@include('admin.cms.partials.page-assets')

@section('content')
<div class="content-wrapper cms-page">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6"><h1>FAQs</h1></div>
                <div class="col-sm-6 text-sm-right">
                    @if ($permissions['create'])
                        <a href="{{ route('admin::content.faqs.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-1"></i> Add FAQ
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card cms-card">
                <div class="card-header">
                    <form class="cms-toolbar" method="GET">
                        <div class="cms-search">
                            <input class="form-control" type="search" name="q" value="{{ $search }}" placeholder="Search questions or categories">
                            <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i> Search</button>
                            @if ($search !== '')
                                <a class="btn btn-outline-secondary" href="{{ route('admin::content.faqs.index') }}">Clear</a>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>Question</th><th>Category</th><th>Status</th><th>Sort</th><th>Created</th><th>Actions</th></tr></thead>
                        <tbody>
                        @forelse ($faqs as $faq)
                            <tr>
                                <td class="cms-title-cell">{{ $faq->question }}</td>
                                <td>{{ optional($faq->category)->name ?: '—' }}</td>
                                <td><span class="badge badge-{{ $faq->status === 'active' ? 'success' : 'secondary' }}">{{ $faq->status }}</span></td>
                                <td>{{ $faq->sort_order }}</td>
                                <td>{{ optional($faq->created_at)->format('d M Y') }}</td>
                                <td><div class="cms-actions">
                                    @if ($permissions['edit'])
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin::content.faqs.edit', $faq->id) }}"><i class="fas fa-edit"></i> Edit</a>
                                    @endif
                                    @if ($permissions['delete'])
                                        <button class="btn btn-sm btn-outline-danger" type="button" data-cms-delete data-endpoint="{{ url('v1/cpanel/admin/cms/faqs/'.$faq->id) }}"><i class="fas fa-trash"></i> Delete</button>
                                    @endif
                                </div></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="cms-empty">No FAQs found for the active brand.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($faqs->hasPages())
                    <div class="card-footer">{{ $faqs->links('pagination::bootstrap-4') }}</div>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
