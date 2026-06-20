<?php

namespace App\Services\Cms;

use App\Models\Brand;
use App\Models\User;
use App\Services\Authorization\PermissionDecision;
use App\Services\Authorization\PermissionResolver;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CmsAuthorizationService
{
    public function __construct(
        private readonly PermissionResolver $permissionResolver
    ) {
    }

    public function allows(
        User $user,
        string $module,
        string $operation,
        Brand $brand
    ): bool {
        $permissionCode = "cms.{$module}.{$operation}";

        $decision = $this->permissionResolver->check(
            $user,
            $permissionCode,
            $brand
        );

        return $decision->allowed;
    }

    public function authorize(
        User $user,
        string $module,
        string $operation,
        Brand $brand
    ): void {
        if (!$this->allows($user, $module, $operation, $brand)) {
            abort(403, "Unauthorized. Requires permission: cms.{$module}.{$operation}");
        }
    }
}
