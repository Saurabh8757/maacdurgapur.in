<?php

namespace App\Providers;

use App\Services\Brands\BrandContextManager;
use App\Services\Brands\BrandContextResolver;
use App\Services\Brands\BrandDomainCache;
use App\Services\Brands\HostnameNormalizer;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    public static $queryCount = 0;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(BrandDomainCache::class, function ($app) {
            return new BrandDomainCache(
                $app->make(CacheRepository::class),
                (string) config('brands.cache.prefix', 'brand-domain:v1:'),
                (int) config('brands.cache.positive_ttl_seconds', 600),
                (int) config('brands.cache.negative_ttl_seconds', 60)
            );
        });

        $this->app->scoped(BrandContextManager::class);

        $this->app->scoped(BrandContextResolver::class, function ($app) {
            return new BrandContextResolver(
                $app->make(HostnameNormalizer::class),
                $app->make(BrandDomainCache::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        DB::listen(function ($query) {
            self::$queryCount++;
            
            if ($query->time > 500) {
                Log::warning('Slow Query Exceeded 500ms', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time_ms' => $query->time
                ]);
            }
        });
    }
}
