<?php

namespace App\Http\Middleware;

use App\Services\Brands\AdminBrandContextResolver;
use App\Services\Brands\BrandContextManager;
use Illuminate\Http\Request;

class ResolveAdminBrandContext
{
    public function __construct(
        private AdminBrandContextResolver $adminContextResolver,
        private BrandContextManager $brandContextManager
    ) {
    }

    public function handle(Request $request, $next)
    {
        $context = $this->adminContextResolver->resolve($request);

        if ($context) {
            $this->brandContextManager->setAdminContext($context);
        }

        return $next($request);
    }
}
