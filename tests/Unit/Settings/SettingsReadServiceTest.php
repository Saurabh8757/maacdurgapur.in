<?php

namespace Tests\Unit\Settings;

use App\Models\Brand;
use App\Models\SettingDefinition;
use App\Models\SettingValue;
use App\Models\User;
use App\Services\Settings\SettingsAuthorizationService;
use App\Services\Settings\SettingsLocaleResolver;
use App\Services\Settings\SettingsReadService;
use App\Services\Settings\SettingsScope;
use App\Services\Settings\SettingsValuePresenter;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class SettingsReadServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::beginTransaction();
    }

    protected function tearDown(): void
    {
        DB::rollBack();
        Mockery::close();
        parent::tearDown();
    }

    public function test_inactive_definitions_are_visible_but_not_resolved(): void
    {
        $definition = SettingDefinition::query()
            ->where('key', 'general.site_name')
            ->firstOrFail();

        $catalogue = $this->service(true)->catalogue(
            User::findOrFail(6),
            SettingsScope::forGlobal('en'),
            'general'
        );

        $item = $catalogue['definitions']->first(
            fn (array $entry) => $entry['definition']->id === $definition->id
        );

        $this->assertSame('inactive', $item['definition']->status);
        $this->assertSame('inactive', $item['presentation']['source']);
        $this->assertSame('Not available', $item['presentation']['display_value']);
    }

    public function test_brand_value_precedes_global_and_default_values(): void
    {
        [$definition, $brand] = $this->activeDefinitionAndBrand();
        $definition->update(['default_value' => ['text' => 'Default']]);

        SettingValue::create([
            'setting_definition_id' => $definition->id,
            'brand_id' => null,
            'scope_key' => 'global',
            'locale' => 'en',
            'value' => ['text' => 'Global'],
            'status' => 'published',
        ]);
        SettingValue::create([
            'setting_definition_id' => $definition->id,
            'brand_id' => $brand->id,
            'scope_key' => "brand:{$brand->uuid}",
            'locale' => 'en',
            'value' => ['text' => 'Brand'],
            'status' => 'published',
        ]);

        $item = $this->catalogueItem(
            $definition,
            SettingsScope::forBrand($brand, 'en')
        );

        $this->assertSame('brand', $item['presentation']['source']);
        $this->assertStringContainsString(
            'Brand',
            $item['presentation']['display_value']
        );
    }

    public function test_brand_scope_falls_back_to_global_then_default(): void
    {
        [$definition, $brand] = $this->activeDefinitionAndBrand();
        $definition->update(['default_value' => ['text' => 'Default']]);

        $global = SettingValue::create([
            'setting_definition_id' => $definition->id,
            'brand_id' => null,
            'scope_key' => 'global',
            'locale' => 'en',
            'value' => ['text' => 'Global'],
            'status' => 'published',
        ]);

        $item = $this->catalogueItem(
            $definition,
            SettingsScope::forBrand($brand, 'en')
        );
        $this->assertSame('global_fallback', $item['presentation']['source']);

        $global->delete();
        $item = $this->catalogueItem(
            $definition,
            SettingsScope::forBrand($brand, 'en')
        );
        $this->assertSame('default', $item['presentation']['source']);
    }

    public function test_sensitive_value_is_masked_without_sensitive_permission(): void
    {
        [$definition, $brand] = $this->activeDefinitionAndBrand();
        $definition->update(['is_sensitive' => true]);

        SettingValue::create([
            'setting_definition_id' => $definition->id,
            'brand_id' => $brand->id,
            'scope_key' => "brand:{$brand->uuid}",
            'locale' => 'en',
            'value' => ['secret' => 'must-not-leak'],
            'status' => 'published',
        ]);

        $item = $this->catalogueItem(
            $definition,
            SettingsScope::forBrand($brand, 'en'),
            false
        );

        $this->assertTrue($item['presentation']['masked']);
        $this->assertNull($item['presentation']['raw_value']);
        $this->assertStringNotContainsString(
            'must-not-leak',
            json_encode($item)
        );
    }

    public function test_catalogue_uses_batched_queries_without_n_plus_one(): void
    {
        [$definition, $brand] = $this->activeDefinitionAndBrand();

        DB::flushQueryLog();
        DB::enableQueryLog();

        $this->service(true)->catalogue(
            User::findOrFail(6),
            SettingsScope::forBrand($brand, 'en'),
            $definition->group->code
        );

        $queryCount = count(DB::getQueryLog());
        DB::disableQueryLog();

        $this->assertLessThanOrEqual(7, $queryCount);
    }

    private function catalogueItem(
        SettingDefinition $definition,
        SettingsScope $scope,
        bool $sensitiveAllowed = true
    ): array {
        $catalogue = $this->service($sensitiveAllowed)->catalogue(
            User::findOrFail(6),
            $scope,
            $definition->group->code
        );

        return $catalogue['definitions']->first(
            fn (array $entry) => $entry['definition']->id === $definition->id
        );
    }

    private function activeDefinitionAndBrand(): array
    {
        $definition = SettingDefinition::query()
            ->with('group')
            ->where('key', 'general.site_name')
            ->firstOrFail();
        $definition->update(['status' => 'active']);

        return [
            $definition->fresh('group'),
            Brand::query()->where('code', 'maac')->firstOrFail(),
        ];
    }

    private function service(bool $sensitiveAllowed): SettingsReadService
    {
        $authorization = Mockery::mock(SettingsAuthorizationService::class);
        $authorization->shouldReceive('authorize')
            ->withAnyArgs()
            ->andReturnNull();
        $authorization->shouldReceive('allows')
            ->with(
                Mockery::type(User::class),
                Mockery::type(SettingsScope::class),
                'view_sensitive'
            )
            ->andReturn($sensitiveAllowed);

        return new SettingsReadService(
            $authorization,
            new SettingsLocaleResolver(),
            new SettingsValuePresenter()
        );
    }
}
