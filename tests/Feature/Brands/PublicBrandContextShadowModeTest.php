<?php

namespace Tests\Feature\Brands;

use App\Http\Middleware\ResolvePublicBrandContext;
use Tests\TestCase;

class PublicBrandContextShadowModeTest extends TestCase
{
    public function test_public_routes_receive_shadow_mode_middleware(): void
    {
        $middleware = $this->middlewareForRoute('home');

        $this->assertContains(ResolvePublicBrandContext::class, $middleware);
        $this->assertContains('web', $middleware);
    }

    public function test_admin_routes_do_not_receive_public_context_middleware(): void
    {
        $this->assertNotContains(
            ResolvePublicBrandContext::class,
            $this->middlewareForRoute('admin_login')
        );
        $this->assertNotContains(
            ResolvePublicBrandContext::class,
            $this->middlewareForRoute('admin::dashboard')
        );
    }

    public function test_api_routes_do_not_receive_public_context_middleware(): void
    {
        $apiUserRoute = collect($this->app['router']->getRoutes()->getRoutes())
            ->first(static fn ($route) => $route->uri() === 'api/user');

        $this->assertNotNull($apiUserRoute);
        $this->assertNotContains(
            ResolvePublicBrandContext::class,
            $apiUserRoute->gatherMiddleware()
        );
    }

    private function middlewareForRoute(string $routeName): array
    {
        $route = $this->app['router']->getRoutes()->getByName($routeName);

        $this->assertNotNull($route);

        return $route->gatherMiddleware();
    }
}
