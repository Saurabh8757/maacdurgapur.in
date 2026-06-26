<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cms\CmsFaqRequest;
use App\Models\CmsFaq;
use App\Services\Brands\BrandContextManager;
use App\Services\Cms\CmsFaqService;
use App\Services\Cms\CmsAuthorizationService;
use Illuminate\Http\JsonResponse;

class CmsFaqController extends Controller
{
    public function __construct(
        private readonly CmsFaqService $faqService,
        private readonly BrandContextManager $brandContextManager,
        private readonly CmsAuthorizationService $authorizationService
    ) {}

    public function index(): JsonResponse
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'faqs', 'view', $brand);

        $brandId = $brand->getKey();
        
        $faqs = CmsFaq::with('category')
            ->where('brand_id', $brandId)
            ->orderBy('sort_order')
            ->get();
            
        return response()->json($faqs);
    }

    public function store(CmsFaqRequest $request): JsonResponse
    {
        try {
            $faq = $this->faqService->create($request->validated());

            return response()->json($faq->load('category'), 201);
        } catch (\InvalidArgumentException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        } catch (\Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => 'Unable to save FAQ. Please verify the selected category and brand access.',
            ], 500);
        }
    }

    public function update(CmsFaqRequest $request, CmsFaq $faq): JsonResponse
    {
        $brandId = $this->brandContextManager->requireAdminContext()->brand()->getKey();
        if ($faq->brand_id !== $brandId) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $faq = $this->faqService->update($faq, $request->validated());

            return response()->json($faq->load('category'));
        } catch (\InvalidArgumentException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        } catch (\Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => 'Unable to update FAQ. Please verify the selected category and brand access.',
            ], 500);
        }
    }

    public function destroy(CmsFaq $faq): JsonResponse
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'faqs', 'delete', $brand);

        $brandId = $brand->getKey();
        if ($faq->brand_id !== $brandId) {
            abort(403, 'Unauthorized action.');
        }

        $this->faqService->delete($faq);
        
        return response()->json(null, 204);
    }
}
