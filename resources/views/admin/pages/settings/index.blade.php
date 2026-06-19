@extends('admin.layout.admin_layout')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/dist/css/settings.css') }}">
@endpush

@section('content')
<div class="content-wrapper settings-page">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1>{{ $scope->isBrand() ? 'Brand Settings' : 'Global Settings' }}</h1>
                    <p class="settings-page-subtitle">Read-only configuration catalogue</p>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin::dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Settings</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @include('admin.pages.settings.partials.scope-header')

            <div class="settings-layout">
                @include('admin.pages.settings.partials.group-navigation')

                <div class="settings-definitions">
                    @if ($selected_group)
                        <div class="settings-group-header">
                            <div>
                                <span class="settings-group-eyebrow">Configuration group</span>
                                <h2>{{ $selected_group->name }}</h2>
                                @if ($selected_group->description)
                                    <p>{{ $selected_group->description }}</p>
                                @endif
                            </div>
                            <span class="settings-count">{{ $definitions->count() }} definitions</span>
                        </div>
                    @endif

                    @forelse ($definitions as $item)
                        @include('admin.pages.settings.partials.definition-card', ['item' => $item])
                    @empty
                        <div class="settings-empty">
                            <i class="fas fa-sliders-h"></i>
                            <h3>No settings in this group</h3>
                            <p>No definitions are currently available for the selected scope.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
