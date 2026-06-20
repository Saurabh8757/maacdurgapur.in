<?php

namespace Tests\Feature\Admin\Settings;

use App\Models\Brand;
use App\Models\Permission;
use App\Models\Role;
use App\Models\SettingDefinition;
use App\Models\SettingGroup;
use App\Models\SettingValue;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsDraftTest extends TestCase
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
            'key' => 'site_name',
            'name' => 'Site Name',
            'data_type' => 'string',
            'input_type' => 'text',
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

    public function test_can_create_and_update_draft(): void
    {
        $this->withoutExceptionHandling();
        // Add brand context to session
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
            'definition_key' => 'site_name',
            'value' => 'Draft Site Name',
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('setting_values', [
            'setting_definition_id' => $this->definition->id,
            'brand_id' => $this->brand->id,
            'scope_key' => 'brand:' . $this->brand->uuid,
            'status' => 'draft',
            'value' => '"Draft Site Name"',
        ]);

        $this->assertDatabaseHas('setting_audit_logs', [
            'event' => 'draft_created',
            'scope_key' => 'brand:' . $this->brand->uuid,
        ]);

        // Update the draft
        $draft = SettingValue::first();

        $updateResponse = $this->actingAs($this->admin)->putJson(route('admin::settings.brand.draft.update'), [
            'definition_key' => 'site_name',
            'value' => 'Updated Site Name',
            'updated_at' => $draft->updated_at->toIso8601String(),
        ]);

        $updateResponse->assertOk();
        $this->assertDatabaseHas('setting_values', [
            'id' => $draft->id,
            'value' => '"Updated Site Name"',
        ]);

        $this->assertDatabaseHas('setting_audit_logs', [
            'event' => 'draft_updated',
        ]);
    }

    public function test_rejects_stale_update(): void
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

        $this->actingAs($this->admin)->putJson(route('admin::settings.brand.draft.update'), [
            'definition_key' => 'site_name',
            'value' => 'First',
        ]);

        // Try updating with wrong timestamp
        $response = $this->actingAs($this->admin)->putJson(route('admin::settings.brand.draft.update'), [
            'definition_key' => 'site_name',
            'value' => 'Second',
            'updated_at' => '2000-01-01T00:00:00Z',
        ]);

        $response->assertStatus(409); // Conflict
    }

    public function test_can_reset_override(): void
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

        $this->actingAs($this->admin)->putJson(route('admin::settings.brand.draft.update'), [
            'definition_key' => 'site_name',
            'value' => 'To be deleted',
        ]);

        $this->assertDatabaseCount('setting_values', 1);

        $response = $this->actingAs($this->admin)->deleteJson(route('admin::settings.brand.draft.reset', ['definition' => 'site_name']));

        $response->assertOk();
        $this->assertDatabaseCount('setting_values', 0);

        $this->assertDatabaseHas('setting_audit_logs', [
            'event' => 'override_reset',
        ]);
    }
}
