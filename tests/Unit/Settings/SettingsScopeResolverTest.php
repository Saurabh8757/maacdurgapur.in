<?php

namespace Tests\Unit\Settings;

use App\Models\Brand;
use App\Models\User;
use App\Services\Brands\AdminBrandContext;
use App\Services\Brands\BrandContextManager;
use App\Services\Settings\Exceptions\SettingsScopeException;
use App\Services\Settings\SettingsLocaleResolver;
use App\Services\Settings\SettingsScopeResolver;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Auth\Guard;
use Mockery;
use PHPUnit\Framework\TestCase;

class SettingsScopeResolverTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    /**
     * @dataProvider brandProvider
     */
    public function test_admin_context_resolves_exact_brand_scope(
        int $id,
        string $uuid,
        string $code
    ): void {
        $user = $this->user();
        $brand = $this->brand($id, $uuid, $code);
        $manager = new BrandContextManager();
        $manager->setAdminContext(new AdminBrandContext(
            $brand,
            (int) $user->id,
            'explicit_switch',
            CarbonImmutable::now(),
            true
        ));
        $locale = Mockery::mock(SettingsLocaleResolver::class);
        $locale->shouldReceive('resolveBrand')
            ->once()
            ->with($brand, null)
            ->andReturn('en');

        $scope = (new SettingsScopeResolver(
            $manager,
            $locale,
            $this->guard($user)
        ))->brandScope();

        $this->assertSame($brand, $scope->brand());
        $this->assertSame("brand:{$uuid}", $scope->scopeKey());
    }

    public function test_missing_admin_context_has_no_maac_fallback(): void
    {
        $this->expectException(SettingsScopeException::class);
        $this->expectExceptionMessage('Admin Brand Context');

        (new SettingsScopeResolver(
            new BrandContextManager(),
            Mockery::mock(SettingsLocaleResolver::class),
            $this->guard($this->user())
        ))->brandScope();
    }

    public function test_context_user_mismatch_is_rejected(): void
    {
        $manager = new BrandContextManager();
        $manager->setAdminContext(new AdminBrandContext(
            $this->brand(
                1,
                'e0802a5c-9ae4-4583-9534-d6c9281be008',
                'maac'
            ),
            999,
            'explicit_switch',
            CarbonImmutable::now(),
            false
        ));

        $this->expectException(SettingsScopeException::class);
        $this->expectExceptionMessage('does not belong');

        (new SettingsScopeResolver(
            $manager,
            Mockery::mock(SettingsLocaleResolver::class),
            $this->guard($this->user())
        ))->brandScope();
    }

    public function test_global_scope_ignores_selected_admin_brand(): void
    {
        $locale = Mockery::mock(SettingsLocaleResolver::class);
        $locale->shouldReceive('resolveGlobal')
            ->once()
            ->with('en')
            ->andReturn('en');

        $scope = (new SettingsScopeResolver(
            new BrandContextManager(),
            $locale,
            $this->guard(null)
        ))->globalScope('en');

        $this->assertTrue($scope->isGlobal());
        $this->assertNull($scope->brand());
    }

    public function test_unknown_scope_is_rejected(): void
    {
        $this->expectException(SettingsScopeException::class);

        (new SettingsScopeResolver(
            new BrandContextManager(),
            Mockery::mock(SettingsLocaleResolver::class),
            $this->guard($this->user())
        ))->resolve('brand:2');
    }

    public function brandProvider(): array
    {
        return [
            'MAAC' => [1, 'e0802a5c-9ae4-4583-9534-d6c9281be008', 'maac'],
            'AKSHA' => [2, '43f324a6-7024-46bb-8d86-4f4efa51b305', 'aksha'],
            'Space-E-Fic' => [3, '978285a3-54d8-477b-89d7-8589e6d59d1b', 'space_e_fic'],
        ];
    }

    private function guard(?User $user): Guard
    {
        $guard = Mockery::mock(Guard::class);
        $guard->shouldReceive('user')->andReturn($user);

        return $guard;
    }

    private function user(): User
    {
        $user = new User(['name' => 'Administrator']);
        $user->id = 6;
        $user->exists = true;

        return $user;
    }

    private function brand(int $id, string $uuid, string $code): Brand
    {
        $brand = new Brand([
            'uuid' => $uuid,
            'code' => $code,
            'name' => strtoupper($code),
            'default_locale' => 'en',
            'status' => 'active',
        ]);
        $brand->id = $id;
        $brand->exists = true;

        return $brand;
    }
}
