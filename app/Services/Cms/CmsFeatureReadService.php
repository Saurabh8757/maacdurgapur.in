<?php

namespace App\Services\Cms;

use App\Models\CmsFeature;
use App\Services\Brands\BrandContextManager;
use Illuminate\Database\Eloquent\Collection;

class CmsFeatureReadService
{
    public function __construct(
        private readonly BrandContextManager $brandContextManager
    ) {}

    public function getAllAdmin(): Collection
    {
        $brandId = $this->brandContextManager->requireAdminContext()->brand()->getKey();
        
        return CmsFeature::with('icon')
            ->where('brand_id', $brandId)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    public function getAllPublic(): Collection
    {
        $brandId = $this->brandContextManager->requirePublicContext()->brand()->getKey();
        
        return CmsFeature::with('icon')
            ->where('brand_id', $brandId)
            ->where('status', 'active')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }
}
