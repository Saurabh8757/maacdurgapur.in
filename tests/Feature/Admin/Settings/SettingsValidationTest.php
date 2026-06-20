<?php

namespace Tests\Feature\Admin\Settings;

use App\Models\Brand;
use App\Models\Permission;
use App\Models\Role;
use App\Models\SettingDefinition;
use App\Models\SettingGroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsValidationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Brand $brand;
    private SettingDefinition $definition;

    protected function setUp(): void
    {
        parent::setUp();
        
        config(['brands.host_validation.operational_hosts' => ['localhost', '127.0.0.1']]);

        $this->admin = User::factory()->create(['user_type' => 'Admin']);
        $this->brand = Brand::create([
            'uuid' => 'test-uuid-brand',
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
            'code' => 'settings.brand.edit',
            'name' => 'Settings Brand Edit',
            'domain' => 'settings',
            'resource' => 'brand',
            'action' => 'edit',
            'scope_type' => 'brand',
            'status' => 'active',
        ]);
        $role->permissions()->attach($permission);

        $group = SettingGroup::create([
            'code' => 'general',
            'name' => 'General',
            'status' => 'active',
        ]);

        $this->definition = SettingDefinition::create([
            'setting_group_id' => $group->id,
            'key' => 'email_address',
            'name' => 'Email Address',
            'data_type' => 'email',
            'input_type' => 'email',
            'is_required' => true,
            'is_translatable' => false,
            'is_brand_overridable' => true,
            'is_sensitive' => false,
            'is_public' => true,
            'requires_publish' => true,
            'sort_order' => 1,
            'status' => 'active',
        ]);
    }

    public function test_fails_on_invalid_email(): void
    {
        $this->withSession([
            'admin_brand_context' => [
                'brand_id' => $this->brand->id,
                'brand_uuid' => $this->brand->uuid,
                'user_id' => $this->admin->id,
                'source' => 'explicit_switch',
                'selected_at' => now()->toIso8601String()
            ]
        ]);

        $response = $this->actingAs($this->admin)->putJson(route('admin::settings.brand.draft.update'), [
            'definition_key' => 'email_address',
            'value' => 'not-an-email',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['value']);
    }

    public function test_passes_on_valid_email(): void
    {
        $this->withSession([
            'admin_brand_context' => [
                'brand_id' => $this->brand->id,
                'brand_uuid' => $this->brand->uuid,
                'user_id' => $this->admin->id,
                'source' => 'explicit_switch',
                'selected_at' => now()->toIso8601String()
            ]
        ]);

        $response = $this->actingAs($this->admin)->putJson(route('admin::settings.brand.draft.update'), [
            'definition_key' => 'email_address',
            'value' => 'test@example.com',
        ]);

        $response->assertOk();
    }
}
