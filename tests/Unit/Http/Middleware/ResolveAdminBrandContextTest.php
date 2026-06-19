<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\ResolveAdminBrandContext;
use App\Services\Brands\AdminBrandContext;
use App\Services\Brands\AdminBrandContextResolver;
use App\Services\Brands\BrandContextManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery;
use PHPUnit\Framework\TestCase;

class ResolveAdminBrandContextTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_it_binds_context_and_preserves_downstream_response(): void
    {
        $request = Request::create('/v1/cpanel/admin/dashboard');
        $context = Mockery::mock(AdminBrandContext::class);
        $resolver = Mockery::mock(AdminBrandContextResolver::class);
        $manager = Mockery::mock(BrandContextManager::class);
        $response = new Response('existing admin response', 200);

        $resolver->shouldReceive('resolve')
            ->once()
            ->with($request)
            ->andReturn($context);
        $manager->shouldReceive('setAdminContext')
            ->once()
            ->with($context);

        $result = (new ResolveAdminBrandContext($resolver, $manager))->handle(
            $request,
            static fn () => $response
        );

        $this->assertSame($response, $result);
    }

    public function test_it_continues_when_no_authorized_context_exists(): void
    {
        $request = Request::create('/v1/cpanel/admin/dashboard');
        $resolver = Mockery::mock(AdminBrandContextResolver::class);
        $manager = Mockery::mock(BrandContextManager::class);

        $resolver->shouldReceive('resolve')->once()->andReturnNull();
        $manager->shouldNotReceive('setAdminContext');

        $response = (new ResolveAdminBrandContext($resolver, $manager))->handle(
            $request,
            static fn () => new Response('continued')
        );

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('continued', $response->getContent());
    }
}
