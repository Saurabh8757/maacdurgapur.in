<?php

namespace App\Http\Requests\Admin\Cms;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\Cms\CmsAuthorizationService;
use App\Services\Brands\BrandContextManager;
use Illuminate\Validation\Rule;

class CmsFaqRequest extends FormRequest
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

        return [
            'cms_faq_category_id' => [
                'required',
                Rule::exists('cms_faq_categories', 'id')
                    ->where('brand_id', $brandId)
                    ->whereNull('deleted_at'),
            ],
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'integer|min:0',
        ];
    }
}
