<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cms\CmsFaqCategoryRequest;
use App\Models\CmsFaqCategory;
use App\Services\Brands\BrandContextManager;
use App\Services\Cms\CmsFaqCategoryService;
use App\Services\Cms\CmsAuthorizationService;
use Illuminate\Http\JsonResponse;

class CmsFaqCategoryController extends Controller
{
    public function __construct(
        private readonly CmsFaqCategoryService $faqCategoryService,
        private readonly BrandContextManager $brandContextManager,
        private readonly CmsAuthorizationService $authorizationService
    ) {}

    public function index(): JsonResponse
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'faqs', 'view', $brand);

        $brandId = $brand->getKey();
        
        $categories = CmsFaqCategory::where('brand_id', $brandId)
            ->orderBy('sort_order')
            ->get();
            
        return response()->json($categories);
    }

    public function store(CmsFaqCategoryRequest $request): JsonResponse
    {
        $category = $this->faqCategoryService->create($request->validated());
        
        return response()->json($category, 201);
    }

    public function update(CmsFaqCategoryRequest $request, CmsFaqCategory $faqCategory): JsonResponse
    {
        // Ensure it belongs to the brand
        $brandId = $this->brandContextManager->requireAdminContext()->brand()->getKey();
        if ($faqCategory->brand_id !== $brandId) {
            abort(403, 'Unauthorized action.');
        }

        $category = $this->faqCategoryService->update($faqCategory, $request->validated());
        
        return response()->json($category);
    }

    public function destroy(CmsFaqCategory $faqCategory): JsonResponse
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'faqs', 'delete', $brand);

        $brandId = $brand->getKey();
        if ($faqCategory->brand_id !== $brandId) {
            abort(403, 'Unauthorized action.');
        }

        $this->faqCategoryService->delete($faqCategory);
        
        return response()->json(null, 204);
    }
}
