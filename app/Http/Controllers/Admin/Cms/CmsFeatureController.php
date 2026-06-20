<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cms\CmsFeatureRequest;
use App\Models\CmsFeature;
use App\Services\Brands\BrandContextManager;
use App\Services\Cms\CmsFeatureReadService;
use App\Services\Cms\CmsFeatureService;
use App\Services\Cms\CmsAuthorizationService;
use Illuminate\Http\JsonResponse;

class CmsFeatureController extends Controller
{
    public function __construct(
        private readonly CmsFeatureReadService $featureReadService,
        private readonly CmsFeatureService $featureService,
        private readonly BrandContextManager $brandContextManager,
        private readonly CmsAuthorizationService $authorizationService
    ) {}

    public function index(): JsonResponse
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'features', 'view', $brand);

        $features = $this->featureReadService->getAllAdmin();
            
        return response()->json($features);
    }

    public function store(CmsFeatureRequest $request): JsonResponse
    {
        $feature = $this->featureService->create($request->validated());
        
        return response()->json($feature->load('icon'), 201);
    }

    public function update(CmsFeatureRequest $request, CmsFeature $feature): JsonResponse
    {
        $brandId = $this->brandContextManager->requireAdminContext()->brand()->getKey();
        if ($feature->brand_id !== $brandId) {
            abort(403, 'Unauthorized action.');
        }

        $feature = $this->featureService->update($feature, $request->validated());
        
        return response()->json($feature->load('icon'));
    }

    public function destroy(CmsFeature $feature): JsonResponse
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'features', 'delete', $brand);

        if ($feature->brand_id !== $brand->getKey()) {
            abort(403, 'Unauthorized action.');
        }

        $this->featureService->delete($feature);
        
        return response()->json(null, 204);
    }
}
