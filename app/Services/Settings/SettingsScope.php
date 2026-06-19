<?php

namespace App\Services\Settings;

use App\Models\Brand;

class SettingsScope
{
    private function __construct(
        private string $type,
        private string $scopeKey,
        private ?Brand $brand,
        private string $locale
    ) {
    }

    public static function forBrand(Brand $brand, string $locale): self
    {
        return new self(
            'brand',
            "brand:{$brand->uuid}",
            $brand,
            $locale
        );
    }

    public static function forGlobal(string $locale): self
    {
        return new self('global', 'global', null, $locale);
    }

    public function type(): string
    {
        return $this->type;
    }

    public function scopeKey(): string
    {
        return $this->scopeKey;
    }

    public function brand(): ?Brand
    {
        return $this->brand;
    }

    public function brandId(): ?int
    {
        return $this->brand?->getKey();
    }

    public function brandUuid(): ?string
    {
        return $this->brand?->uuid;
    }

    public function locale(): string
    {
        return $this->locale;
    }

    public function isBrand(): bool
    {
        return $this->type === 'brand';
    }

    public function isGlobal(): bool
    {
        return $this->type === 'global';
    }
}
