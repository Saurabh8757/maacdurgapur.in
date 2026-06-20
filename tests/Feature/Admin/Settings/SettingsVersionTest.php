<?php

namespace Tests\Feature\Admin\Settings;

use App\Models\Brand;
use App\Models\SettingDefinition;
use App\Models\SettingGroup;
use App\Models\SettingValue;
use App\Models\SettingValueVersion;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsVersionTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Brand $brand;
    private SettingDefinition $definition;
    private SettingValue $settingValue;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['user_type' => 'Admin']);
        $this->brand = Brand::create([
            'uuid' => 'test-brand-uuid',
            'code' => 'test_brand',
            'name' => 'Test Brand',
            'slug' => 'test-brand',
            'status' => 'active',
        ]);

        $this->admin->brands()->attach($this->brand, ['is_default' => true]);

        $role = Role::create([
            'code' => 'brand_admin',
            'name' => 'Brand Admin',
            'scope_type' => 'brand',
            'status' => 'active',
        ]);
        $this->admin->roles()->attach($role, [
            'brand_id' => $this->brand->id,
            'uuid' => \Illuminate\Support\Str::uuid(),
            'scope_key' => 'brand:' . $this->brand->uuid,
            'status' => 'active',
        ]);

        $permission = Permission::create([
            'code' => 'settings.brand.view',
            'name' => 'Settings Brand View',
            'domain' => 'settings',
            'resource' => 'brand',
            'action' => 'view',
            'scope_type' => 'brand',
            'status' => 'active',
        ]);
        $role->permissions()->attach($permission);

        $group = SettingGroup::create([
            'name' => 'General',
            'code' => 'general',
            'status' => 'active',
            'sort_order' => 1,
        ]);
        
        $this->definition = SettingDefinition::create([
            'setting_group_id' => $group->id,
            'name' => 'Site Name',
            'key' => 'general.site_name',
            'data_type' => 'string',
            'is_brand_overridable' => true,
            'is_sensitive' => false,
            'status' => 'active',
        ]);

        $this->settingValue = SettingValue::create([
            'setting_definition_id' => $this->definition->id,
            'brand_id' => $this->brand->id,
            'scope_key' => 'brand',
            'locale' => 'en',
            'value' => 'Brand Name',
            'status' => 'published',
        ]);

        SettingValueVersion::factory()->create([
            'setting_value_id' => $this->settingValue->id,
            'version_number' => 1,
            'value' => 'Old Brand Name',
            'status' => 'published',
            'change_summary' => 'Initial',
            'created_by' => $this->admin->id,
        ]);
        
        SettingValueVersion::factory()->create([
            'setting_value_id' => $this->settingValue->id,
            'version_number' => 2,
            'value' => 'Brand Name',
            'status' => 'published',
            'change_summary' => 'Updated',
            'created_by' => $this->admin->id,
        ]);
    }

    private function getBrandSessionConfig(): array
    {
        return [
            'admin_brand_context' => [
                'brand_id' => $this->brand->id,
                'brand_uuid' => $this->brand->uuid,
                'user_id' => $this->admin->id,
                'source' => 'explicit',
                'selected_at' => now()->toIso8601String(),
            ]
        ];
    }

    public function test_can_list_versions()
    {
        config(['brands.host_validation.operational_hosts' => ['localhost']]);

        $response = $this->actingAs($this->admin)
            ->withSession($this->getBrandSessionConfig())
            ->getJson(route('admin::settings.brand.versions.index', ['definition' => 'general.site_name']));

        $response->assertStatus(200)
            ->assertJsonPath('data.0.version_number', 2)
            ->assertJsonPath('data.0.change_summary', 'Updated')
            ->assertJsonPath('data.1.version_number', 1);
    }

    public function test_can_view_specific_version()
    {
        config(['brands.host_validation.operational_hosts' => ['localhost']]);

        $response = $this->actingAs($this->admin)
            ->withSession($this->getBrandSessionConfig())
            ->getJson(route('admin::settings.brand.versions.show', [
                'definition' => 'general.site_name',
                'version' => 1
            ]));

        $response->assertStatus(200)
            ->assertJsonPath('data.version_number', 1)
            ->assertJsonPath('data.change_summary', 'Initial')
            ->assertJsonPath('data.presentation.raw_value', 'Old Brand Name');
    }
}
