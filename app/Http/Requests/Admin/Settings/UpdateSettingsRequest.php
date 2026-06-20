<?php

namespace App\Http\Requests\Admin\Settings;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization is handled by the controller via SettingsAuthorizationService
    }

    public function rules(): array
    {
        return [
            'definition_key' => ['required', 'string', 'exists:setting_definitions,key'],
            'value' => [], // Validated dynamically by SettingValidator
            'updated_at' => ['nullable', 'string'],
        ];
    }
}
