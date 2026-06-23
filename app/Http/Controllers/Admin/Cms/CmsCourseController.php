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
        $data = $request->validated();
        
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $path = $file->store('cms/courses', 'public');
            $media = \App\Models\MediaAsset::create([
                'brand_id' => $this->brandContextManager->requireAdminContext()->brand()->getKey(),
                'uploaded_by' => auth()->id() ?? 1,
                'storage_disk' => 'public',
                'storage_key' => $path,
                'original_filename' => $file->getClientOriginalName(),
                'display_name' => $file->getClientOriginalName(),
                'extension' => strtolower($file->getClientOriginalExtension()),
                'media_type' => 'image',
                'mime_type' => $file->getMimeType(),
                'size_bytes' => $file->getSize(),
                'checksum_sha256' => hash_file('sha256', $file->getRealPath()),
                'visibility' => 'public',
                'is_active' => true,
            ]);
            $data['thumbnail_media_id'] = $media->id;
        }

        $course = $this->courseService->create($data);
        
        return response()->json($course->load('thumbnail'), 201);
    }

    public function update(CmsCourseRequest $request, CmsCourse $course): JsonResponse
    {
        $brandId = $this->brandContextManager->requireAdminContext()->brand()->getKey();
        if ($course->brand_id !== $brandId) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validated();
        
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $path = $file->store('cms/courses', 'public');
            $media = \App\Models\MediaAsset::create([
                'brand_id' => $brandId,
                'uploaded_by' => auth()->id() ?? 1,
                'storage_disk' => 'public',
                'storage_key' => $path,
                'original_filename' => $file->getClientOriginalName(),
                'display_name' => $file->getClientOriginalName(),
                'extension' => strtolower($file->getClientOriginalExtension()),
                'media_type' => 'image',
                'mime_type' => $file->getMimeType(),
                'size_bytes' => $file->getSize(),
                'checksum_sha256' => hash_file('sha256', $file->getRealPath()),
                'visibility' => 'public',
                'is_active' => true,
            ]);
            $data['thumbnail_media_id'] = $media->id;
        }

        $course = $this->courseService->update($course, $data);
        
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
