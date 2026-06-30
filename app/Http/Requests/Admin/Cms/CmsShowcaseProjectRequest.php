<?php

namespace App\Http\Requests\Admin\Cms;

use App\Models\MediaAsset;
use App\Services\Brands\BrandContextManager;
use App\Services\Cms\CmsAuthorizationService;
use Illuminate\Foundation\Http\FormRequest;

class CmsShowcaseProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(
        CmsAuthorizationService $authService,
        BrandContextManager $brandContextManager
    ): bool {
        $brand = $brandContextManager->requireAdminContext()->brand();
        $operation = $this->isMethod('POST') ? 'create' : 'edit';
        
        return $authService->allows(
            $this->user(),
            'showcase',
            $operation,
            $brand
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(BrandContextManager $brandContextManager): array
    {
        $brandId = $brandContextManager->requireAdminContext()->brand()->getKey();
        $projectId = $this->route('showcase_project') ? $this->route('showcase_project')->id : 'NULL';

        return [
            'cms_showcase_category_id' => 'required|exists:cms_showcase_categories,id,brand_id,' . $brandId,
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:cms_showcase_projects,slug,' . $projectId . ',id,brand_id,' . $brandId,
            'student_name' => 'required|string|max:255',
            'short_description' => 'required|string',
            'thumbnail_media_id' => [
                'nullable',
                'exists:media_assets,id',
                function ($attribute, $value, $fail) use ($brandId) {
                    if ($value) {
                        $media = MediaAsset::find($value);
                        if (!$media) {
                            $fail('The selected media asset is invalid.');
                            return;
                        }
                        if ($media->status !== 'active' && $media->status !== 'published') {
                            $fail('The selected media asset is not active.');
                        }
                        if ($media->brand_id !== null && $media->brand_id !== $brandId) {
                            $fail('The selected media asset is not accessible in the current brand context.');
                        }
                    }
                }
            ],
            'software_icon_media_id_2' => 'nullable|exists:media_assets,id',
            'software_icon_media_id_3' => 'nullable|exists:media_assets,id',
            'software_icon_media_id_4' => 'nullable|exists:media_assets,id',
            'software_icon_media_id_5' => 'nullable|exists:media_assets,id',
            'software_icon_media_id' => [
                'nullable',
                'exists:media_assets,id',
                function ($attribute, $value, $fail) use ($brandId) {
                    if ($value) {
                        $media = MediaAsset::find($value);
                        if (!$media) {
                            $fail('The selected media asset is invalid.');
                            return;
                        }
                        if ($media->status !== 'active' && $media->status !== 'published') {
                            $fail('The selected media asset is not active.');
                        }
                        if ($media->brand_id !== null && $media->brand_id !== $brandId) {
                            $fail('The selected media asset is not accessible in the current brand context.');
                        }
                    }
                }
            ],
            'status' => 'required|in:draft,published',
            'sort_order' => 'integer|min:0'
        ];
    }
}
