<?php

namespace Tests\Unit\Brands;

use App\Models\Brand;
use App\Models\BrandDomain;
use App\Services\Brands\BrandContextResolver;
use App\Services\Brands\HostnameNormalizer;
use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\Repository;
use Closure;
use PHPUnit\Framework\TestCase;

class BrandContextResolverTest extends TestCase
{
    public function test_it_resolves_and_positive_caches_an_active_brand_domain(): void
    {
        $lookups = 0;
        $resolver = $this->resolver(function (string $hostname) use (&$lookups) {
            $lookups++;

            return $hostname === 'maacdurgapur.local'
                ? $this->activeDomain()
                : null;
        });

        $first = $resolver->resolveHostname('MAACDURGAPUR.LOCAL:80', 'http');
        $second = $resolver->resolveHostname('maacdurgapur.local', 'http');

        $this->assertNotNull($first);
        $this->assertNotNull($second);
        $this->assertSame('maac', $first->brand()->code);
        $this->assertSame('maacdurgapur.local', $first->hostname());
        $this->assertSame(1, $lookups);
    }

    public function test_it_negative_caches_an_unknown_hostname(): void
    {
        $lookups = 0;
        $cache = new Repository(new ArrayStore());
        $lookup = function () use (&$lookups) {
            $lookups++;

            return null;
        };

        $firstResolver = new BrandContextResolver(
            new HostnameNormalizer(),
            $cache,
            $lookup
        );
        $secondResolver = new BrandContextResolver(
            new HostnameNormalizer(),
            $cache,
            $lookup
        );

        $this->assertNull($firstResolver->resolveHostname('unknown.local'));
        $this->assertNull($secondResolver->resolveHostname('unknown.local'));
        $this->assertSame(1, $lookups);
    }

    public function test_it_rejects_inactive_domain_or_brand_results(): void
    {
        $inactiveDomain = $this->activeDomain();
        $inactiveDomain->status = 'inactive';

        $inactiveBrand = $this->activeDomain();
        $inactiveBrand->brand->status = 'inactive';

        $domainResolver = $this->resolver(fn () => $inactiveDomain);
        $brandResolver = $this->resolver(fn () => $inactiveBrand);

        $this->assertNull($domainResolver->resolveHostname('maacdurgapur.local'));
        $this->assertNull($brandResolver->resolveHostname('maacdurgapur.local'));
    }

    public function test_forget_hostname_invalidates_shared_and_request_cache(): void
    {
        $lookups = 0;
        $resolver = $this->resolver(function () use (&$lookups) {
            $lookups++;

            return $this->activeDomain();
        });

        $resolver->resolveHostname('maacdurgapur.local');
        $resolver->forgetHostname('maacdurgapur.local');
        $resolver->resolveHostname('maacdurgapur.local');

        $this->assertSame(2, $lookups);
    }

    private function resolver(callable $lookup): BrandContextResolver
    {
        return new BrandContextResolver(
            new HostnameNormalizer(),
            new Repository(new ArrayStore()),
            Closure::fromCallable($lookup)
        );
    }

    private function activeDomain(): BrandDomain
    {
        $brand = new Brand([
            'uuid' => 'e0802a5c-9ae4-4583-9534-d6c9281be008',
            'code' => 'maac',
            'name' => 'MAAC',
            'slug' => 'maac',
            'default_locale' => 'en',
            'timezone' => 'Asia/Calcutta',
            'status' => 'active',
            'is_primary' => true,
        ]);
        $brand->id = 1;
        $brand->exists = true;

        $domain = new BrandDomain([
            'brand_id' => 1,
            'hostname' => 'maacdurgapur.local',
            'scheme' => 'http',
            'is_primary' => true,
            'is_preview' => false,
            'redirect_to_primary' => false,
            'status' => 'active',
        ]);
        $domain->id = 1;
        $domain->exists = true;
        $domain->setRelation('brand', $brand);

        return $domain;
    }
}
