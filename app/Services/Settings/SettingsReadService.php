<?php

namespace App\Services\Settings;

use App\Models\SettingDefinition;
use App\Models\SettingGroup;
use App\Models\SettingValue;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class SettingsReadService
{
    public function __construct(
        private SettingsAuthorizationService $authorization,
        private SettingsLocaleResolver $localeResolver,
        private SettingsValuePresenter $presenter
    ) {
    }

    public function catalogue(
        User $user,
        SettingsScope $scope,
        ?string $groupCode = null
    ): array {
        $this->authorization->authorize($user, $scope, 'view');

        $groups = SettingGroup::query()
            ->active()
            ->whereHas('definitions')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        if ($groups->isEmpty()) {
            return [
                'groups' => $groups,
                'selected_group' => null,
                'definitions' => collect(),
                'scope' => $scope,
            ];
        }

        $selectedGroup = $groupCode
            ? $groups->firstWhere('code', $groupCode)
            : $groups->first();

        abort_unless($selectedGroup, 404);

        $definitions = SettingDefinition::query()
            ->where('setting_group_id', $selectedGroup->id)
            ->when(
                $scope->isBrand(),
                fn ($query) => $query->where('is_brand_overridable', true)
            )
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $activeDefinitions = $definitions
            ->where('status', 'active')
            ->values();

        $definitionIds = $activeDefinitions->pluck('id');

        $scopeValues = $this->publishedValues(
            $definitionIds->all(),
            $scope->scopeKey(),
            $scope->locale(),
            $scope->brandId()
        );

        $globalValues = $scope->isBrand()
            ? $this->publishedValues(
                $definitionIds->all(),
                'global',
                $scope->locale(),
                null
            )
            : collect();

        $draftDefinitionIds = SettingValue::query()
            ->whereIn('setting_definition_id', $definitionIds)
            ->where('scope_key', $scope->scopeKey())
            ->where('locale', $scope->locale())
            ->where('status', 'draft')
            ->pluck('setting_definition_id')
            ->flip();

        $canViewSensitive = $this->authorization->allows(
            $user,
            $scope,
            'view_sensitive'
        );

        $presentedDefinitions = $definitions->map(function (
            SettingDefinition $definition
        ) use (
            $scopeValues,
            $globalValues,
            $draftDefinitionIds,
            $canViewSensitive
        ): array {
            if ($definition->status !== 'active') {
                return $this->definitionPayload(
                    $definition,
                    null,
                    [
                        'display_value' => 'Not available',
                        'raw_value' => null,
                        'configured' => false,
                        'masked' => false,
                        'source' => 'inactive',
                    ],
                    false
                );
            }

            $scopeValue = $scopeValues->get($definition->id);
            $globalValue = $globalValues->get($definition->id);

            if ($scopeValue) {
                $value = $scopeValue;
                $fallback = null;
                $source = $scopeValue->scope_key === 'global'
                    ? 'global'
                    : 'brand';
            } elseif ($globalValue) {
                $value = $globalValue;
                $fallback = null;
                $source = 'global_fallback';
            } elseif ($definition->default_value !== null) {
                $value = null;
                $fallback = $definition->default_value;
                $source = 'default';
            } else {
                $value = null;
                $fallback = null;
                $source = 'unconfigured';
            }

            return $this->definitionPayload(
                $definition,
                $value,
                $this->presenter->present(
                    $definition,
                    $value,
                    $fallback,
                    $source,
                    $canViewSensitive
                ),
                $draftDefinitionIds->has($definition->id)
            );
        });

        return [
            'groups' => $groups,
            'selected_group' => $selectedGroup,
            'definitions' => $presentedDefinitions,
            'scope' => $scope,
        ];
    }

    private function publishedValues(
        array $definitionIds,
        string $scopeKey,
        string $locale,
        ?int $brandId
    ): Collection {
        if ($definitionIds === []) {
            return new Collection();
        }

        return SettingValue::query()
            ->with('publisher:id,name')
            ->whereIn('setting_definition_id', $definitionIds)
            ->where('scope_key', $scopeKey)
            ->where('locale', $locale)
            ->where('status', 'published')
            ->when(
                $brandId === null,
                fn ($query) => $query->whereNull('brand_id'),
                fn ($query) => $query->where('brand_id', $brandId)
            )
            ->get()
            ->keyBy('setting_definition_id');
    }

    private function definitionPayload(
        SettingDefinition $definition,
        ?SettingValue $value,
        array $presentation,
        bool $hasDraft
    ): array {
        $safeDefinition = $definition;

        if ($presentation['masked']) {
            $safeDefinition = clone $definition;
            $safeDefinition->setAttribute('default_value', null);
        }

        return [
            'definition' => $safeDefinition,
            'presentation' => $presentation,
            'has_draft' => $hasDraft,
            'publication' => [
                'published_at' => $value?->published_at,
                'publisher_name' => $value?->publisher?->name,
            ],
        ];
    }
}
