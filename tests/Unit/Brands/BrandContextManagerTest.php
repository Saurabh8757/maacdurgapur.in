<?php

namespace Tests\Unit\Brands;

use App\Models\Brand;
use App\Models\BrandDomain;
use App\Services\Brands\BrandContext;
use App\Services\Brands\BrandContextManager;
use App\Services\Brands\AdminBrandContext;
use Carbon\CarbonImmutable;
use LogicException;
use PHPUnit\Framework\TestCase;

class BrandContextManagerTest extends TestCase
{
    public function test_it_stores_and_requires_public_context(): void
    {
        $manager = new BrandContextManager();
        $context = $this->context(1, 'maac');

        $this->assertNull($manager->publicContext());

        $manager->setPublicContext($context);

        $this->assertSame($context, $manager->publicContext());
        $this->assertSame($context, $manager->requirePublicContext());
    }

    public function test_setting_the_same_brand_context_is_idempotent(): void
    {
        $manager = new BrandContextManager();
        $first = $this->context(1, 'maac');
        $second = $this->context(1, 'maac');

        $manager->setPublicContext($first);
        $manager->setPublicContext($second);

        $this->assertSame($second, $manager->publicContext());
    }

    public function test_it_rejects_a_different_brand_in_the_same_scope(): void
    {
        $manager = new BrandContextManager();
        $manager->setPublicContext($this->context(1, 'maac'));

        $this->expectException(LogicException::class);

        $manager->setPublicContext($this->context(2, 'aksha'));
    }

    public function test_require_throws_when_context_is_missing(): void
    {
        $this->expectException(LogicException::class);

        (new BrandContextManager())->requirePublicContext();
    }

    public function test_public_and_admin_contexts_are_independent(): void
    {
        $manager = new BrandContextManager();
        $publicContext = $this->context(1, 'maac');
        $brand = new Brand([
            'code' => 'aksha',
            'name' => 'AKSHA',
            'status' => 'active',
        ]);
        $brand->id = 2;
        $adminContext = new AdminBrandContext(
            $brand,
            6,
            'explicit_switch',
            CarbonImmutable::now(),
            true
        );

        $manager->setPublicContext($publicContext);
        $manager->setAdminContext($adminContext);

        $this->assertSame($publicContext, $manager->requirePublicContext());
        $this->assertSame($adminContext, $manager->requireAdminContext());
    }

    private function context(int $brandId, string $code): BrandContext
    {
        $brand = new Brand([
            'code' => $code,
            'name' => strtoupper($code),
            'status' => 'active',
        ]);
        $brand->id = $brandId;

        $domain = new BrandDomain([
            'hostname' => "{$code}.local",
            'scheme' => 'http',
            'status' => 'active',
        ]);

        return new BrandContext(
            $brand,
            $domain,
            "{$code}.local",
            'http'
        );
    }
}
