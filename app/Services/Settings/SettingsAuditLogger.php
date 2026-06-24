<?php

namespace App\Services\Settings;

use Illuminate\Support\Facades\Log;

class SettingsAuditLogger
{
    /**
     * Record an audit log for settings/CMS changes.
     */
    public function record(
        string $event,
        string $scopeKey,
        array $metadata = [],
        ?\Illuminate\Contracts\Auth\Authenticatable $user = null,
        $brand = null
    ): void {
        Log::channel('daily')->info('Settings Audit', [
            'event' => $event,
            'scopeKey' => $scopeKey,
            'metadata' => $metadata,
            'user_id' => $user?->id,
            'brand_id' => $brand?->id ?? null,
            'timestamp' => now(),
        ]);
    }
}
