<?php

namespace Tests\Unit\Brands;

use App\Services\Brands\BrandDomainCache;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Mockery;
use PHPUnit\Framework\TestCase;

class BrandDomainCacheTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_it_uses_configured_positive_and_negative_ttls(): void
    {
        $cache = Mockery::mock(CacheRepository::class);
        $domainCache = new BrandDomainCache(
            $cache,
            'brand-domain:v1:',
            600,
            60
        );
        $payload = ['resolved' => true, 'hostname' => 'maacdurgapur.local'];

        $cache->shouldReceive('put')
            ->once()
            ->with('brand-domain:v1:maacdurgapur.local', $payload, 600);
        $cache->shouldReceive('put')
            ->once()
            ->with(
                'brand-domain:v1:unknown.local',
                ['resolved' => false],
                60
            );

        $domainCache->putResolved('maacdurgapur.local', $payload);
        $domainCache->putUnresolved('unknown.local');

        $this->assertSame(600, $domainCache->positiveTtlSeconds());
        $this->assertSame(60, $domainCache->negativeTtlSeconds());
    }

    public function test_it_reads_and_forgets_namespaced_entries(): void
    {
        $cache = Mockery::mock(CacheRepository::class);
        $domainCache = new BrandDomainCache(
            $cache,
            'brand-domain:v1:',
            600,
            60
        );
        $payload = ['resolved' => true];

        $cache->shouldReceive('get')
            ->once()
            ->with('brand-domain:v1:maacdurgapur.local')
            ->andReturn($payload);
        $cache->shouldReceive('forget')
            ->once()
            ->with('brand-domain:v1:maacdurgapur.local')
            ->andReturn(true);

        $this->assertSame(
            $payload,
            $domainCache->get('maacdurgapur.local')
        );

        $domainCache->forget('maacdurgapur.local');
    }

    public function test_it_ignores_non_array_cache_values(): void
    {
        $cache = Mockery::mock(CacheRepository::class);
        $domainCache = new BrandDomainCache(
            $cache,
            'brand-domain:v1:',
            600,
            60
        );

        $cache->shouldReceive('get')->once()->andReturn('invalid');

        $this->assertNull($domainCache->get('maacdurgapur.local'));
    }
}
