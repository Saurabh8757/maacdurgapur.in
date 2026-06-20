<?php

namespace App\Http\Requests\Admin\Cms;

use App\Services\Brands\BrandContextManager;
use App\Services\Cms\CmsAuthorizationService;
use Illuminate\Foundation\Http\FormRequest;

class CmsCourseRequest extends FormRequest
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
            'courses',
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
        $courseId = $this->route('course') ? $this->route('course')->id : 'NULL';

        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:cms_courses,slug,' . $courseId . ',id,brand_id,' . $brandId,
            'description' => 'required|string|max:2000',
            'tools_covered' => 'nullable|array',
            'tools_covered.*' => 'string|max:100',
            'thumbnail_media_id' => 'nullable|exists:media_assets,id',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'integer|min:0'
        ];
    }
}
