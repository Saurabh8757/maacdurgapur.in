<?php

namespace App\Services\Cms;

use App\Models\CmsFaq;
use App\Models\CmsFaqCategory;
use App\Services\Brands\BrandContextManager;
use App\Services\Settings\SettingsAuditLogger;
use App\Services\Cms\CmsAuthorizationService;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CmsFaqService
{
    public function __construct(
        private readonly BrandContextManager $brandContextManager,
        private readonly SettingsAuditLogger $auditLogger,
        private readonly CmsAuthorizationService $authorizationService
    ) {}

    public function create(array $data): CmsFaq
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'faqs', 'create', $brand);

        return DB::transaction(function () use ($data, $brand) {
            $brandId = $brand->getKey();
            
            // Verify category belongs to brand
            $category = CmsFaqCategory::where('id', $data['cms_faq_category_id'])
                ->where('brand_id', $brandId)
                ->first();
                
            if (!$category) {
                throw new InvalidArgumentException("Invalid FAQ Category for active brand.");
            }

            $data['brand_id'] = $brandId;

            $faq = CmsFaq::create($data);

            $this->auditLogger->record(
                event: 'cms.faq.created',
                scopeKey: 'cms.faqs',
                metadata: ['faq_id' => $faq->id, 'after_state' => $faq->toArray()],
                user: auth()->user(),
                brand: $this->brandContextManager->requireAdminContext()->brand()
            );

            return $faq;
        });
    }

    public function update(CmsFaq $faq, array $data): CmsFaq
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'faqs', 'edit', $brand);

        return DB::transaction(function () use ($faq, $data, $brand) {
            $brandId = $brand->getKey();
            
            if (isset($data['cms_faq_category_id'])) {
                $category = CmsFaqCategory::where('id', $data['cms_faq_category_id'])
                    ->where('brand_id', $brandId)
                    ->first();
                    
                if (!$category) {
                    throw new InvalidArgumentException("Invalid FAQ Category for active brand.");
                }
            }

            $beforeState = $faq->toArray();
            
            $faq->update($data);

            $this->auditLogger->record(
                event: 'cms.faq.updated',
                scopeKey: 'cms.faqs',
                metadata: [
                    'faq_id' => $faq->id,
                    'before_state' => $beforeState,
                    'after_state' => $faq->fresh()->toArray()
                ],
                user: auth()->user(),
                brand: $this->brandContextManager->requireAdminContext()->brand()
            );

            return $faq;
        });
    }

    public function delete(CmsFaq $faq): void
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'faqs', 'delete', $brand);

        DB::transaction(function () use ($faq) {
            $beforeState = $faq->toArray();
            
            $faq->delete();

            $this->auditLogger->record(
                event: 'cms.faq.deleted',
                scopeKey: 'cms.faqs',
                metadata: [
                    'faq_id' => $faq->id,
                    'before_state' => $beforeState,
                ],
                user: auth()->user(),
                brand: $this->brandContextManager->requireAdminContext()->brand()
            );
        });
    }
}
