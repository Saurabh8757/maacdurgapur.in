<?php

namespace App\Services\Cms;

use App\Models\CmsShowcaseCategory;
use App\Models\CmsShowcaseProject;
use App\Services\Brands\BrandContextManager;
use Illuminate\Database\Eloquent\Collection;

class CmsShowcaseReadService
{
    public function __construct(
        private readonly BrandContextManager $brandContextManager
    ) {}

    public function getCategoriesAdmin(): Collection
    {
        $brandId = $this->brandContextManager->requireAdminContext()->brand()->getKey();
        
        return CmsShowcaseCategory::withCount('projects')
            ->where('brand_id', $brandId)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    public function getCategoriesPublic(): Collection
    {
        $brandId = $this->brandContextManager->requirePublicContext()->brand()->getKey();
        
        return CmsShowcaseCategory::where('brand_id', $brandId)
            ->where('status', 'active')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    public function getProjectsAdmin(): Collection
    {
        $brandId = $this->brandContextManager->requireAdminContext()->brand()->getKey();
        
        return CmsShowcaseProject::with(['category', 'thumbnail'])
            ->where('brand_id', $brandId)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    public function getProjectsPublic(): Collection
    {
        $brandId = $this->brandContextManager->requirePublicContext()->brand()->getKey();
        
        return CmsShowcaseProject::with(['category', 'thumbnail'])
            ->whereHas('category', function ($query) {
                $query->where('status', 'active');
            })
            ->where('brand_id', $brandId)
            ->where('status', 'published')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }
}
