<?php

namespace App\Services\Authorization;

use App\Models\Brand;
use App\Models\Permission;
use App\Models\Role;

class PermissionDecision
{
    public function __construct(
        public bool $allowed,
        public string $permissionCode,
        public string $scopeKey,
        public string $reason,
        public ?Permission $permission = null,
        public ?Role $role = null,
        public ?Brand $brand = null,
        public bool $requiresMfa = false,
        public bool $requiresReauthentication = false
    ) {
    }

    public static function deny(
        string $permissionCode,
        string $scopeKey,
        string $reason,
        ?Permission $permission = null,
        ?Brand $brand = null
    ): self {
        return new self(
            false,
            $permissionCode,
            $scopeKey,
            $reason,
            $permission,
            null,
            $brand,
            (bool) $permission?->requires_mfa,
            (bool) $permission?->requires_reauthentication
        );
    }
}
