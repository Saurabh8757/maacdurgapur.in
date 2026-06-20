<?php

namespace App\Services\Cms;

use App\Models\CmsCourse;
use App\Services\Brands\BrandContextManager;
use Illuminate\Database\Eloquent\Collection;

class CmsCourseReadService
{
    public function __construct(
        private readonly BrandContextManager $brandContextManager
    ) {}

    public function getAllAdmin(): Collection
    {
        $brandId = $this->brandContextManager->requireAdminContext()->brand()->getKey();
        
        return CmsCourse::with('thumbnail')
            ->where('brand_id', $brandId)
            ->orderBy('sort_order')
            ->get();
    }

    public function getAllPublic(): Collection
    {
        $brandId = $this->brandContextManager->requirePublicContext()->brand()->getKey();
        
        return CmsCourse::with('thumbnail')
            ->where('brand_id', $brandId)
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->get();
    }
}
