@php
    $definition = $item['definition'];
    $presentation = $item['presentation'];
    $publication = $item['publication'];
    $sourceLabels = [
        'brand' => 'Brand override',
        'global' => 'Global value',
        'global_fallback' => 'Global fallback',
        'default' => 'Definition default',
        'unconfigured' => 'Not configured',
        'inactive' => 'Inactive definition',
    ];
@endphp

<article class="settings-definition-card {{ $definition->status !== 'active' ? 'is-inactive' : '' }}">
    <div class="settings-definition-top">
        <div>
            <div class="settings-definition-flags">
                <span class="settings-status settings-status-{{ $definition->status }}">{{ ucfirst($definition->status) }}</span>
                @if ($definition->is_required)<span>Required</span>@endif
                @if ($definition->is_translatable)<span>Translatable</span>@endif
                @if ($definition->is_sensitive)<span><i class="fas fa-shield-alt"></i> Sensitive</span>@endif
                @if ($item['has_draft'])<span class="settings-draft-flag">Draft exists</span>@endif
            </div>
            <h3>{{ $definition->name }}</h3>
            <code>{{ $definition->key }}</code>
        </div>
        <div class="settings-type-pill">{{ $definition->data_type }} / {{ $definition->input_type }}</div>
    </div>

    @if ($definition->description)
        <p class="settings-definition-description">{{ $definition->description }}</p>
    @endif

    <div class="settings-value-panel">
        <div class="settings-value-heading">
            <span>Effective value</span>
            <span class="settings-source settings-source-{{ $presentation['source'] }}">
                {{ $sourceLabels[$presentation['source']] ?? ucfirst($presentation['source']) }}
            </span>
        </div>
        <pre class="{{ $presentation['masked'] ? 'is-masked' : '' }}">{{ $presentation['display_value'] }}</pre>
    </div>

    <div class="settings-definition-meta">
        <span>
            <b>Locale</b>
            {{ strtoupper($scope->locale()) }}
        </span>
        <span>
            <b>Published</b>
            {{ $publication['published_at']?->format('d M Y, H:i') ?? '—' }}
        </span>
        <span>
            <b>Publisher</b>
            {{ $publication['publisher_name'] ?? '—' }}
        </span>
    </div>
</article>
