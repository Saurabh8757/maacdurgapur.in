<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cms\CmsCourseRequest;
use App\Models\CmsCourse;
use App\Services\Brands\BrandContextManager;
use App\Services\Cms\CmsCourseReadService;
use App\Services\Cms\CmsCourseService;
use App\Services\Cms\CmsAuthorizationService;
use Illuminate\Http\JsonResponse;

class CmsCourseController extends Controller
{
    public function __construct(
        private readonly CmsCourseReadService $courseReadService,
        private readonly CmsCourseService $courseService,
        private readonly BrandContextManager $brandContextManager,
        private readonly CmsAuthorizationService $authorizationService
    ) {}

    public function index(): JsonResponse
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'courses', 'view', $brand);

        $courses = $this->courseReadService->getAllAdmin();
            
        return response()->json($courses);
    }

    public function store(CmsCourseRequest $request): JsonResponse
    {
        $course = $this->courseService->create($request->validated());
        
        return response()->json($course->load('thumbnail'), 201);
    }

    public function update(CmsCourseRequest $request, CmsCourse $course): JsonResponse
    {
        $brandId = $this->brandContextManager->requireAdminContext()->brand()->getKey();
        if ($course->brand_id !== $brandId) {
            abort(403, 'Unauthorized action.');
        }

        $course = $this->courseService->update($course, $request->validated());
        
        return response()->json($course->load('thumbnail'));
    }

    public function destroy(CmsCourse $course): JsonResponse
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'courses', 'delete', $brand);

        if ($course->brand_id !== $brand->getKey()) {
            abort(403, 'Unauthorized action.');
        }

        $this->courseService->delete($course);
        
        return response()->json(null, 204);
    }
}
