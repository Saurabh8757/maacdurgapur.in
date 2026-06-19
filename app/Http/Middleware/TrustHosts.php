<?php

namespace App\Http\Middleware;

use App\Services\Brands\BrandContextResolver;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Middleware\TrustHosts as Middleware;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException;

class TrustHosts extends Middleware
{
    public function __construct(
        Application $app,
        private BrandContextResolver $brandContextResolver
    ) {
        parent::__construct($app);
    }

    /**
     * Get the host patterns that should be trusted.
     *
     * @return array
     */
    public function hosts()
    {
        return [];
    }

    /**
     * Validate operational hosts and active brand domains before dispatch.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, $next)
    {
        try {
            $hostname = strtolower(rtrim($request->getHost(), '.'));
        } catch (SuspiciousOperationException) {
            abort(404);
        }

        if ($this->isOperationalHost($hostname)) {
            return $next($request);
        }

        try {
            $brandContext = $this->brandContextResolver->resolveHostname(
                $hostname,
                $request->getScheme()
            );
        } catch (InvalidArgumentException) {
            abort(404);
        }

        if (!$brandContext) {
            abort(404);
        }

        return $next($request);
    }

    private function isOperationalHost(string $hostname): bool
    {
        $operationalHosts = config(
            'brands.host_validation.operational_hosts',
            []
        );

        if ($this->hostIsConfigured($hostname, $operationalHosts)) {
            return true;
        }

        if (!$this->app->environment('local')) {
            return false;
        }

        return $this->hostIsConfigured(
            $hostname,
            config('brands.host_validation.local_compatibility_hosts', [])
        );
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
