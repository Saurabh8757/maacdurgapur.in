<?php

namespace App\Http\Requests\Admin\Cms;

use App\Models\MediaAsset;
use App\Services\Brands\BrandContextManager;
use App\Services\Cms\CmsAuthorizationService;
use Illuminate\Foundation\Http\FormRequest;

class CmsFeatureRequest extends FormRequest
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
            'features',
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
        $featureId = $this->route('feature') ? $this->route('feature')->id : 'NULL';

        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:cms_features,slug,' . $featureId . ',id,brand_id,' . $brandId,
            'description' => 'required|string|max:2000',
            'icon_file' => 'nullable|image|max:5120',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'integer|min:0'
        ];
    }
}
