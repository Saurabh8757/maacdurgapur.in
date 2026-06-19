<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['super_admin', 'Super Admin', 'global', 'critical', false],
            ['brand_admin', 'Brand Admin', 'brand', 'high', true],
            ['content_manager', 'Content Manager', 'brand', 'medium', true],
            ['content_editor', 'Content Editor', 'brand', 'medium', true],
            ['media_manager', 'Media Manager', 'brand', 'high', true],
            ['reviewer', 'Reviewer', 'brand', 'medium', true],
            ['analyst', 'Analyst', 'brand', 'low', true],
            ['auditor', 'Auditor', 'global', 'high', false],
            ['student_counsellor', 'Student Counsellor', 'brand', 'medium', true],
            ['placement_coordinator', 'Placement Coordinator', 'brand', 'medium', true],
            ['marketing_manager', 'Marketing Manager', 'brand', 'high', true],
        ];

        foreach ($roles as [$code, $name, $scopeType, $riskLevel, $assignable]) {
            $role = Role::firstOrNew(['code' => $code]);

            if (!$role->exists) {
                $role->uuid = (string) Str::uuid();
            }

            $role->fill([
                'parent_role_id' => null,
                'name' => $name,
                'description' => null,
                'scope_type' => $scopeType,
                'risk_level' => $riskLevel,
                'is_system' => true,
                'is_assignable' => $assignable,
                'status' => 'active',
                'created_by' => null,
                'updated_by' => null,
            ]);
            $role->save();
        }
    }
}
