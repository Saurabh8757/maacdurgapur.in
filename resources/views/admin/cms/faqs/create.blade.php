@extends('admin.layout.admin_layout')
@include('admin.cms.partials.page-assets')

@section('content')
<div class="content-wrapper cms-page">
    <section class="content-header"><div class="container-fluid"><h1>Create FAQ</h1></div></section>
    <section class="content"><div class="container-fluid"><div class="card cms-card cms-form-card">
        @if ($categories->isEmpty())
            <div class="card-body">
                <div class="alert alert-warning mb-0">
                    <h5><i class="fas fa-exclamation-triangle mr-1"></i> No categories available</h5>
                    <p class="mb-3">Create an FAQ category before adding an FAQ.</p>
                    <a href="{{ route('admin::content.faq-categories.create') }}" class="btn btn-warning">
                        <i class="fas fa-folder-plus mr-1"></i> Create FAQ Category
                    </a>
                </div>
            </div>
            <div class="card-footer cms-form-actions">
                <a href="{{ route('admin::content.faqs.index') }}" class="btn btn-outline-secondary">Back to FAQs</a>
            </div>
        @else
            <form data-cms-form data-endpoint="{{ url('v1/cpanel/admin/cms/faqs') }}" data-method="POST" data-redirect="{{ route('admin::content.faqs.index') }}" novalidate>
                <div class="card-body">@include('admin.cms.faqs._form')</div>
                <div class="card-footer cms-form-actions">
                    <a href="{{ route('admin::content.faqs.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Create FAQ</button>
                </div>
            </form>
        @endif
    </div></div></section>
</div>
@endsection
