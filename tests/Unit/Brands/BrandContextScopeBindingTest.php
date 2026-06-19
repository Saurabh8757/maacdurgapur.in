<?php

namespace Tests\Unit\Brands;

use App\Services\Brands\BrandContextManager;
use App\Services\Brands\BrandContextResolver;
use App\Services\Brands\BrandDomainCache;
use Tests\TestCase;

class BrandContextScopeBindingTest extends TestCase
{
    public function test_context_manager_is_scoped_and_resets_between_lifecycles(): void
    {
        $first = $this->app->make(BrandContextManager::class);
        $sameScope = $this->app->make(BrandContextManager::class);

        $this->assertSame($first, $sameScope);

        $this->app->forgetScopedInstances();

        $nextScope = $this->app->make(BrandContextManager::class);

        $this->assertNotSame($first, $nextScope);
        $this->assertNull($nextScope->publicContext());
    }

    public function test_resolver_is_scoped_but_domain_cache_is_shared(): void
    {
        $firstResolver = $this->app->make(BrandContextResolver::class);
        $sameResolver = $this->app->make(BrandContextResolver::class);
        $firstCache = $this->app->make(BrandDomainCache::class);

        $this->assertSame($firstResolver, $sameResolver);

        $this->app->forgetScopedInstances();

        $nextResolver = $this->app->make(BrandContextResolver::class);
        $nextCache = $this->app->make(BrandDomainCache::class);

        $this->assertNotSame($firstResolver, $nextResolver);
        $this->assertSame($firstCache, $nextCache);
    }

    public function test_container_uses_configured_cache_ttls(): void
    {
        $cache = $this->app->make(BrandDomainCache::class);

        $this->assertSame(600, $cache->positiveTtlSeconds());
        $this->assertSame(60, $cache->negativeTtlSeconds());
    }
}
