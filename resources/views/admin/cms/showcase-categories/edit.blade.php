@extends('admin.layout.admin_layout')
@include('admin.cms.partials.page-assets')

@section('content')
<div class="content-wrapper cms-page">
    <section class="content-header"><div class="container-fluid"><h1>Edit Showcase Category</h1></div></section>
    <section class="content"><div class="container-fluid"><div class="card cms-card cms-form-card">
        <form method="POST" action="{{ route('admin::content.showcase-categories.update', $category->id) }}">
            @csrf
            @method('PUT')
            <div class="card-body">@include('admin.cms.showcase-categories._form')</div>
            <div class="card-footer cms-form-actions">
                <a href="{{ route('admin::content.showcase-categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Save changes</button>
            </div>
        </form>
    </div></div></section>
</div>
@endsection
