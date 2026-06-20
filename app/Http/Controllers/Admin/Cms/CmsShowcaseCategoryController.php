<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cms\CmsShowcaseCategoryRequest;
use App\Models\CmsShowcaseCategory;
use App\Services\Brands\BrandContextManager;
use App\Services\Cms\CmsShowcaseReadService;
use App\Services\Cms\CmsShowcaseService;
use App\Services\Cms\CmsAuthorizationService;
use Illuminate\Http\JsonResponse;

class CmsShowcaseCategoryController extends Controller
{
    public function __construct(
        private readonly CmsShowcaseReadService $readService,
        private readonly CmsShowcaseService $writeService,
        private readonly BrandContextManager $brandContextManager,
        private readonly CmsAuthorizationService $authorizationService
    ) {}

    public function index(): JsonResponse
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'showcase', 'view', $brand);

        $categories = $this->readService->getCategoriesAdmin();
            
        return response()->json($categories);
    }

    public function store(CmsShowcaseCategoryRequest $request): JsonResponse
    {
        $category = $this->writeService->createCategory($request->validated());
        
        return response()->json($category, 201);
    }

    public function update(CmsShowcaseCategoryRequest $request, CmsShowcaseCategory $showcase_category): JsonResponse
    {
        $brandId = $this->brandContextManager->requireAdminContext()->brand()->getKey();
        if ($showcase_category->brand_id !== $brandId) {
            abort(403, 'Unauthorized action.');
        }

        $category = $this->writeService->updateCategory($showcase_category, $request->validated());
        
        return response()->json($category);
    }

    public function destroy(CmsShowcaseCategory $showcase_category): JsonResponse
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'showcase', 'delete', $brand);

        if ($showcase_category->brand_id !== $brand->getKey()) {
            abort(403, 'Unauthorized action.');
        }

        $this->writeService->deleteCategory($showcase_category);
        
        return response()->json(null, 204);
    }
}
