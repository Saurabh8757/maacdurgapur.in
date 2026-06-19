<?php

namespace Tests\Unit\Settings;

use App\Models\Brand;
use App\Models\User;
use App\Services\Brands\AdminBrandContext;
use App\Services\Brands\BrandContextManager;
use App\Services\Settings\SettingsAuthorizationService;
use App\Services\Settings\SettingsLocaleResolver;
use App\Services\Settings\SettingsNavigationService;
use App\Services\Settings\SettingsScope;
use Carbon\CarbonImmutable;
use Mockery;
use Tests\TestCase;

class SettingsNavigationServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_brand_navigation_requires_matching_admin_context_and_permission(): void
    {
        $user = $this->user();
        $brand = $this->brand();
        $manager = new BrandContextManager();
        $manager->setAdminContext(new AdminBrandContext(
            $brand,
            $user->id,
            'primary_brand',
            CarbonImmutable::now(),
            true
        ));

        $authorization = Mockery::mock(SettingsAuthorizationService::class);
        $authorization->shouldReceive('allows')
            ->once()
            ->with(
                $user,
                Mockery::on(fn (SettingsScope $scope) =>
                    $scope->scopeKey() === "brand:{$brand->uuid}"
                ),
                'view'
            )
            ->andReturn(true);

        $service = new SettingsNavigationService(
            $authorization,
            $manager,
            new SettingsLocaleResolver()
        );

        $this->assertTrue($service->canViewBrand($user));
    }

    public function test_brand_navigation_is_hidden_without_context(): void
    {
        $authorization = Mockery::mock(SettingsAuthorizationService::class);
        $authorization->shouldNotReceive('allows');

        $service = new SettingsNavigationService(
            $authorization,
            new BrandContextManager(),
            new SettingsLocaleResolver()
        );

        $this->assertFalse($service->canViewBrand($this->user()));
    }

    public function test_global_navigation_uses_global_view_permission(): void
    {
        $user = $this->user();
        $authorization = Mockery::mock(SettingsAuthorizationService::class);
        $authorization->shouldReceive('allows')
            ->once()
            ->with(
                $user,
                Mockery::on(fn (SettingsScope $scope) => $scope->isGlobal()),
                'view'
            )
            ->andReturn(true);

        $service = new SettingsNavigationService(
            $authorization,
            new BrandContextManager(),
            new SettingsLocaleResolver()
        );

        $this->assertTrue($service->canViewGlobal($user));
    }

    private function user(): User
    {
        $user = new User(['name' => 'Administrator']);
        $user->id = 6;
        $user->exists = true;

        return $user;
    }

    private function brand(): Brand
    {
        $brand = new Brand([
            'uuid' => 'e0802a5c-9ae4-4583-9534-d6c9281be008',
            'name' => 'MAAC',
            'default_locale' => 'en',
        ]);
        $brand->id = 1;
        $brand->exists = true;

        return $brand;
    }
}
