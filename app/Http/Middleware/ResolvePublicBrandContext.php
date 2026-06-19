<?php

namespace App\Http\Middleware;

use App\Services\Brands\BrandContextManager;
use App\Services\Brands\BrandContextResolver;
use Illuminate\Http\Request;
use InvalidArgumentException;

class ResolvePublicBrandContext
{
    public function __construct(
        private BrandContextResolver $brandContextResolver,
        private BrandContextManager $brandContextManager
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

        if ($brandContext) {
            $this->brandContextManager->setPublicContext($brandContext);
        }

        return $next($request);
    }
}
