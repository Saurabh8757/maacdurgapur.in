<?php

namespace App\Services\Settings;

use App\Models\SettingDefinition;
use App\Models\SettingValue;

class SettingsValuePresenter
{
    public function present(
        SettingDefinition $definition,
        ?SettingValue $value,
        mixed $fallbackValue,
        string $source,
        bool $canViewSensitive
    ): array {
        $configured = $value !== null || $fallbackValue !== null;

        if ($definition->is_sensitive && !$canViewSensitive) {
            return [
                'display_value' => $configured ? '••••••••••••' : 'Not configured',
                'raw_value' => null,
                'configured' => $configured,
                'masked' => $configured,
                'source' => $source,
            ];
        }

        $rawValue = $value?->value ?? $fallbackValue;

        return [
            'display_value' => $this->format($rawValue),
            'raw_value' => $rawValue,
            'configured' => $configured,
            'masked' => false,
            'source' => $source,
        ];
    }

    public function format(mixed $value): string
    {
        if ($value === null) {
            return 'Not configured';
        }

        if (is_bool($value)) {
            return $value ? 'Enabled' : 'Disabled';
        }

        if (is_string($value) || is_numeric($value)) {
            return (string) $value;
        }

        return (string) json_encode(
            $value,
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
        );
    }
}
