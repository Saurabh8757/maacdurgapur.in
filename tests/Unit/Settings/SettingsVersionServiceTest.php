<?php

namespace Tests\Unit\Settings;

use App\Models\Brand;
use App\Models\SettingDefinition;
use App\Models\SettingValue;
use App\Models\User;
use App\Services\Settings\SettingsAuditLogger;
use App\Services\Settings\SettingsValuePresenter;
use App\Services\Settings\SettingsVersionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsVersionServiceTest extends TestCase
{
    use RefreshDatabase;

    private SettingsVersionService $service;
    private SettingsAuditLogger $auditLogger;
    private SettingsValuePresenter $presenter;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->auditLogger = $this->createMock(SettingsAuditLogger::class);
        $this->presenter = $this->createMock(SettingsValuePresenter::class);
        
        $this->service = new SettingsVersionService(
            $this->auditLogger,
            $this->presenter
        );
    }

    public function test_create_snapshot_creates_new_version_with_incremented_number()
    {
        $user = User::factory()->create();
        $brand = Brand::create([
            'uuid' => 'test-brand-uuid',
            'code' => 'test_brand',
            'name' => 'Test Brand',
            'slug' => 'test-brand',
            'status' => 'active',
        ]);
        $group = \App\Models\SettingGroup::create([
            'name' => 'General',
            'code' => 'general',
            'status' => 'active',
            'sort_order' => 1,
        ]);
        $definition = SettingDefinition::create([
            'setting_group_id' => $group->id,
            'key' => 'test.key',
            'name' => 'Test Key',
            'data_type' => 'string',
            'status' => 'active',
        ]);
        
        $settingValue = SettingValue::create([
            'setting_definition_id' => $definition->id,
            'brand_id' => $brand->id,
            'scope_key' => 'brand',
            'locale' => 'en',
            'value' => 'initial_value',
            'status' => 'draft',
        ]);

        $this->auditLogger->expects($this->once())->method('record');

        $version = $this->service->createSnapshot($settingValue, 'First snapshot', $user);

        $this->assertEquals(1, $version->version_number);
        $this->assertEquals('initial_value', $version->value);
        $this->assertEquals('draft', $version->status);
        $this->assertEquals('First snapshot', $version->change_summary);
        $this->assertEquals($user->id, $version->created_by);

        // Update the value and create another snapshot
        $settingValue->update(['value' => 'updated_value', 'status' => 'published']);
        
        $this->auditLogger->expects($this->once())->method('record');
        
        // recreate service to mock expectations again or just use a new instance
        $service2 = new SettingsVersionService(
            $this->createMock(SettingsAuditLogger::class),
            $this->presenter
        );
        $version2 = $service2->createSnapshot($settingValue, 'Second snapshot', $user);

        $this->assertEquals(2, $version2->version_number);
        $this->assertEquals('updated_value', $version2->value);
        $this->assertEquals('published', $version2->status);
    }
}
