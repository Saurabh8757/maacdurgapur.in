<?php

namespace App\Services\Cms;

use App\Models\CmsFeature;
use App\Services\Brands\BrandContextManager;
use App\Services\Settings\SettingsAuditLogger;
use Illuminate\Support\Facades\DB;

class CmsFeatureService
{
    public function __construct(
        private readonly BrandContextManager $brandContextManager,
        private readonly SettingsAuditLogger $auditLogger,
        private readonly CmsAuthorizationService $authorizationService
    ) {}

    public function create(array $data): CmsFeature
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'features', 'create', $brand);

        return DB::transaction(function () use ($data, $brand) {
            $brandId = $brand->getKey();
            $data['brand_id'] = $brandId;

            $feature = CmsFeature::create($data);

            $this->auditLogger->record(
                event: 'cms.feature.created',
                scopeKey: 'cms.features',
                metadata: [
                    'feature_id' => $feature->id,
                    'brand_id' => $brandId,
                    'after_state' => $feature->toArray()
                ],
                user: auth()->user(),
                brand: $brand
            );

            return $feature;
        });
    }

    public function update(CmsFeature $feature, array $data): CmsFeature
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'features', 'edit', $brand);

        return DB::transaction(function () use ($feature, $data, $brand) {
            $brandId = $brand->getKey();
            
            if ($feature->brand_id !== $brandId) {
                abort(403, "Unauthorized cross-brand update.");
            }

            $beforeState = $feature->toArray();
            
            $feature->update($data);

            $this->auditLogger->record(
                event: 'cms.feature.updated',
                scopeKey: 'cms.features',
                metadata: [
                    'feature_id' => $feature->id,
                    'brand_id' => $brandId,
                    'before_state' => $beforeState,
                    'after_state' => $feature->fresh()->toArray()
                ],
                user: auth()->user(),
                brand: $brand
            );

            return $feature;
        });
    }

    public function delete(CmsFeature $feature): void
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'features', 'delete', $brand);

        DB::transaction(function () use ($feature, $brand) {
            $brandId = $brand->getKey();

            if ($feature->brand_id !== $brandId) {
                abort(403, "Unauthorized cross-brand delete.");
            }

            $beforeState = $feature->toArray();
            
            $feature->delete();

            $this->auditLogger->record(
                event: 'cms.feature.deleted',
                scopeKey: 'cms.features',
                metadata: [
                    'feature_id' => $feature->id,
                    'brand_id' => $brandId,
                    'before_state' => $beforeState,
                ],
                user: auth()->user(),
                brand: $brand
            );
        });
    }
}
