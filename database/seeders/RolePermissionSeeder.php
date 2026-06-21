<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\Brand;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use RuntimeException;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $allPermissions = Permission::active()->pluck('id', 'code');
        $roles = Role::active()->get()->keyBy('code');

        $grants = [
            'super_admin' => $allPermissions->keys()->all(),
            'brand_admin' => [
                'brands.view',
                'settings.brand.view',
                'settings.brand.edit',
                'settings.brand.submit',
                'settings.brand.approve',
                'settings.brand.publish',
                'settings.brand.rollback',
                'settings.sensitive.view',
                'media.assets.view',
                'media.assets.upload',
                'media.assets.edit_metadata',
                'media.assets.organize',
                'media.assets.attach',
                'media.assets.publish',
                'media.assets.replace',
                'media.assets.archive',
                'media.assets.restore',
                'cms.faqs.view',
                'cms.faqs.create',
                'cms.faqs.edit',
                'cms.faqs.delete',
                'cms.courses.view',
                'cms.courses.create',
                'cms.courses.edit',
                'cms.courses.delete',
                'cms.features.view',
                'cms.features.create',
                'cms.features.edit',
                'cms.features.delete',
                'cms.showcase.view',
                'cms.showcase.create',
                'cms.showcase.edit',
                'cms.showcase.delete',
                'cms.showcase.publish',
            ],
            'content_manager' => [
                'brands.view',
                'settings.brand.view',
                'settings.brand.edit',
                'settings.brand.submit',
                'media.assets.view',
                'media.assets.attach',
                'cms.faqs.view',
                'cms.faqs.create',
                'cms.faqs.edit',
                'cms.faqs.delete',
                'cms.courses.view',
                'cms.courses.create',
                'cms.courses.edit',
                'cms.courses.delete',
                'cms.features.view',
                'cms.features.create',
                'cms.features.edit',
                'cms.features.delete',
                'cms.showcase.view',
                'cms.showcase.create',
                'cms.showcase.edit',
                'cms.showcase.delete',
                'cms.showcase.publish',
            ],
            'content_editor' => [
                'brands.view',
                'settings.brand.view',
                'settings.brand.edit',
                'media.assets.view',
                'media.assets.attach',
                'cms.faqs.view',
                'cms.faqs.create',
                'cms.faqs.edit',
                'cms.courses.view',
                'cms.courses.create',
                'cms.courses.edit',
                'cms.features.view',
                'cms.features.create',
                'cms.features.edit',
                'cms.showcase.view',
                'cms.showcase.create',
                'cms.showcase.edit',
            ],
            'media_manager' => [
                'brands.view',
                'media.assets.view',
                'media.assets.upload',
                'media.assets.edit_metadata',
                'media.assets.organize',
                'media.assets.attach',
                'media.assets.publish',
                'media.assets.replace',
                'media.assets.archive',
                'media.assets.restore',
            ],
            'reviewer' => [
                'brands.view',
                'settings.brand.view',
                'settings.brand.approve',
                'media.assets.view',
            ],
            'analyst' => [
                'brands.view',
                'settings.brand.view',
                'media.assets.view',
            ],
            'auditor' => [
                'brands.view',
                'brands.access_global_scope',
                'settings.global.view',
                'settings.sensitive.view',
                'media.assets.view',
            ],
            'student_counsellor' => [
                'brands.view',
            ],
            'placement_coordinator' => [
                'brands.view',
                'media.assets.view',
                'media.assets.upload',
                'media.assets.attach',
            ],
            'marketing_manager' => [
                'brands.view',
                'settings.brand.view',
                'settings.brand.edit',
                'settings.brand.submit',
                'media.assets.view',
                'media.assets.upload',
                'media.assets.attach',
            ],
        ];

        foreach ($grants as $roleCode => $permissionCodes) {
            $role = $roles->get($roleCode);

            if (!$role) {
                throw new RuntimeException("Missing role: {$roleCode}");
            }

            foreach ($permissionCodes as $permissionCode) {
                $permissionId = $allPermissions->get($permissionCode);

                if (!$permissionId) {
                    throw new RuntimeException("Missing permission: {$permissionCode}");
                }

                RolePermission::updateOrCreate(
                    [
                        'role_id' => $role->id,
                        'permission_id' => $permissionId,
                    ],
                    [
                        'granted_by' => null,
                    ]
                );
            }
        }

        $brandAdmin = $roles->get('brand_admin');
        $maac = Brand::where('code', 'maac')->first();

        if ($brandAdmin && $maac) {
            User::where('user_type', 'Admin')->each(function (User $user) use ($brandAdmin, $maac): void {
                UserRole::firstOrCreate(
                    [
                        'user_id' => $user->getKey(),
                        'role_id' => $brandAdmin->getKey(),
                        'scope_key' => 'brand:'.$maac->uuid,
                        'status' => 'active',
                    ],
                    [
                        'brand_id' => $maac->getKey(),
                        'assigned_by' => $user->getKey(),
                        'reason' => 'Bootstrap admin access',
                    ]
                );
            });
        }
    }
}
