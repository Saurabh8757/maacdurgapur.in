<?php

namespace App\Services\Settings;

use App\Models\SettingDefinition;
use InvalidArgumentException;

class SettingValueCaster
{
    public function cast(SettingDefinition $definition, mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        return match ($definition->data_type) {
            'string', 'text', 'url', 'email', 'color' => (string) $value,
            'integer', 'number' => (int) $value,
            'float' => (float) $value,
            'boolean', 'toggle' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'array', 'json' => is_string($value) ? json_decode($value, true) : (array) $value,
            default => $value,
        };
    }
}
