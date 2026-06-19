<?php

namespace App\Services\Brands;

use App\Models\Brand;
use Carbon\CarbonImmutable;

class AdminBrandContext
{
    public function __construct(
        private Brand $brand,
        private int $userId,
        private string $source,
        private CarbonImmutable $selectedAt,
        private bool $globalSuperAdmin
    ) {
    }

    public function brand(): Brand
    {
        return $this->brand;
    }

    public function userId(): int
    {
        return $this->userId;
    }

    public function source(): string
    {
        return $this->source;
    }

    public function selectedAt(): CarbonImmutable
    {
        return $this->selectedAt;
    }

    public function isGlobalSuperAdmin(): bool
    {
        return $this->globalSuperAdmin;
    }
}
