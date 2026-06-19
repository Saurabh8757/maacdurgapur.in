<?php

namespace Tests\Unit\Settings;

use App\Models\Brand;
use App\Models\SettingDefinition;
use App\Services\Settings\Exceptions\UnsupportedSettingsLocaleException;
use App\Services\Settings\SettingsLocaleResolver;
use App\Services\Settings\SettingsScope;
use Tests\TestCase;

class SettingsLocaleResolverTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'brands.settings.supported_locales' => ['en', 'bn'],
            'brands.settings.default_locale' => 'en',
        ]);
    }

    public function test_supported_locale_is_normalized(): void
    {
        $this->assertSame(
            'bn',
            (new SettingsLocaleResolver())->validate(' BN ')
        );
    }

    public function test_unsupported_locale_is_rejected(): void
    {
        $this->expectException(UnsupportedSettingsLocaleException::class);

        (new SettingsLocaleResolver())->validate('fr');
    }

    public function test_brand_and_global_defaults_are_resolved(): void
    {
        $resolver = new SettingsLocaleResolver();
        $brand = $this->brand('bn');

        $this->assertSame('bn', $resolver->resolveBrand($brand));
        $this->assertSame('en', $resolver->resolveGlobal());
    }

    public function test_non_translatable_definition_ignores_alternate_locale(): void
    {
        $resolver = new SettingsLocaleResolver();
        $brand = $this->brand('en');
        $scope = SettingsScope::forBrand($brand, 'en');
        $definition = new SettingDefinition([
            'is_translatable' => false,
        ]);

        $this->assertSame(
            'en',
            $resolver->resolveForDefinition(
                $definition,
                $scope,
                'bn'
            )
        );
    }

    public function test_translatable_definition_uses_requested_supported_locale(): void
    {
        $definition = new SettingDefinition([
            'is_translatable' => true,
        ]);

        $this->assertSame(
            'bn',
            (new SettingsLocaleResolver())->resolveForDefinition(
                $definition,
                SettingsScope::forGlobal('en'),
                'bn'
            )
        );
    }

    private function brand(string $defaultLocale): Brand
    {
        $brand = new Brand([
            'uuid' => 'e0802a5c-9ae4-4583-9534-d6c9281be008',
            'code' => 'maac',
            'name' => 'MAAC',
            'default_locale' => $defaultLocale,
            'status' => 'active',
        ]);
        $brand->id = 1;

        return $brand;
    }
}
