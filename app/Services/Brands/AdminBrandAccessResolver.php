<?php

namespace App\Services\Brands;

use App\Models\Brand;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Collection;

class AdminBrandAccessResolver
{
    public function accessibleBrands(User $user): Collection
    {
        if ($this->hasActiveGlobalSuperAdminAssignment($user)) {
            return Brand::query()
                ->active()
                ->orderByDesc('is_primary')
                ->orderBy('id')
                ->get();
        }

        $assignedBrandIds = UserRole::query()
            ->active()
            ->where('user_id', $user->id)
            ->whereNotNull('brand_id')
            ->whereHas('role', function ($roleQuery) {
                $roleQuery
                    ->where('scope_type', 'brand')
                    ->where('status', 'active');
            })
            ->pluck('brand_id');

        if ($assignedBrandIds->isEmpty()) {
            return collect();
        }

        return $user->brands()
            ->whereIn('brands.id', $assignedBrandIds)
            ->where('brands.status', 'active')
            ->orderByDesc('brands.is_primary')
            ->orderBy('brands.id')
            ->get();
    }

    public function hasActiveGlobalSuperAdminAssignment(User $user): bool
    {
        return UserRole::query()
            ->active()
            ->where('user_id', $user->id)
            ->whereNull('brand_id')
            ->where('scope_key', 'global')
            ->whereHas('role', function ($roleQuery) {
                $roleQuery
                    ->where('code', 'super_admin')
                    ->where('scope_type', 'global')
                    ->where('status', 'active');
            })
            ->exists();
    }

    public function defaultAccessibleBrand(
        User $user,
        Collection $accessibleBrands
    ): ?Brand {
        $defaultBrandId = $user->brands()
            ->wherePivot('is_default', true)
            ->where('brands.status', 'active')
            ->value('brands.id');

        if (!$defaultBrandId) {
            return null;
        }

        return $accessibleBrands->firstWhere('id', $defaultBrandId);
    }
}
