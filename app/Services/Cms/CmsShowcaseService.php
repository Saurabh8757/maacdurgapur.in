<?php

namespace App\Services\Cms;

use App\Models\CmsShowcaseCategory;
use App\Models\CmsShowcaseProject;
use App\Services\Brands\BrandContextManager;
use App\Services\Settings\SettingsAuditLogger;
use Illuminate\Support\Facades\DB;

class CmsShowcaseService
{
    public function __construct(
        private readonly BrandContextManager $brandContextManager,
        private readonly SettingsAuditLogger $auditLogger,
        private readonly CmsAuthorizationService $authorizationService
    ) {}

    // CATEGORY OPERATIONS
    public function createCategory(array $data): CmsShowcaseCategory
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'showcase', 'create', $brand);

        return DB::transaction(function () use ($data, $brand) {
            $brandId = $brand->getKey();
            $data['brand_id'] = $brandId;

            $category = CmsShowcaseCategory::create($data);

            $this->auditLogger->record(
                event: 'cms.showcase_category.created',
                scopeKey: 'cms.showcase',
                metadata: [
                    'category_id' => $category->id,
                    'brand_id' => $brandId,
                    'after_state' => $category->toArray()
                ],
                user: auth()->user(),
                brand: $brand
            );

            return $category;
        });
    }

    public function updateCategory(CmsShowcaseCategory $category, array $data): CmsShowcaseCategory
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'showcase', 'edit', $brand);

        return DB::transaction(function () use ($category, $data, $brand) {
            $brandId = $brand->getKey();
            if ($category->brand_id !== $brandId) {
                abort(403, "Unauthorized cross-brand update.");
            }

            $beforeState = $category->toArray();
            $category->update($data);

            $this->auditLogger->record(
                event: 'cms.showcase_category.updated',
                scopeKey: 'cms.showcase',
                metadata: [
                    'category_id' => $category->id,
                    'brand_id' => $brandId,
                    'before_state' => $beforeState,
                    'after_state' => $category->fresh()->toArray()
                ],
                user: auth()->user(),
                brand: $brand
            );

            return $category;
        });
    }

    public function deleteCategory(CmsShowcaseCategory $category): void
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'showcase', 'delete', $brand);

        DB::transaction(function () use ($category, $brand) {
            $brandId = $brand->getKey();
            if ($category->brand_id !== $brandId) {
                abort(403, "Unauthorized cross-brand delete.");
            }

            $beforeState = $category->toArray();
            $category->delete();

            $this->auditLogger->record(
                event: 'cms.showcase_category.deleted',
                scopeKey: 'cms.showcase',
                metadata: [
                    'category_id' => $category->id,
                    'brand_id' => $brandId,
                    'before_state' => $beforeState,
                ],
                user: auth()->user(),
                brand: $brand
            );
        });
    }

    // PROJECT OPERATIONS
    public function createProject(array $data): CmsShowcaseProject
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'showcase', 'create', $brand);

        return DB::transaction(function () use ($data, $brand) {
            $brandId = $brand->getKey();
            $data['brand_id'] = $brandId;

            $project = CmsShowcaseProject::create($data);

            $this->auditLogger->record(
                event: 'cms.showcase.created',
                scopeKey: 'cms.showcase',
                metadata: [
                    'project_id' => $project->id,
                    'brand_id' => $brandId,
                    'after_state' => $project->toArray()
                ],
                user: auth()->user(),
                brand: $brand
            );

            return $project;
        });
    }

    public function updateProject(CmsShowcaseProject $project, array $data): CmsShowcaseProject
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'showcase', 'edit', $brand);

        return DB::transaction(function () use ($project, $data, $brand) {
            $brandId = $brand->getKey();
            if ($project->brand_id !== $brandId) {
                abort(403, "Unauthorized cross-brand update.");
            }

            $beforeState = $project->toArray();
            $project->update($data);

            $this->auditLogger->record(
                event: 'cms.showcase.updated',
                scopeKey: 'cms.showcase',
                metadata: [
                    'project_id' => $project->id,
                    'brand_id' => $brandId,
                    'before_state' => $beforeState,
                    'after_state' => $project->fresh()->toArray()
                ],
                user: auth()->user(),
                brand: $brand
            );

            return $project;
        });
    }

    public function deleteProject(CmsShowcaseProject $project): void
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'showcase', 'delete', $brand);

        DB::transaction(function () use ($project, $brand) {
            $brandId = $brand->getKey();
            if ($project->brand_id !== $brandId) {
                abort(403, "Unauthorized cross-brand delete.");
            }

            $beforeState = $project->toArray();
            $project->delete();

            $this->auditLogger->record(
                event: 'cms.showcase.deleted',
                scopeKey: 'cms.showcase',
                metadata: [
                    'project_id' => $project->id,
                    'brand_id' => $brandId,
                    'before_state' => $beforeState,
                ],
                user: auth()->user(),
                brand: $brand
            );
        });
    }

    public function publishProject(CmsShowcaseProject $project): CmsShowcaseProject
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'showcase', 'publish', $brand);

        return DB::transaction(function () use ($project, $brand) {
            $brandId = $brand->getKey();
            if ($project->brand_id !== $brandId) {
                abort(403, "Unauthorized cross-brand publish.");
            }

            $beforeState = $project->toArray();
            
            $project->update(['status' => 'published']);

            $this->auditLogger->record(
                event: 'cms.showcase.published',
                scopeKey: 'cms.showcase',
                metadata: [
                    'project_id' => $project->id,
                    'brand_id' => $brandId,
                    'before_state' => $beforeState,
                    'after_state' => $project->fresh()->toArray()
                ],
                user: auth()->user(),
                brand: $brand
            );

            return $project;
        });
    }
}
