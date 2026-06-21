<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\MediaAsset;
use App\Services\Brands\BrandContextManager;
use App\Services\Cms\CmsAuthorizationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class CmsShowcaseMediaController extends Controller
{
    public function __construct(
        private readonly BrandContextManager $brandContextManager,
        private readonly CmsAuthorizationService $authorizationService
    ) {
    }

    public function store(Request $request): JsonResponse
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $user = auth()->user();

        $canCreate = $this->authorizationService->allows($user, 'showcase', 'create', $brand);
        $canEdit = $this->authorizationService->allows($user, 'showcase', 'edit', $brand);
        abort_unless($canCreate || $canEdit, 403);

        $validated = $request->validate([
            'thumbnail' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:10240'],
        ], [
            'thumbnail.uploaded' => 'Image exceeds server upload limit. Please try a smaller file.',
            'thumbnail.max' => 'Maximum allowed image size is 10 MB.',
            'thumbnail.mimes' => 'Unsupported image format. Allowed formats: JPG, PNG, WEBP, GIF.',
            'thumbnail.image' => 'The uploaded file must be a valid image.',
            'thumbnail.required' => 'Please select a thumbnail image to upload.',
        ]);

        $file = $validated['thumbnail'];
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $filename = Str::uuid().'.'.$extension;
        $path = $file->storeAs(
            'media/showcase/'.$brand->getKey(),
            $filename,
            'public'
        );

        abort_unless($path, 500, 'The thumbnail could not be stored.');

        try {
            $dimensions = @getimagesize($file->getRealPath()) ?: [null, null];
            $asset = DB::transaction(function () use ($brand, $file, $path, $extension, $dimensions) {
                return MediaAsset::create([
                    'brand_id' => $brand->getKey(),
                    'uploaded_by' => auth()->id(),
                    'storage_disk' => 'public',
                    'storage_key' => 'storage/'.$path,
                    'original_filename' => $file->getClientOriginalName(),
                    'display_name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                    'extension' => $extension,
                    'mime_type' => $file->getMimeType() ?: 'application/octet-stream',
                    'media_type' => 'image',
                    'visibility' => 'public',
                    'security_classification' => 'public',
                    'status' => 'active',
                    'size_bytes' => $file->getSize(),
                    'checksum_sha256' => hash_file('sha256', $file->getRealPath()),
                    'width' => $dimensions[0],
                    'height' => $dimensions[1],
                    'alt_text' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                    'published_at' => now(),
                ]);
            });
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($path);
            throw $exception;
        }

        return response()->json([
            'id' => $asset->getKey(),
            'url' => asset($asset->storage_key),
            'storage_key' => $asset->storage_key,
        ], 201);
    }
}
