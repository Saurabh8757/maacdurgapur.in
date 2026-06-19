@php
    $adminContextManager = app(\App\Services\Brands\BrandContextManager::class);
    $adminContext = $adminContextManager->adminContext();
    $accessibleBrands = Auth::check()
        ? app(\App\Services\Brands\AdminBrandAccessResolver::class)->accessibleBrands(Auth::user())
        : collect();
@endphp

@if ($adminContext && $accessibleBrands->isNotEmpty())
    <li class="nav-item d-flex align-items-center mr-2">
        <form method="POST" action="{{ route('admin::brand_context.switch') }}" class="form-inline">
            @csrf
            <label for="admin-brand-context" class="sr-only">Active brand</label>
            <select
                id="admin-brand-context"
                name="brand_uuid"
                class="form-control form-control-sm"
                aria-label="Active brand"
                onchange="this.form.submit()"
            >
                @foreach ($accessibleBrands as $brand)
                    <option
                        value="{{ $brand->uuid }}"
                        @selected($adminContext->brand()->is($brand))
                    >
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
        </form>
    </li>
@endif
