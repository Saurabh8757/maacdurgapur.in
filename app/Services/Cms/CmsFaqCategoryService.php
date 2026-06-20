<?php

namespace App\Services\Cms;

use App\Models\CmsFaqCategory;
use App\Services\Brands\BrandContextManager;
use App\Services\Settings\SettingsAuditLogger;
use App\Services\Cms\CmsAuthorizationService;
use Illuminate\Support\Facades\DB;

class CmsFaqCategoryService
{
    public function __construct(
        private readonly BrandContextManager $brandContextManager,
        private readonly SettingsAuditLogger $auditLogger,
        private readonly CmsAuthorizationService $authorizationService
    ) {}

    public function create(array $data): CmsFaqCategory
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'faqs', 'create', $brand);

        return DB::transaction(function () use ($data, $brand) {
            $brandId = $brand->getKey();
            $data['brand_id'] = $brandId;

            $category = CmsFaqCategory::create($data);

            $this->auditLogger->record(
                event: 'cms.faq_category.created',
                scopeKey: 'cms.faqs',
                metadata: ['category_id' => $category->id, 'after_state' => $category->toArray()],
                user: auth()->user(),
                brand: $this->brandContextManager->requireAdminContext()->brand()
            );

            return $category;
        });
    }

    public function update(CmsFaqCategory $category, array $data): CmsFaqCategory
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'faqs', 'edit', $brand);

        return DB::transaction(function () use ($category, $data) {
            $beforeState = $category->toArray();
            
            $category->update($data);

            $this->auditLogger->record(
                event: 'cms.faq_category.updated',
                scopeKey: 'cms.faqs',
                metadata: [
                    'category_id' => $category->id,
                    'before_state' => $beforeState,
                    'after_state' => $category->fresh()->toArray()
                ],
                user: auth()->user(),
                brand: $this->brandContextManager->requireAdminContext()->brand()
            );

            return $category;
        });
    }

    public function delete(CmsFaqCategory $category): void
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'faqs', 'delete', $brand);

        DB::transaction(function () use ($category) {
            $beforeState = $category->toArray();
            
            $category->delete();

            $this->auditLogger->record(
                event: 'cms.faq_category.deleted',
                scopeKey: 'cms.faqs',
                metadata: [
                    'category_id' => $category->id,
                    'before_state' => $beforeState,
                ],
                user: auth()->user(),
                brand: $this->brandContextManager->requireAdminContext()->brand()
            );
        });
    }
}
