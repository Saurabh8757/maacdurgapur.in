<?php

namespace App\Services\Cms;

use App\Models\CmsCourse;
use App\Services\Brands\BrandContextManager;
use App\Services\Settings\SettingsAuditLogger;
use Illuminate\Support\Facades\DB;

class CmsCourseService
{
    public function __construct(
        private readonly BrandContextManager $brandContextManager,
        private readonly SettingsAuditLogger $auditLogger,
        private readonly CmsAuthorizationService $authorizationService
    ) {}

    public function create(array $data): CmsCourse
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'courses', 'create', $brand);

        return DB::transaction(function () use ($data, $brand) {
            $brandId = $brand->getKey();
            $data['brand_id'] = $brandId;

            $course = CmsCourse::create($data);

            $this->auditLogger->record(
                event: 'cms.course.created',
                scopeKey: 'cms.courses',
                metadata: ['course_id' => $course->id, 'after_state' => $course->toArray()],
                user: auth()->user(),
                brand: $brand
            );

            return $course;
        });
    }

    public function update(CmsCourse $course, array $data): CmsCourse
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'courses', 'edit', $brand);

        return DB::transaction(function () use ($course, $data, $brand) {
            $brandId = $brand->getKey();
            
            if ($course->brand_id !== $brandId) {
                abort(403, "Unauthorized cross-brand update.");
            }

            $beforeState = $course->toArray();
            
            $course->update($data);

            $this->auditLogger->record(
                event: 'cms.course.updated',
                scopeKey: 'cms.courses',
                metadata: [
                    'course_id' => $course->id,
                    'before_state' => $beforeState,
                    'after_state' => $course->fresh()->toArray()
                ],
                user: auth()->user(),
                brand: $brand
            );

            return $course;
        });
    }

    public function delete(CmsCourse $course): void
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $this->authorizationService->authorize(auth()->user(), 'courses', 'delete', $brand);

        DB::transaction(function () use ($course, $brand) {
            if ($course->brand_id !== $brand->getKey()) {
                abort(403, "Unauthorized cross-brand delete.");
            }

            $beforeState = $course->toArray();
            
            $course->delete();

            $this->auditLogger->record(
                event: 'cms.course.deleted',
                scopeKey: 'cms.courses',
                metadata: [
                    'course_id' => $course->id,
                    'before_state' => $beforeState,
                ],
                user: auth()->user(),
                brand: $brand
            );
        });
    }
}
