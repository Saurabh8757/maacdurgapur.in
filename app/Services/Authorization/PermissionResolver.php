<?php

namespace App\Services\Authorization;

use App\Models\Brand;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Collection;

class PermissionResolver
{
    private array $memoizedDecisions = [];

    public function check(
        User $user,
        string $permissionCode,
        ?Brand $brand = null
    ): PermissionDecision {
        $scopeKey = $brand ? "brand:{$brand->uuid}" : 'global';
        $memoKey = "{$user->id}|{$permissionCode}|{$scopeKey}";

        if (isset($this->memoizedDecisions[$memoKey])) {
            return $this->memoizedDecisions[$memoKey];
        }

        if (!$user->exists || $user->getAttribute('deleted_at') !== null) {
            return $this->remember(
                $memoKey,
                PermissionDecision::deny($permissionCode, $scopeKey, 'user_unavailable')
            );
        }

        $permission = Permission::query()
            ->where('code', $permissionCode)
            ->first();

        if (!$permission) {
            return $this->remember(
                $memoKey,
                PermissionDecision::deny($permissionCode, $scopeKey, 'permission_unknown')
            );
        }

        if ($permission->status !== 'active') {
            return $this->remember(
                $memoKey,
                PermissionDecision::deny(
                    $permissionCode,
                    $scopeKey,
                    'permission_inactive',
                    $permission,
                    $brand
                )
            );
        }

        if ($permission->scope_type === 'brand' && !$brand) {
            return $this->remember(
                $memoKey,
                PermissionDecision::deny(
                    $permissionCode,
                    $scopeKey,
                    'brand_required',
                    $permission
                )
            );
        }

        if ($brand && !$user->brands()->whereKey($brand->getKey())->exists()) {
            return $this->remember(
                $memoKey,
                PermissionDecision::deny(
                    $permissionCode,
                    $scopeKey,
                    'brand_membership_missing',
                    $permission,
                    $brand
                )
            );
        }

        $assignments = $this->activeAssignments($user, $brand);

        if ($assignments->isEmpty()) {
            return $this->remember(
                $memoKey,
                PermissionDecision::deny(
                    $permissionCode,
                    $scopeKey,
                    'role_assignment_missing',
                    $permission,
                    $brand
                )
            );
        }

        foreach ($assignments as $assignment) {
            foreach ($this->roleLineage($assignment->role) as $role) {
                $granted = $role->permissions()
                    ->where('permissions.id', $permission->id)
                    ->where('permissions.status', 'active')
                    ->exists();

                if ($granted) {
                    return $this->remember(
                        $memoKey,
                        new PermissionDecision(
                            true,
                            $permissionCode,
                            $scopeKey,
                            'granted',
                            $permission,
                            $role,
                            $brand,
                            (bool) $permission->requires_mfa,
                            (bool) $permission->requires_reauthentication
                        )
                    );
                }
            }
        }

        return $this->remember(
            $memoKey,
            PermissionDecision::deny(
                $permissionCode,
                $scopeKey,
                'permission_not_granted',
                $permission,
                $brand
            )
        );
    }

    public function allows(
        User $user,
        string $permissionCode,
        ?Brand $brand = null
    ): bool {
        return $this->check($user, $permissionCode, $brand)->allowed;
    }

    private function activeAssignments(User $user, ?Brand $brand): Collection
    {
        return UserRole::query()
            ->with('role')
            ->active()
            ->where('user_id', $user->id)
            ->whereHas('role', function ($roleQuery) {
                $roleQuery->where('status', 'active');
            })
            ->where(function ($scopeQuery) use ($brand) {
                $scopeQuery->where('scope_key', 'global');

                if ($brand) {
                    $scopeQuery->orWhere(function ($brandScopeQuery) use ($brand) {
                        $brandScopeQuery
                            ->where('scope_key', "brand:{$brand->uuid}")
                            ->where('brand_id', $brand->id);
                    });
                }
            })
            ->get();
    }

    private function roleLineage(Role $role): array
    {
        $roles = [];
        $visited = [];
        $current = $role;

        while ($current && !isset($visited[$current->id])) {
            $visited[$current->id] = true;
            $roles[] = $current;
            $current = $current->parent;
        }

        return $roles;
    }

    private function remember(
        string $memoKey,
        PermissionDecision $decision
    ): PermissionDecision {
        return $this->memoizedDecisions[$memoKey] = $decision;
    }
}
