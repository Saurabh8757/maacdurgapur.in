<?php

namespace App\Services\Brands;

use App\Models\Brand;
use App\Models\BrandDomain;
use Closure;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Http\Request;

class BrandContextResolver
{
    private const CACHE_PREFIX = 'brand-domain:v1:';
    private const NEGATIVE_CACHE_VALUE = ['resolved' => false];

    private array $requestCache = [];

    public function __construct(
        private HostnameNormalizer $hostnameNormalizer,
        private CacheRepository $cache,
        private ?Closure $domainLookup = null,
        private int $positiveTtlSeconds = 600,
        private int $negativeTtlSeconds = 60
    ) {
        $this->domainLookup ??= static function (string $hostname): ?BrandDomain {
            return BrandDomain::query()
                ->with('brand')
                ->where('hostname', $hostname)
                ->where('status', 'active')
                ->whereHas('brand', function ($brandQuery) {
                    $brandQuery->where('status', 'active');
                })
                ->first();
        };
    }

    public function resolve(Request $request): ?BrandContext
    {
        return $this->resolveHostname($request->getHost(), $request->getScheme());
    }

    public function resolveHostname(
        string $hostname,
        ?string $requestScheme = null
    ): ?BrandContext {
        $normalizedHostname = $this->hostnameNormalizer->normalize($hostname);

        if (array_key_exists($normalizedHostname, $this->requestCache)) {
            return $this->requestCache[$normalizedHostname];
        }

        $cacheKey = self::CACHE_PREFIX.$normalizedHostname;
        $cached = $this->cache->get($cacheKey);

        if (is_array($cached) && ($cached['resolved'] ?? null) === false) {
            return $this->requestCache[$normalizedHostname] = null;
        }

        if (is_array($cached) && ($cached['resolved'] ?? null) === true) {
            return $this->requestCache[$normalizedHostname] = $this->hydrateContext(
                $cached,
                $requestScheme
            );
        }

        $domain = ($this->domainLookup)($normalizedHostname);

        if (
            !$domain
            || !$domain->relationLoaded('brand')
            || !$domain->brand
            || $domain->status !== 'active'
            || $domain->brand->status !== 'active'
        ) {
            $this->cache->put(
                $cacheKey,
                self::NEGATIVE_CACHE_VALUE,
                $this->negativeTtlSeconds
            );

            return $this->requestCache[$normalizedHostname] = null;
        }

        $payload = $this->contextPayload($domain, $normalizedHostname);
        $this->cache->put($cacheKey, $payload, $this->positiveTtlSeconds);

        return $this->requestCache[$normalizedHostname] = $this->hydrateContext(
            $payload,
            $requestScheme
        );
    }

    public function forgetHostname(string $hostname): void
    {
        $normalizedHostname = $this->hostnameNormalizer->normalize($hostname);

        unset($this->requestCache[$normalizedHostname]);
        $this->cache->forget(self::CACHE_PREFIX.$normalizedHostname);
    }

    private function contextPayload(
        BrandDomain $domain,
        string $normalizedHostname
    ): array {
        return [
            'resolved' => true,
            'hostname' => $normalizedHostname,
            'domain' => [
                'id' => $domain->id,
                'brand_id' => $domain->brand_id,
                'hostname' => $domain->hostname,
                'scheme' => $domain->scheme,
                'is_primary' => (bool) $domain->is_primary,
                'is_preview' => (bool) $domain->is_preview,
                'redirect_to_primary' => (bool) $domain->redirect_to_primary,
                'status' => $domain->status,
            ],
            'brand' => [
                'id' => $domain->brand->id,
                'uuid' => $domain->brand->uuid,
                'code' => $domain->brand->code,
                'name' => $domain->brand->name,
                'legal_name' => $domain->brand->legal_name,
                'slug' => $domain->brand->slug,
                'default_locale' => $domain->brand->default_locale,
                'timezone' => $domain->brand->timezone,
                'status' => $domain->brand->status,
                'is_primary' => (bool) $domain->brand->is_primary,
            ],
        ];
    }

    private function hydrateContext(
        array $payload,
        ?string $requestScheme
    ): BrandContext {
        $brand = (new Brand())->newFromBuilder($payload['brand']);
        $domain = (new BrandDomain())->newFromBuilder($payload['domain']);
        $domain->setRelation('brand', $brand);

        return new BrandContext(
            $brand,
            $domain,
            $payload['hostname'],
            $requestScheme ?: $domain->scheme,
            'hostname'
        );
    }
}
