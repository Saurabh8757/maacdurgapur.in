<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cms\CmsFeatureRequest;
use App\Models\CmsFeature;
use App\Services\Brands\BrandContextManager;
use App\Services\Cms\CmsFeatureReadService;
use App\Services\Cms\CmsFeatureService;
use App\Services\Cms\CmsAuthorizationService;
use App\Models\MediaAsset;
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
        $validated = $request->validated();
        
        if ($request->hasFile('icon_file')) {
            $file = $request->file('icon_file');
            $path = $file->store('features', 'public');
            
            $brandId = $this->brandContextManager->requireAdminContext()->brand()->getKey();
            
            $media = MediaAsset::create([
                'brand_id' => $brandId,
                'uploaded_by' => auth()->id(),
                'storage_disk' => 'public',
                'storage_key' => 'storage/' . $path,
                'original_filename' => $file->getClientOriginalName(),
                'display_name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'extension' => $file->getClientOriginalExtension(),
                'mime_type' => $file->getMimeType(),
                'media_type' => 'image',
                'visibility' => 'public',
                'status' => 'ready',
                'size_bytes' => $file->getSize(),
                'checksum_sha256' => hash_file('sha256', $file->getRealPath()),
            ]);
            
            $validated['icon_media_id'] = $media->id;
        }
        unset($validated['icon_file']);

        $feature = $this->featureService->create($validated);
        
        return response()->json($feature->load('icon'), 201);
    }

    public function update(CmsFeatureRequest $request, CmsFeature $feature): JsonResponse
    {
        $brandId = $this->brandContextManager->requireAdminContext()->brand()->getKey();
        if ($feature->brand_id !== $brandId) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validated();
        
        if ($request->hasFile('icon_file')) {
            $file = $request->file('icon_file');
            $path = $file->store('features', 'public');
            
            $media = MediaAsset::create([
                'brand_id' => $brandId,
                'uploaded_by' => auth()->id(),
                'storage_disk' => 'public',
                'storage_key' => 'storage/' . $path,
                'original_filename' => $file->getClientOriginalName(),
                'display_name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'extension' => $file->getClientOriginalExtension(),
                'mime_type' => $file->getMimeType(),
                'media_type' => 'image',
                'visibility' => 'public',
                'status' => 'ready',
                'size_bytes' => $file->getSize(),
                'checksum_sha256' => hash_file('sha256', $file->getRealPath()),
            ]);
            
            $validated['icon_media_id'] = $media->id;
        }
        unset($validated['icon_file']);

        $feature = $this->featureService->update($feature, $validated);
        
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
