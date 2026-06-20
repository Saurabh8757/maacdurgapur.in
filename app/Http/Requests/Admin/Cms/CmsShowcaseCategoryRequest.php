<?php

namespace App\Http\Requests\Admin\Cms;

use App\Services\Brands\BrandContextManager;
use App\Services\Cms\CmsAuthorizationService;
use Illuminate\Foundation\Http\FormRequest;

class CmsShowcaseCategoryRequest extends FormRequest
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
        $categoryId = $this->route('showcase_category') ? $this->route('showcase_category')->id : 'NULL';

        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:cms_showcase_categories,slug,' . $categoryId . ',id,brand_id,' . $brandId,
            'status' => 'required|in:active,inactive',
            'sort_order' => 'integer|min:0'
        ];
    }
}
