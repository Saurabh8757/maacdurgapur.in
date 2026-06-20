<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\ResolvePublicBrandContext;
use App\Services\Brands\BrandContext;
use App\Services\Brands\BrandContextManager;
use App\Services\Brands\BrandContextResolver;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use InvalidArgumentException;
use Mockery;
use PHPUnit\Framework\TestCase;

class ResolvePublicBrandContextTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_it_binds_resolved_context_and_returns_response_unchanged(): void
    {
        $request = Request::create('http://maacdurgapur.local/');
        $context = Mockery::mock(BrandContext::class);
        $resolver = Mockery::mock(BrandContextResolver::class);
        $manager = Mockery::mock(BrandContextManager::class);
        $app = Mockery::mock(Application::class);
        $config = Mockery::mock(ConfigRepository::class);
        $response = new Response(
            'original response',
            207,
            ['X-Shadow' => 'unchanged']
        );
        $attributesBefore = $request->attributes->all();

        $resolver->shouldReceive('resolve')
            ->once()
            ->with($request)
            ->andReturn($context);
        $manager->shouldReceive('setPublicContext')
            ->once()
            ->with($context);

        $middleware = new ResolvePublicBrandContext(
            $resolver,
            $manager,
            $app,
            $config
        );
        $result = $middleware->handle(
            $request,
            static fn () => $response
        );

        $this->assertSame($response, $result);
        $this->assertSame('original response', $result->getContent());
        $this->assertSame(207, $result->getStatusCode());
        $this->assertSame('unchanged', $result->headers->get('X-Shadow'));
        $this->assertSame($attributesBefore, $request->attributes->all());
        $this->assertFalse($request->hasSession());
    }

    public function test_it_continues_without_binding_when_context_is_unresolved(): void
    {
        $request = Request::create('http://localhost/');
        $resolver = Mockery::mock(BrandContextResolver::class);
        $manager = Mockery::mock(BrandContextManager::class);
        $app = Mockery::mock(Application::class);
        $config = Mockery::mock(ConfigRepository::class);

        $resolver->shouldReceive('resolve')
            ->once()
            ->with($request)
            ->andReturnNull();
        $manager->shouldNotReceive('setPublicContext');
        $app->shouldReceive('environment')
            ->once()
            ->with('local')
            ->andReturnFalse();

        $middleware = new ResolvePublicBrandContext(
            $resolver,
            $manager,
            $app,
            $config
        );
        $response = $middleware->handle(
            $request,
            static fn () => new Response('continued')
        );

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('continued', $response->getContent());
        $this->assertFalse($request->hasSession());
    }

    public function test_invalid_operational_hostname_is_a_shadow_mode_no_op(): void
    {
        $request = Request::create('http://127.0.0.1/');
        $resolver = Mockery::mock(BrandContextResolver::class);
        $manager = Mockery::mock(BrandContextManager::class);
        $app = Mockery::mock(Application::class);
        $config = Mockery::mock(ConfigRepository::class);

        $resolver->shouldReceive('resolve')
            ->once()
            ->with($request)
            ->andThrow(new InvalidArgumentException('IP hosts are unsupported.'));
        $manager->shouldNotReceive('setPublicContext');
        $app->shouldReceive('environment')
            ->once()
            ->with('local')
            ->andReturnFalse();

        $middleware = new ResolvePublicBrandContext(
            $resolver,
            $manager,
            $app,
            $config
        );
        $response = $middleware->handle(
            $request,
            static fn () => new Response('continued')
        );

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('continued', $response->getContent());
    }

    public function test_local_loopback_host_uses_configured_canonical_context(): void
    {
        $request = Request::create('http://127.0.0.1:8000/faq');
        $context = Mockery::mock(BrandContext::class);
        $resolver = Mockery::mock(BrandContextResolver::class);
        $manager = Mockery::mock(BrandContextManager::class);
        $app = Mockery::mock(Application::class);
        $config = Mockery::mock(ConfigRepository::class);

        $resolver->shouldReceive('resolve')
            ->once()
            ->with($request)
            ->andThrow(new InvalidArgumentException('IP hosts are unsupported.'));
        $app->shouldReceive('environment')
            ->once()
            ->with('local')
            ->andReturnTrue();
        $config->shouldReceive('get')
            ->once()
            ->with('brands.host_validation.local_compatibility_hosts', [])
            ->andReturn(['localhost', '127.0.0.1']);
        $config->shouldReceive('get')
            ->once()
            ->with('brands.host_validation.local_context_hostname', '')
            ->andReturn('maacdurgapur.local');
        $resolver->shouldReceive('resolveHostname')
            ->once()
            ->with('maacdurgapur.local', 'http')
            ->andReturn($context);
        $manager->shouldReceive('setPublicContext')
            ->once()
            ->with($context);

        $middleware = new ResolvePublicBrandContext(
            $resolver,
            $manager,
            $app,
            $config
        );
        $response = $middleware->handle(
            $request,
            static fn () => new Response('continued')
        );

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('continued', $response->getContent());
    }

    public function test_unknown_host_never_uses_local_context_alias(): void
    {
        $request = Request::create('http://unknown.local/faq');
        $resolver = Mockery::mock(BrandContextResolver::class);
        $manager = Mockery::mock(BrandContextManager::class);
        $app = Mockery::mock(Application::class);
        $config = Mockery::mock(ConfigRepository::class);

        $resolver->shouldReceive('resolve')
            ->once()
            ->with($request)
            ->andReturnNull();
        $app->shouldReceive('environment')
            ->once()
            ->with('local')
            ->andReturnTrue();
        $config->shouldReceive('get')
            ->once()
            ->with('brands.host_validation.local_compatibility_hosts', [])
            ->andReturn(['localhost', '127.0.0.1']);
        $resolver->shouldNotReceive('resolveHostname');
        $manager->shouldNotReceive('setPublicContext');

        $middleware = new ResolvePublicBrandContext(
            $resolver,
            $manager,
            $app,
            $config
        );
        $response = $middleware->handle(
            $request,
            static fn () => new Response('continued')
        );

        $this->assertSame(200, $response->getStatusCode());
    }
}
