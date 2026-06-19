<?php

namespace Tests\Feature\Admin;

use App\Models\Brand;
use App\Models\User;
use App\Services\Brands\AdminBrandAccessResolver;
use App\Services\Brands\AdminBrandContext;
use App\Services\Brands\AdminBrandContextResolver;
use Carbon\CarbonImmutable;
use Mockery;
use Tests\TestCase;

class AdminBrandSwitchTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_authenticated_admin_can_switch_to_authorized_brand(): void
    {
        $user = $this->adminUser();
        $resolver = Mockery::mock(AdminBrandContextResolver::class);

        $resolver->shouldReceive('resolve')
            ->once()
            ->andReturnNull();
        $resolver->shouldReceive('select')
            ->once()
            ->with(
                Mockery::type('Illuminate\Http\Request'),
                Mockery::on(static fn ($candidate) => $candidate->is($user)),
                '43f324a6-7024-46bb-8d86-4f4efa51b305'
            )
            ->andReturn(Mockery::mock(AdminBrandContext::class));
        $this->app->instance(AdminBrandContextResolver::class, $resolver);

        $this->actingAs($user)
            ->post('http://maacdurgapur.local/v1/cpanel/admin/brand-context', [
                'brand_uuid' => '43f324a6-7024-46bb-8d86-4f4efa51b305',
            ])
            ->assertRedirect(
                'http://maacdurgapur.local/v1/cpanel/admin/dashboard'
            );
    }

    public function test_inaccessible_brand_switch_returns_forbidden(): void
    {
        $user = $this->adminUser();
        $resolver = Mockery::mock(AdminBrandContextResolver::class);

        $resolver->shouldReceive('resolve')
            ->once()
            ->andReturnNull();
        $resolver->shouldReceive('select')
            ->once()
            ->andReturnNull();
        $this->app->instance(AdminBrandContextResolver::class, $resolver);

        $this->actingAs($user)
            ->post('http://maacdurgapur.local/v1/cpanel/admin/brand-context', [
                'brand_uuid' => '43f324a6-7024-46bb-8d86-4f4efa51b305',
            ])
            ->assertForbidden();
    }

    public function test_invalid_brand_identifier_is_rejected(): void
    {
        $user = $this->adminUser();
        $resolver = Mockery::mock(AdminBrandContextResolver::class);

        $resolver->shouldReceive('resolve')
            ->once()
            ->andReturnNull();
        $resolver->shouldNotReceive('select');
        $this->app->instance(AdminBrandContextResolver::class, $resolver);

        $this->actingAs($user)
            ->post('http://maacdurgapur.local/v1/cpanel/admin/brand-context', [
                'brand_uuid' => 'not-a-uuid',
            ])
            ->assertSessionHasErrors('brand_uuid');
    }

    public function test_dashboard_renders_authorized_brand_switcher(): void
    {
        $user = $this->adminUser();
        $maac = $this->brand(
            1,
            'e0802a5c-9ae4-4583-9534-d6c9281be008',
            'maac',
            'MAAC'
        );
        $aksha = $this->brand(
            2,
            '43f324a6-7024-46bb-8d86-4f4efa51b305',
            'aksha',
            'AKSHA'
        );
        $context = new AdminBrandContext(
            $maac,
            (int) $user->id,
            'user_default',
            CarbonImmutable::now(),
            true
        );
        $resolver = Mockery::mock(AdminBrandContextResolver::class);
        $access = Mockery::mock(AdminBrandAccessResolver::class);

        $resolver->shouldReceive('resolve')->once()->andReturn($context);
        $access->shouldReceive('accessibleBrands')
            ->once()
            ->with(Mockery::on(static fn ($candidate) => $candidate->is($user)))
            ->andReturn(collect([$maac, $aksha]));
        $this->app->instance(AdminBrandContextResolver::class, $resolver);
        $this->app->instance(AdminBrandAccessResolver::class, $access);

        $this->actingAs($user)
            ->get('http://maacdurgapur.local/v1/cpanel/admin/dashboard')
            ->assertOk()
            ->assertSee('admin-brand-context')
            ->assertSee('MAAC')
            ->assertSee('AKSHA');
    }

    private function adminUser(): User
    {
        $user = new User([
            'name' => 'Test Administrator',
            'email' => 'admin-context@example.test',
        ]);
        $user->id = 9001;
        $user->user_type = 'Admin';
        $user->slug_name = 'test-administrator-9001';
        $user->profile_picture = 'default.png';
        $user->exists = true;

        return $user;
    }

    private function brand(
        int $id,
        string $uuid,
        string $code,
        string $name
    ): Brand {
        $brand = new Brand([
            'uuid' => $uuid,
            'code' => $code,
            'name' => $name,
            'status' => 'active',
            'is_primary' => $code === 'maac',
        ]);
        $brand->id = $id;
        $brand->exists = true;

        return $brand;
    }
}
