<div class="settings-scope-card">
    <div class="settings-scope-identity">
        <span class="settings-scope-icon">
            <i class="{{ $scope->isBrand() ? 'fas fa-building' : 'fas fa-globe' }}"></i>
        </span>
        <div>
            <span class="settings-scope-label">{{ $scope->isBrand() ? 'Active brand scope' : 'Application scope' }}</span>
            <h2>{{ $scope->isBrand() ? $scope->brand()->name : 'Global Settings' }}</h2>
            <code>{{ $scope->scopeKey() }}</code>
        </div>
    </div>

    <div class="settings-scope-tools">
        <div class="settings-readonly-badge"><i class="fas fa-lock"></i> Read only</div>
        <form method="GET" action="{{ $scope->isBrand() ? route('admin::settings.brand.index') : route('admin::settings.global.index') }}">
            @if ($selected_group)
                <input type="hidden" name="group" value="{{ $selected_group->code }}">
            @endif
            <label for="settings-locale">Locale</label>
            <select id="settings-locale" name="locale" onchange="this.form.submit()">
                @foreach ($supportedLocales as $locale)
                    <option value="{{ $locale }}" @selected($scope->locale() === $locale)>{{ strtoupper($locale) }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="settings-coexistence-note">
        <i class="fas fa-info-circle"></i>
        <span>
            This catalogue does not replace or migrate existing <code>site_info</code> values.
            {{ $scope->isBrand() ? 'Brand overrides fall back to published global values and then definition defaults.' : 'Global values fall back to definition defaults.' }}
        </span>
    </div>
</div>
