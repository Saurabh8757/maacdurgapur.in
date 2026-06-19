<?php

namespace Tests\Unit\Brands;

use App\Models\Brand;
use App\Models\User;
use App\Services\Brands\AdminBrandAccessResolver;
use App\Services\Brands\AdminBrandContextResolver;
use Illuminate\Http\Request;
use Illuminate\Session\ArraySessionHandler;
use Illuminate\Session\Store;
use Mockery;
use Tests\TestCase;

class AdminBrandContextResolverTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_it_initializes_exact_session_context_from_user_default(): void
    {
        $user = $this->user();
        $maac = $this->brand(1, 'maac', true);
        $aksha = $this->brand(2, 'aksha');
        $access = Mockery::mock(AdminBrandAccessResolver::class);

        $access->shouldReceive('accessibleBrands')
            ->once()
            ->with($user)
            ->andReturn(collect([$maac, $aksha]));
        $access->shouldReceive('defaultAccessibleBrand')
            ->once()
            ->with($user, Mockery::type('Illuminate\Support\Collection'))
            ->andReturn($maac);
        $access->shouldReceive('hasActiveGlobalSuperAdminAssignment')
            ->once()
            ->with($user)
            ->andReturn(true);

        $request = $this->request($user);
        $context = (new AdminBrandContextResolver($access))->resolve($request);
        $stored = $request->session()->get('admin_brand_context');

        $this->assertSame($maac, $context->brand());
        $this->assertSame('user_default', $context->source());
        $this->assertTrue($context->isGlobalSuperAdmin());
        $this->assertSame(
            ['brand_id', 'brand_uuid', 'user_id', 'source', 'selected_at'],
            array_keys($stored)
        );
        $this->assertSame(1, $stored['brand_id']);
        $this->assertSame($maac->uuid, $stored['brand_uuid']);
        $this->assertSame(6, $stored['user_id']);
        $this->assertSame('user_default', $stored['source']);
    }

    public function test_valid_stored_selection_is_reused_without_session_rewrite(): void
    {
        $user = $this->user();
        $aksha = $this->brand(2, 'aksha');
        $access = Mockery::mock(AdminBrandAccessResolver::class);
        $request = $this->request($user);
        $stored = [
            'brand_id' => 2,
            'brand_uuid' => $aksha->uuid,
            'user_id' => 6,
            'source' => 'explicit_switch',
            'selected_at' => '2026-06-19T12:00:00+05:30',
        ];
        $request->session()->put('admin_brand_context', $stored);

        $access->shouldReceive('accessibleBrands')
            ->once()
            ->andReturn(collect([$aksha]));
        $access->shouldReceive('hasActiveGlobalSuperAdminAssignment')
            ->once()
            ->andReturn(true);
        $access->shouldNotReceive('defaultAccessibleBrand');

        $context = (new AdminBrandContextResolver($access))->resolve($request);

        $this->assertSame($aksha, $context->brand());
        $this->assertSame('explicit_switch', $context->source());
        $this->assertSame(
            $stored,
            $request->session()->get('admin_brand_context')
        );
    }

    public function test_legacy_admin_without_rbac_access_receives_no_context(): void
    {
        $user = $this->user();
        $access = Mockery::mock(AdminBrandAccessResolver::class);
        $request = $this->request($user);
        $request->session()->put('admin_brand_context', [
            'brand_id' => 1,
            'brand_uuid' => 'stale',
            'user_id' => 6,
            'source' => 'user_default',
            'selected_at' => now()->toIso8601String(),
        ]);

        $access->shouldReceive('accessibleBrands')
            ->once()
            ->with($user)
            ->andReturn(collect());

        $context = (new AdminBrandContextResolver($access))->resolve($request);

        $this->assertNull($context);
        $this->assertFalse(
            $request->session()->has('admin_brand_context')
        );
    }

    public function test_explicit_switch_rejects_inaccessible_brand(): void
    {
        $user = $this->user();
        $maac = $this->brand(1, 'maac');
        $access = Mockery::mock(AdminBrandAccessResolver::class);
        $request = $this->request($user);

        $access->shouldReceive('accessibleBrands')
            ->once()
            ->with($user)
            ->andReturn(collect([$maac]));

        $context = (new AdminBrandContextResolver($access))->select(
            $request,
            $user,
            '43f324a6-7024-46bb-8d86-4f4efa51b305'
        );

        $this->assertNull($context);
        $this->assertFalse(
            $request->session()->has('admin_brand_context')
        );
    }

    private function request(User $user): Request
    {
        $request = Request::create('/v1/cpanel/admin/dashboard');
        $request->setLaravelSession(
            new Store('test', new ArraySessionHandler(120))
        );
        $request->setUserResolver(static fn () => $user);

        return $request;
    }

    private function user(): User
    {
        $user = new User(['name' => 'Administrator']);
        $user->id = 6;
        $user->exists = true;

        return $user;
    }

    private function brand(
        int $id,
        string $code,
        bool $primary = false
    ): Brand {
        $brand = new Brand([
            'uuid' => match ($code) {
                'maac' => 'e0802a5c-9ae4-4583-9534-d6c9281be008',
                'aksha' => '43f324a6-7024-46bb-8d86-4f4efa51b305',
                default => '978285a3-54d8-477b-89d7-8589e6d59d1b',
            },
            'code' => $code,
            'name' => strtoupper($code),
            'status' => 'active',
            'is_primary' => $primary,
        ]);
        $brand->id = $id;
        $brand->exists = true;

        return $brand;
    }
}
