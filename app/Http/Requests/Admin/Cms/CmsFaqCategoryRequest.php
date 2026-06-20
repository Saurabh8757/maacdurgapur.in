<?php

namespace App\Http\Requests\Admin\Cms;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\Cms\CmsAuthorizationService;
use App\Services\Brands\BrandContextManager;
use Illuminate\Validation\Rule;

class CmsFaqCategoryRequest extends FormRequest
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
            'faqs',
            $operation,
            $brand
        );
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(BrandContextManager $brandContextManager): array
    {
        $brandId = $brandContextManager->requireAdminContext()->brand()->getKey();
        $category = $this->route('faq_category');
        $categoryId = is_object($category) ? $category->getKey() : $category;

        return [
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cms_faq_categories', 'slug')
                    ->where('brand_id', $brandId)
                    ->ignore($categoryId),
            ],
            'status' => 'required|in:active,inactive',
            'sort_order' => 'integer|min:0',
        ];
    }
}
