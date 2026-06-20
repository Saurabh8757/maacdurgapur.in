<?php

namespace App\Services\Cms;

use App\Models\CmsFaq;
use App\Models\CmsFaqCategory;
use App\Services\Brands\BrandContextManager;
use Illuminate\Database\Eloquent\Collection;

class CmsFaqReadService
{
    public function __construct(
        private readonly BrandContextManager $brandContextManager
    ) {
    }

    public function getAllAdmin(): Collection
    {
        $brandId = $this->brandContextManager
            ->requireAdminContext()
            ->brand()
            ->getKey();

        return CmsFaq::with('category')
            ->where('brand_id', $brandId)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    public function getCategoriesAdmin(): Collection
    {
        $brandId = $this->brandContextManager
            ->requireAdminContext()
            ->brand()
            ->getKey();

        return CmsFaqCategory::withCount('faqs')
            ->where('brand_id', $brandId)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }
}
