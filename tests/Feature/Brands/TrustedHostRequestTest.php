<?php

namespace Tests\Feature\Brands;

use App\Services\Brands\BrandContext;
use App\Services\Brands\BrandContextResolver;
use Illuminate\Support\Facades\Route;
use Mockery;
use Tests\TestCase;

class TrustedHostRequestTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::get('/_host-validation-test', static function () {
            return response('trusted');
        });
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_active_brand_domain_is_accepted(): void
    {
        $resolver = Mockery::mock(BrandContextResolver::class);
        $resolver->shouldReceive('resolveHostname')
            ->once()
            ->with('maacdurgapur.local', 'http')
            ->andReturn(Mockery::mock(BrandContext::class));
        $this->app->instance(BrandContextResolver::class, $resolver);

        $this->get('http://maacdurgapur.local/_host-validation-test')
            ->assertOk()
            ->assertSeeText('trusted');
    }

}
