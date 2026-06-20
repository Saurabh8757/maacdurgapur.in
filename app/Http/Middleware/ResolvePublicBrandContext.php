<?php

namespace App\Http\Middleware;

use App\Services\Brands\BrandContextManager;
use App\Services\Brands\BrandContextResolver;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use InvalidArgumentException;

class ResolvePublicBrandContext
{
    public function __construct(
        private BrandContextResolver $brandContextResolver,
        private BrandContextManager $brandContextManager,
        private Application $app,
        private ConfigRepository $config
    ) {
    }

    /**
     * Resolve and bind public Brand Context without altering the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, $next)
    {
        try {
            $brandContext = $this->brandContextResolver->resolve($request);
        } catch (InvalidArgumentException) {
            $brandContext = null;
        }

        $brandContext ??= $this->resolveLocalCompatibilityContext($request);

        if ($brandContext) {
            $this->brandContextManager->setPublicContext($brandContext);
        }

        return $next($request);
    }

    private function resolveLocalCompatibilityContext(Request $request)
    {
        if (!$this->app->environment('local')) {
            return null;
        }

        $hostname = strtolower(rtrim($request->getHost(), '.'));
        $localHosts = $this->config->get(
            'brands.host_validation.local_compatibility_hosts',
            []
        );

        if (!$this->hostIsConfigured($hostname, $localHosts)) {
            return null;
        }

        $contextHostname = trim((string) $this->config->get(
            'brands.host_validation.local_context_hostname',
            ''
        ));

        if ($contextHostname === '') {
            return null;
        }

        try {
            return $this->brandContextResolver->resolveHostname(
                $contextHostname,
                $request->getScheme()
            );
        } catch (InvalidArgumentException) {
            return null;
        }
    }

    private function hostIsConfigured(string $hostname, array $configuredHosts): bool
    {
        foreach ($configuredHosts as $configuredHost) {
            if (
                $hostname === strtolower(rtrim(trim((string) $configuredHost), '.'))
            ) {
                return true;
            }
        }

        return false;
    }
}
