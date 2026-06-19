<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\TrustHosts;
use App\Services\Brands\BrandContextResolver;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class TrustHostsTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    /**
     * @dataProvider localCompatibilityHostProvider
     */
    public function test_local_compatibility_hosts_bypass_brand_resolution(
        string $hostname
    ): void {
        $this->app->detectEnvironment(static fn () => 'local');

        $resolver = Mockery::mock(BrandContextResolver::class);
        $resolver->shouldNotReceive('resolveHostname');

        $middleware = new TrustHosts($this->app, $resolver);
        $request = Request::create('http://'.$hostname.'/admin-login');

        $response = $middleware->handle(
            $request,
            static fn () => response('trusted')
        );

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('trusted', $response->getContent());
    }

    public function localCompatibilityHostProvider(): array
    {
        return [
            'localhost' => ['localhost'],
            'loopback IPv4' => ['127.0.0.1'],
        ];
    }
}
