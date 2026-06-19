<?php

namespace Tests\Feature\Admin;

use App\Http\Middleware\ResolveAdminBrandContext;
use Tests\TestCase;

class AdminBrandContextRouteTest extends TestCase
{
    public function test_route_inventory_is_exactly_seventy_two(): void
    {
        $this->assertCount(72, $this->app['router']->getRoutes()->getRoutes());
    }

    public function test_all_protected_admin_routes_receive_context_middleware(): void
    {
        $protectedRoutes = collect(
            $this->app['router']->getRoutes()->getRoutes()
        )->filter(
            static fn ($route) => str_starts_with(
                $route->uri(),
                'v1/cpanel/admin'
            )
        );

        $this->assertCount(53, $protectedRoutes);

        foreach ($protectedRoutes as $route) {
            $this->assertContains(
                ResolveAdminBrandContext::class,
                $route->gatherMiddleware(),
                $route->uri()
            );
        }
    }

    public function test_login_and_public_routes_do_not_receive_admin_context(): void
    {
        foreach (['admin_login', 'admin_login_check', 'home'] as $routeName) {
            $route = $this->app['router']->getRoutes()->getByName($routeName);

            $this->assertNotNull($route);
            $this->assertNotContains(
                ResolveAdminBrandContext::class,
                $route->gatherMiddleware()
            );
        }
    }

    public function test_switch_route_is_protected_post_only(): void
    {
        $route = $this->app['router']->getRoutes()->getByName(
            'admin::brand_context.switch'
        );

        $this->assertNotNull($route);
        $this->assertSame(
            ['POST'],
            array_values(array_diff($route->methods(), ['HEAD']))
        );
        $this->assertSame(
            'v1/cpanel/admin/brand-context',
            $route->uri()
        );
        $this->assertContains(
            'AdminMiddleware',
            $route->gatherMiddleware()
        );
    }
}
