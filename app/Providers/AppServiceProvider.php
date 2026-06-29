<?php

namespace App\Providers;

use App\Services\Brands\BrandContextManager;
use App\Services\Brands\BrandContextResolver;
use App\Services\Brands\BrandDomainCache;
use App\Services\Brands\AdminBrandAccessResolver;
use App\Services\Brands\AdminBrandContextResolver;
use App\Services\Brands\HostnameNormalizer;
use App\Services\Settings\SettingsAuthorizationService;
use App\Services\Settings\SettingsLocaleResolver;
use App\Services\Settings\SettingsScopeResolver;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use App\Models\Brand;
use App\Models\LeadFormField;

use App\Models\Lead;
use App\Models\LeadFollowup;
use App\Models\WhatsappMessage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\Paginator;
use App\Services\WhatsApp\WhatsappServiceInterface;
use App\Services\WhatsApp\WhatsappService;

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
        $this->app->bind(WhatsappServiceInterface::class, WhatsappService::class);

        $this->app->singleton(BrandDomainCache::class, function ($app) {
            return new BrandDomainCache(
                $app->make(CacheRepository::class),
                (string) config('brands.cache.prefix', 'brand-domain:v1:'),
                (int) config('brands.cache.positive_ttl_seconds', 600),
                (int) config('brands.cache.negative_ttl_seconds', 60)
            );
        });

        $this->app->scoped(BrandContextManager::class);
        $this->app->scoped(AdminBrandAccessResolver::class);
        $this->app->scoped(AdminBrandContextResolver::class);
        $this->app->scoped(SettingsScopeResolver::class);
        $this->app->scoped(SettingsAuthorizationService::class);
        $this->app->scoped(SettingsLocaleResolver::class);

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
        Paginator::useBootstrap();

        $clearAnalyticsCache = function () {
            Cache::forget('analytics_conversion_metrics');
            Cache::forget('analytics_followups');
            Cache::forget('analytics_leads_trend');
            Cache::forget('analytics_brands');
            Cache::forget('analytics_sources');
            Cache::forget('analytics_status_funnel');
            Cache::forget('analytics_counsellors');
            Cache::forget('analytics_whatsapp');
        };

        Lead::saved($clearAnalyticsCache);
        Lead::deleted($clearAnalyticsCache);
        
        LeadFollowup::saved($clearAnalyticsCache);
        LeadFollowup::deleted($clearAnalyticsCache);

        WhatsappMessage::saved($clearAnalyticsCache);
        WhatsappMessage::deleted($clearAnalyticsCache);

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

        View::composer('frontend.layout.app', function ($view) {
            $maacBrand = Brand::where('slug', 'maac')->first();
            $globalModalFormFields = collect();
            
            if ($maacBrand) {
                $globalModalFormFields = LeadFormField::where('brand_id', $maacBrand->id)
                    ->where('form_type', 'global_modal')
                    ->where('is_active', true)
                    ->orderBy('sort_order', 'asc')
                    ->get();
            }
            
            $view->with('globalModalFormFields', $globalModalFormFields);
        });
    }
}
