<aside class="settings-group-nav" aria-label="Settings groups">
    <div class="settings-group-nav-title">Setting groups</div>
    <nav>
        @foreach ($groups as $group)
            <a href="{{ $scope->isBrand()
                ? route('admin::settings.brand.index', ['group' => $group->code, 'locale' => $scope->locale()])
                : route('admin::settings.global.index', ['group' => $group->code, 'locale' => $scope->locale()]) }}"
               class="{{ $selected_group?->id === $group->id ? 'active' : '' }}">
                <i class="{{ $group->icon ?: 'fas fa-cog' }}"></i>
                <span>{{ $group->name }}</span>
                <i class="fas fa-chevron-right settings-nav-arrow"></i>
            </a>
        @endforeach
    </nav>
</aside>
