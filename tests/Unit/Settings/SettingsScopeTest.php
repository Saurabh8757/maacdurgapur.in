<?php

namespace Tests\Unit\Settings;

use App\Models\Brand;
use App\Services\Settings\SettingsScope;
use PHPUnit\Framework\TestCase;

class SettingsScopeTest extends TestCase
{
    public function test_brand_scope_exposes_immutable_brand_identity(): void
    {
        $brand = $this->brand();
        $scope = SettingsScope::forBrand($brand, 'en');

        $this->assertSame('brand', $scope->type());
        $this->assertSame("brand:{$brand->uuid}", $scope->scopeKey());
        $this->assertSame($brand, $scope->brand());
        $this->assertSame(1, $scope->brandId());
        $this->assertSame($brand->uuid, $scope->brandUuid());
        $this->assertSame('en', $scope->locale());
        $this->assertTrue($scope->isBrand());
        $this->assertFalse($scope->isGlobal());
    }

    public function test_global_scope_never_contains_brand_identity(): void
    {
        $scope = SettingsScope::forGlobal('en');

        $this->assertSame('global', $scope->type());
        $this->assertSame('global', $scope->scopeKey());
        $this->assertNull($scope->brand());
        $this->assertNull($scope->brandId());
        $this->assertNull($scope->brandUuid());
        $this->assertTrue($scope->isGlobal());
        $this->assertFalse($scope->isBrand());
    }

    public function test_scope_has_no_public_mutator_methods(): void
    {
        $reflection = new \ReflectionClass(SettingsScope::class);

        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            $this->assertFalse(
                str_starts_with($method->getName(), 'set'),
                $method->getName()
            );
        }
    }

    private function brand(): Brand
    {
        $brand = new Brand([
            'uuid' => 'e0802a5c-9ae4-4583-9534-d6c9281be008',
            'code' => 'maac',
            'name' => 'MAAC',
            'default_locale' => 'en',
            'status' => 'active',
        ]);
        $brand->id = 1;
        $brand->exists = true;

        return $brand;
    }
}
