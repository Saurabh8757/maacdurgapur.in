<?php

namespace App\Services\Settings;

use App\Models\SettingDefinition;
use App\Models\User;
use App\Services\Authorization\PermissionDecision;
use App\Services\Authorization\PermissionResolver;
use App\Services\Settings\Exceptions\SettingsAuthorizationException;
use InvalidArgumentException;

class SettingsAuthorizationService
{
    public function __construct(
        private PermissionResolver $permissionResolver
    ) {
    }

    public function decision(
        User $user,
        SettingsScope $scope,
        string $operation
    ): PermissionDecision {
        $permissionCode = $this->permissionCode($scope, $operation);

        return $this->permissionResolver->check(
            $user,
            $permissionCode,
            $scope->brand()
        );
    }

    public function allows(
        User $user,
        SettingsScope $scope,
        string $operation
    ): bool {
        return $this->decision($user, $scope, $operation)->allowed;
    }

    public function authorize(
        User $user,
        SettingsScope $scope,
        string $operation
    ): void {
        $decision = $this->decision($user, $scope, $operation);

        if (!$decision->allowed) {
            $this->throwDenied($decision, $scope);
        }
    }

    public function authorizeDefinition(
        User $user,
        SettingsScope $scope,
        SettingDefinition $definition,
        string $operation
    ): void {
        if ($definition->status !== 'active') {
            throw new SettingsAuthorizationException(
                $this->permissionCode($scope, $operation),
                $scope->scopeKey(),
                'definition_inactive',
                $scope->brandUuid()
            );
        }

        if ($scope->isBrand() && !$definition->is_brand_overridable) {
            throw new SettingsAuthorizationException(
                $this->permissionCode($scope, $operation),
                $scope->scopeKey(),
                'brand_override_disabled',
                $scope->brandUuid()
            );
        }

        $this->authorize($user, $scope, $operation);

        if (!$definition->is_sensitive) {
            return;
        }

        $sensitiveOperation = match ($operation) {
            'view', 'view_definitions' => 'view_sensitive',
            'edit' => 'edit_sensitive',
            default => null,
        };

        if ($sensitiveOperation !== null) {
            $this->authorize($user, $scope, $sensitiveOperation);
        }
    }

    private function permissionCode(
        SettingsScope $scope,
        string $operation
    ): string {
        if ($operation === 'view_sensitive') {
            return 'settings.sensitive.view';
        }

        if ($operation === 'edit_sensitive') {
            return 'settings.sensitive.edit';
        }

        if ($operation === 'view_definitions') {
            $operation = 'view';
        }

        $mapping = $scope->isBrand()
            ? [
                'view' => 'settings.brand.view',
                'edit' => 'settings.brand.edit',
                'submit' => 'settings.brand.submit',
                'approve' => 'settings.brand.approve',
                'publish' => 'settings.brand.publish',
                'rollback' => 'settings.brand.rollback',
            ]
            : [
                'view' => 'settings.global.view',
                'edit' => 'settings.global.edit',
                'approve' => 'settings.global.publish',
                'publish' => 'settings.global.publish',
                'rollback' => 'settings.global.publish',
            ];

        if (!isset($mapping[$operation])) {
            throw new InvalidArgumentException(
                "Unsupported settings authorization operation: {$operation}."
            );
        }

        return $mapping[$operation];
    }

    private function throwDenied(
        PermissionDecision $decision,
        SettingsScope $scope
    ): void {
        throw new SettingsAuthorizationException(
            $decision->permissionCode,
            $scope->scopeKey(),
            $decision->reason,
            $scope->brandUuid()
        );
    }
}
