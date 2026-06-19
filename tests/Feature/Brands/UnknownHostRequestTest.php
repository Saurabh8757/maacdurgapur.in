<?php

namespace Tests\Feature\Brands;

use App\Services\Brands\BrandContextResolver;
use Illuminate\Support\Facades\Route;
use InvalidArgumentException;
use Mockery;
use Tests\TestCase;

class UnknownHostRequestTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::get('/_host-validation-test', static function () {
            return response('route reached');
        });
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_unknown_host_returns_not_found(): void
    {
        $resolver = Mockery::mock(BrandContextResolver::class);
        $resolver->shouldReceive('resolveHostname')
            ->once()
            ->with('unknown.local', 'http')
            ->andReturnNull();
        $this->app->instance(BrandContextResolver::class, $resolver);

        $this->get('http://unknown.local/_host-validation-test')
            ->assertNotFound()
            ->assertDontSeeText('route reached');
    }

    public function test_valid_domain_suffix_does_not_bypass_validation(): void
    {
        $hostname = 'maacdurgapur.local.attacker.test';
        $resolver = Mockery::mock(BrandContextResolver::class);
        $resolver->shouldReceive('resolveHostname')
            ->once()
            ->with($hostname, 'http')
            ->andReturnNull();
        $this->app->instance(BrandContextResolver::class, $resolver);

        $this->get('http://'.$hostname.'/_host-validation-test')
            ->assertNotFound();
    }

    public function test_invalid_hostname_returns_not_found(): void
    {
        $resolver = Mockery::mock(BrandContextResolver::class);
        $resolver->shouldReceive('resolveHostname')
            ->once()
            ->andThrow(new InvalidArgumentException('Invalid hostname.'));
        $this->app->instance(BrandContextResolver::class, $resolver);

        $this->get('http://127.0.0.2/_host-validation-test')
            ->assertNotFound();
    }

    public function test_malformed_host_header_returns_not_found(): void
    {
        $resolver = Mockery::mock(BrandContextResolver::class);
        $resolver->shouldReceive('resolveHostname')
            ->once()
            ->andThrow(new InvalidArgumentException('Invalid hostname.'));
        $this->app->instance(BrandContextResolver::class, $resolver);

        $this->withServerVariables(['HTTP_HOST' => 'invalid_host'])
            ->call('GET', '/_host-validation-test')
            ->assertNotFound();
    }

    public function test_local_compatibility_hosts_are_not_allowed_outside_local(): void
    {
        config(['app.env' => 'production']);

        $resolver = Mockery::mock(BrandContextResolver::class);
        $resolver->shouldReceive('resolveHostname')
            ->once()
            ->with('localhost', 'http')
            ->andReturnNull();
        $this->app->instance(BrandContextResolver::class, $resolver);

        $this->get('http://localhost/_host-validation-test')
            ->assertNotFound();
    }
}
