<?php

namespace App\Services\Settings\Exceptions;

use RuntimeException;

class SettingsAuthorizationException extends RuntimeException
{
    public function __construct(
        public string $permissionCode,
        public string $scopeKey,
        public string $reason,
        public ?string $brandUuid = null
    ) {
        parent::__construct(
            "Settings authorization denied: {$reason}."
        );
    }
}
