<?php

namespace App\Services\Brands;

use Illuminate\Contracts\Cache\Repository as CacheRepository;

class BrandDomainCache
{
    public function __construct(
        private CacheRepository $cache,
        private string $prefix,
        private int $positiveTtlSeconds,
        private int $negativeTtlSeconds
    ) {
    }

    public function get(string $normalizedHostname): ?array
    {
        $value = $this->cache->get($this->key($normalizedHostname));

        return is_array($value) ? $value : null;
    }

    public function putResolved(string $normalizedHostname, array $payload): void
    {
        $this->cache->put(
            $this->key($normalizedHostname),
            $payload,
            $this->positiveTtlSeconds
        );
    }

    public function putUnresolved(string $normalizedHostname): void
    {
        $this->cache->put(
            $this->key($normalizedHostname),
            ['resolved' => false],
            $this->negativeTtlSeconds
        );
    }

    public function forget(string $normalizedHostname): void
    {
        $this->cache->forget($this->key($normalizedHostname));
    }

    public function key(string $normalizedHostname): string
    {
        return $this->prefix.$normalizedHostname;
    }

    public function positiveTtlSeconds(): int
    {
        return $this->positiveTtlSeconds;
    }

    public function negativeTtlSeconds(): int
    {
        return $this->negativeTtlSeconds;
    }
}
