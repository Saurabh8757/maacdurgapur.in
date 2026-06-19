<?php

namespace App\Services\Brands;

use App\Models\Brand;
use App\Models\BrandDomain;

class BrandContext
{
    public function __construct(
        private Brand $brand,
        private BrandDomain $domain,
        private string $hostname,
        private string $scheme,
        private string $source = 'hostname'
    ) {
    }

    public function brand(): Brand
    {
        return $this->brand;
    }

    public function domain(): BrandDomain
    {
        return $this->domain;
    }

    public function hostname(): string
    {
        return $this->hostname;
    }

    public function scheme(): string
    {
        return $this->scheme;
    }

    public function source(): string
    {
        return $this->source;
    }

    public function isPreview(): bool
    {
        return (bool) $this->domain->is_preview;
    }

    public function isPrimaryDomain(): bool
    {
        return (bool) $this->domain->is_primary;
    }

    public function shouldRedirectToPrimary(): bool
    {
        return (bool) $this->domain->redirect_to_primary;
    }
}
