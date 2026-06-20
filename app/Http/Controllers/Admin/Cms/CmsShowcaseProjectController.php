<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cms\CmsShowcaseProjectRequest;
use App\Models\CmsShowcaseProject;
use App\Services\Brands\BrandContextManager;
use App\Services\Cms\CmsShowcaseReadService;
use App\Services\Cms\CmsShowcaseService;
use App\Services\Cms\CmsAuthorizationService;
use Illuminate\Http\JsonResponse;

class CmsShowcaseProjectController extends Controller
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

        $projects = $this->readService->getProjectsAdmin();
            
        return response()->json($projects);
    }

    public function store(CmsShowcaseProjectRequest $request): JsonResponse
    {
        $project = $this->writeService->createProject($request->validated());
        
        return response()->json($project->load(['category', 'thumbnail']), 201);
    }

    public function update(CmsShowcaseProjectRequest $request, CmsShowcaseProject $showcase_project): JsonResponse
    {
        $brandId = $this->brandContextManager->requireAdminContext()->brand()->getKey();
        if ($showcase_project->brand_id !== $brandId) {
            abort(403, 'Unauthorized action.');
        }

        $project = $this->writeService->updateProject($showcase_project, $request->validated());
        
        return response()->json($project->load(['category', 'thumbnail']));
    }

    public function destroy(CmsShowcaseProject $showcase_project): JsonResponse
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'showcase', 'delete', $brand);

        if ($showcase_project->brand_id !== $brand->getKey()) {
            abort(403, 'Unauthorized action.');
        }

        $this->writeService->deleteProject($showcase_project);
        
        return response()->json(null, 204);
    }

    public function publish(CmsShowcaseProject $showcase_project): JsonResponse
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'showcase', 'publish', $brand);

        if ($showcase_project->brand_id !== $brand->getKey()) {
            abort(403, 'Unauthorized action.');
        }

        $project = $this->writeService->publishProject($showcase_project);
        
        return response()->json($project->load(['category', 'thumbnail']));
    }
}
