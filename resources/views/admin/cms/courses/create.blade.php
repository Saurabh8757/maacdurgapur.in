@extends('admin.layout.admin_layout')
@include('admin.cms.partials.page-assets')

@section('content')
<div class="content-wrapper cms-page">
    <section class="content-header"><div class="container-fluid"><h1>Create Course</h1></div></section>
    <section class="content"><div class="container-fluid"><div class="card cms-card cms-form-card">
        <form data-cms-form data-endpoint="{{ url('v1/cpanel/admin/cms/courses') }}" data-method="POST" data-redirect="{{ route('admin::content.courses.index') }}" novalidate>
            <div class="card-body">@include('admin.cms.courses._form')</div>
            <div class="card-footer cms-form-actions"><a href="{{ route('admin::content.courses.index') }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Create Course</button></div>
        </form>
    </div></div></section>
</div>
@endsection
