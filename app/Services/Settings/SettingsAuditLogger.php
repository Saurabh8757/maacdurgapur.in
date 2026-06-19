<?php

namespace App\Services\Settings;

use App\Models\Brand;
use App\Models\SettingAuditLog;
use App\Models\SettingDefinition;
use App\Models\SettingsPublication;
use App\Models\SettingValue;
use App\Models\User;
use Illuminate\Http\Request;

class SettingsAuditLogger
{
    private const SENSITIVE_METADATA_TERMS = [
        'authorization',
        'credential',
        'password',
        'secret',
        'token',
    ];

    public function record(
        string $event,
        string $scopeKey,
        string $locale = 'en',
        mixed $beforeValue = null,
        mixed $afterValue = null,
        array $metadata = [],
        ?User $user = null,
        ?Brand $brand = null,
        ?SettingDefinition $definition = null,
        ?SettingValue $settingValue = null,
        ?SettingsPublication $publication = null,
        ?Request $request = null
    ): SettingAuditLog {
        $sensitive = (bool) $definition?->is_sensitive;

        return SettingAuditLog::create([
            'user_id' => $user?->getKey(),
            'brand_id' => $brand?->getKey(),
            'brand_uuid' => $brand?->uuid,
            'setting_definition_id' => $definition?->getKey(),
            'setting_value_id' => $settingValue?->getKey(),
            'settings_publication_id' => $publication?->getKey(),
            'scope_key' => $scopeKey,
            'locale' => $locale,
            'event' => $event,
            'before_value' => $this->redactValue($beforeValue, $sensitive),
            'after_value' => $this->redactValue($afterValue, $sensitive),
            'metadata' => $this->redactMetadata($metadata),
            'ip_address' => $request?->ip(),
            'user_agent' => $this->truncateUserAgent(
                $request?->userAgent()
            ),
        ]);
    }

    public function redactValue(mixed $value, bool $sensitive): mixed
    {
        if (!$sensitive || $value === null) {
            return $value;
        }

        return [
            'redacted' => true,
            'sha256' => hash(
                'sha256',
                $this->canonicalJson($value)
            ),
        ];
    }

    public function redactMetadata(array $metadata): array
    {
        $redacted = [];

        foreach ($metadata as $key => $value) {
            if ($this->isSensitiveMetadataKey((string) $key)) {
                $redacted[$key] = '[REDACTED]';

                continue;
            }

            $redacted[$key] = is_array($value)
                ? $this->redactMetadata($value)
                : $value;
        }

        return $redacted;
    }

    private function isSensitiveMetadataKey(string $key): bool
    {
        $normalizedKey = strtolower($key);

        foreach (self::SENSITIVE_METADATA_TERMS as $term) {
            if (str_contains($normalizedKey, $term)) {
                return true;
            }
        }

        return false;
    }

    private function canonicalJson(mixed $value): string
    {
        return (string) json_encode(
            $value,
            JSON_UNESCAPED_SLASHES
                | JSON_UNESCAPED_UNICODE
                | JSON_PRESERVE_ZERO_FRACTION
        );
    }

    private function truncateUserAgent(?string $userAgent): ?string
    {
        if ($userAgent === null) {
            return null;
        }

        return mb_substr($userAgent, 0, 500);
    }
}
