<?php

namespace App\Services\Settings;

use App\Models\SettingDefinition;
use App\Models\SettingValue;
use App\Models\SettingValueVersion;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SettingsVersionService
{
    public function __construct(
        private SettingsAuditLogger $auditLogger,
        private SettingsValuePresenter $presenter
    ) {
    }

    public function createSnapshot(
        SettingValue $settingValue,
        ?string $changeSummary,
        User $user
    ): SettingValueVersion {
        return DB::transaction(function () use ($settingValue, $changeSummary, $user) {
            $lockedValue = SettingValue::query()->lockForUpdate()->findOrFail($settingValue->getKey());

            $maxVersion = SettingValueVersion::where('setting_value_id', $lockedValue->getKey())->max('version_number');
            $nextVersion = $maxVersion ? $maxVersion + 1 : 1;

            $version = new SettingValueVersion([
                'setting_value_id' => $lockedValue->getKey(),
                'version_number' => $nextVersion,
                'value' => $lockedValue->value,
                'status' => $lockedValue->status,
                'change_summary' => $changeSummary,
                'created_by' => $user->getKey(),
            ]);

            $version->save();

            $this->auditLogger->record(
                event: 'version_created',
                scopeKey: $lockedValue->scope_key,
                locale: $lockedValue->locale,
                afterValue: $lockedValue->value,
                user: $user,
                brand: $lockedValue->brand,
                definition: $lockedValue->definition,
                settingValue: $lockedValue
            );

            return $version;
        });
    }

    public function getHistory(
        SettingsScope $scope,
        SettingDefinition $definition,
        bool $canViewSensitive
    ): LengthAwarePaginator {
        $versions = SettingValueVersion::query()
            ->with('creator:id,name')
            ->whereHas('settingValue', function ($query) use ($scope, $definition) {
                $query->where('setting_definition_id', $definition->getKey())
                    ->where('scope_key', $scope->scopeKey())
                    ->where('locale', $scope->locale());

                if ($scope->isBrand()) {
                    $query->where('brand_id', $scope->brandId());
                } else {
                    $query->whereNull('brand_id');
                }
            })
            ->orderByDesc('version_number')
            ->paginate(15);

        $versions->getCollection()->transform(function ($version) use ($definition, $canViewSensitive) {
            return $this->formatVersion($version, $definition, $canViewSensitive);
        });

        return $versions;
    }

    public function getVersion(
        SettingsScope $scope,
        SettingDefinition $definition,
        int $versionNumber,
        bool $canViewSensitive
    ): array {
        $version = SettingValueVersion::query()
            ->with('creator:id,name')
            ->where('version_number', $versionNumber)
            ->whereHas('settingValue', function ($query) use ($scope, $definition) {
                $query->where('setting_definition_id', $definition->getKey())
                    ->where('scope_key', $scope->scopeKey())
                    ->where('locale', $scope->locale());

                if ($scope->isBrand()) {
                    $query->where('brand_id', $scope->brandId());
                } else {
                    $query->whereNull('brand_id');
                }
            })
            ->first();

        if (!$version) {
            throw new NotFoundHttpException('Version not found.');
        }

        return $this->formatVersion($version, $definition, $canViewSensitive);
    }

    private function formatVersion(
        SettingValueVersion $version,
        SettingDefinition $definition,
        bool $canViewSensitive
    ): array {
        $presentation = $this->presenter->present(
            definition: $definition,
            value: $version->value,
            fallback: null,
            source: 'history',
            canViewSensitive: $canViewSensitive
        );

        return [
            'id' => $version->id,
            'version_number' => $version->version_number,
            'status' => $version->status,
            'change_summary' => $version->change_summary,
            'created_at' => $version->created_at,
            'creator_name' => $version->creator?->name,
            'presentation' => $presentation,
        ];
    }
}
