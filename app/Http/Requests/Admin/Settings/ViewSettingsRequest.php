<?php

namespace App\Http\Requests\Admin\Settings;

use App\Services\Settings\SettingsLocaleResolver;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ViewSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('locale')) {
            $this->merge([
                'locale' => strtolower(str_replace('_', '-', trim((string) $this->input('locale')))),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'group' => ['nullable', 'string', 'max:100', 'regex:/^[a-z0-9_-]+$/'],
            'locale' => [
                'nullable',
                'string',
                'max:10',
                Rule::in(app(SettingsLocaleResolver::class)->supportedLocales()),
            ],
            'brand_id' => ['prohibited'],
            'brand_uuid' => ['prohibited'],
            'scope_key' => ['prohibited'],
            'user_id' => ['prohibited'],
            'value' => ['prohibited'],
            'status' => ['prohibited'],
        ];
    }
}
