<?php

namespace Tests\Unit\Settings;

use App\Models\Brand;
use App\Models\SettingDefinition;
use App\Models\User;
use App\Services\Authorization\PermissionDecision;
use App\Services\Authorization\PermissionResolver;
use App\Services\Settings\Exceptions\SettingsAuthorizationException;
use App\Services\Settings\SettingsAuthorizationService;
use App\Services\Settings\SettingsScope;
use Mockery;
use PHPUnit\Framework\TestCase;

class SettingsAuthorizationServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    /**
     * @dataProvider permissionMappingProvider
     */
    public function test_operations_map_to_exact_permissions(
        string $scopeType,
        string $operation,
        string $permissionCode
    ): void {
        $user = $this->user();
        $scope = $scopeType === 'brand'
            ? SettingsScope::forBrand($this->brand(), 'en')
            : SettingsScope::forGlobal('en');
        $resolver = Mockery::mock(PermissionResolver::class);
        $resolver->shouldReceive('check')
            ->once()
            ->with($user, $permissionCode, $scope->brand())
            ->andReturn($this->allowed($permissionCode, $scope));

        $decision = (new SettingsAuthorizationService($resolver))
            ->decision($user, $scope, $operation);

        $this->assertTrue($decision->allowed);
    }

    public function test_denied_decision_throws_settings_exception(): void
    {
        $user = $this->user();
        $scope = SettingsScope::forBrand($this->brand(), 'en');
        $resolver = Mockery::mock(PermissionResolver::class);
        $resolver->shouldReceive('check')->once()->andReturn(
            PermissionDecision::deny(
                'settings.brand.edit',
                $scope->scopeKey(),
                'permission_not_granted',
                null,
                $scope->brand()
            )
        );

        try {
            (new SettingsAuthorizationService($resolver))
                ->authorize($user, $scope, 'edit');
            $this->fail('Authorization exception was not thrown.');
        } catch (SettingsAuthorizationException $exception) {
            $this->assertSame(
                'settings.brand.edit',
                $exception->permissionCode
            );
            $this->assertSame(
                'permission_not_granted',
                $exception->reason
            );
            $this->assertSame($scope->brandUuid(), $exception->brandUuid);
        }
    }

    public function test_sensitive_edit_requires_ordinary_and_sensitive_permissions(): void
    {
        $user = $this->user();
        $scope = SettingsScope::forBrand($this->brand(), 'en');
        $definition = new SettingDefinition([
            'status' => 'active',
            'is_brand_overridable' => true,
            'is_sensitive' => true,
        ]);
        $resolver = Mockery::mock(PermissionResolver::class);
        $resolver->shouldReceive('check')
            ->once()
            ->with($user, 'settings.brand.edit', $scope->brand())
            ->andReturn($this->allowed('settings.brand.edit', $scope));
        $resolver->shouldReceive('check')
            ->once()
            ->with($user, 'settings.sensitive.edit', $scope->brand())
            ->andReturn($this->allowed('settings.sensitive.edit', $scope));

        (new SettingsAuthorizationService($resolver))->authorizeDefinition(
            $user,
            $scope,
            $definition,
            'edit'
        );

        $this->addToAssertionCount(1);
    }

    public function test_inactive_definition_is_rejected_before_rbac_lookup(): void
    {
        $resolver = Mockery::mock(PermissionResolver::class);
        $resolver->shouldNotReceive('check');

        $this->expectException(SettingsAuthorizationException::class);
        $this->expectExceptionMessage('definition_inactive');

        (new SettingsAuthorizationService($resolver))->authorizeDefinition(
            $this->user(),
            SettingsScope::forGlobal('en'),
            new SettingDefinition(['status' => 'inactive']),
            'view'
        );
    }

    public function test_brand_scope_rejects_non_overridable_definition(): void
    {
        $resolver = Mockery::mock(PermissionResolver::class);
        $resolver->shouldNotReceive('check');

        $this->expectException(SettingsAuthorizationException::class);
        $this->expectExceptionMessage('brand_override_disabled');

        (new SettingsAuthorizationService($resolver))->authorizeDefinition(
            $this->user(),
            SettingsScope::forBrand($this->brand(), 'en'),
            new SettingDefinition([
                'status' => 'active',
                'is_brand_overridable' => false,
            ]),
            'view'
        );
    }

    public function permissionMappingProvider(): array
    {
        return [
            ['brand', 'view', 'settings.brand.view'],
            ['brand', 'edit', 'settings.brand.edit'],
            ['brand', 'submit', 'settings.brand.submit'],
            ['brand', 'approve', 'settings.brand.approve'],
            ['brand', 'publish', 'settings.brand.publish'],
            ['brand', 'rollback', 'settings.brand.rollback'],
            ['global', 'view', 'settings.global.view'],
            ['global', 'edit', 'settings.global.edit'],
            ['global', 'approve', 'settings.global.publish'],
            ['global', 'publish', 'settings.global.publish'],
            ['global', 'rollback', 'settings.global.publish'],
            ['brand', 'view_sensitive', 'settings.sensitive.view'],
            ['global', 'edit_sensitive', 'settings.sensitive.edit'],
            ['brand', 'view_definitions', 'settings.brand.view'],
        ];
    }

    private function allowed(
        string $permissionCode,
        SettingsScope $scope
    ): PermissionDecision {
        return new PermissionDecision(
            true,
            $permissionCode,
            $scope->scopeKey(),
            'granted',
            null,
            null,
            $scope->brand()
        );
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
