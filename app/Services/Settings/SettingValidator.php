<?php

namespace App\Services\Settings;

use App\Models\SettingDefinition;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SettingValidator
{
    public function validate(SettingDefinition $definition, mixed $value): array
    {
        $rules = [];

        if ($definition->is_required) {
            $rules[] = 'required';
        } else {
            $rules[] = 'nullable';
        }

        $baseRules = match ($definition->data_type) {
            'string', 'text' => ['string'],
            'integer', 'number' => ['integer'],
            'float' => ['numeric'],
            'boolean', 'toggle' => ['boolean'],
            'array', 'json' => ['array'],
            'email' => ['string', 'email'],
            'url' => ['string', 'url'],
            'color' => ['string', 'regex:/^#([a-fA-F0-9]{3}|[a-fA-F0-9]{6})$/'],
            default => [],
        };

        $rules = array_merge($rules, $baseRules);

        // Append explicit validation rules if any
        if (is_array($definition->validation_rules)) {
            $rules = array_merge($rules, $definition->validation_rules);
        }

        $validator = Validator::make(
            ['value' => $value],
            ['value' => $rules]
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
