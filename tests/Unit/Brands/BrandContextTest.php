<?php

namespace Tests\Unit\Brands;

use App\Models\Brand;
use App\Models\BrandDomain;
use App\Services\Brands\BrandContext;
use PHPUnit\Framework\TestCase;

class BrandContextTest extends TestCase
{
    public function test_it_exposes_resolved_brand_domain_flags(): void
    {
        $brand = new Brand([
            'code' => 'maac',
            'name' => 'MAAC',
            'status' => 'active',
        ]);
        $domain = new BrandDomain([
            'hostname' => 'maacdurgapur.local',
            'scheme' => 'http',
            'is_primary' => true,
            'is_preview' => false,
            'redirect_to_primary' => false,
            'status' => 'active',
        ]);

        $context = new BrandContext(
            $brand,
            $domain,
            'maacdurgapur.local',
            'http'
        );

        $this->assertSame($brand, $context->brand());
        $this->assertSame($domain, $context->domain());
        $this->assertSame('maacdurgapur.local', $context->hostname());
        $this->assertSame('http', $context->scheme());
        $this->assertSame('hostname', $context->source());
        $this->assertTrue($context->isPrimaryDomain());
        $this->assertFalse($context->isPreview());
        $this->assertFalse($context->shouldRedirectToPrimary());
    }
}
