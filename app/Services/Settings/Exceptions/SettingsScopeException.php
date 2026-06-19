<?php

namespace App\Services\Settings\Exceptions;

use RuntimeException;

class SettingsScopeException extends RuntimeException
{
    public static function missingAdminContext(): self
    {
        return new self('Admin Brand Context is required for brand settings.');
    }

    public static function unauthenticated(): self
    {
        return new self('An authenticated administrator is required.');
    }

    public static function contextUserMismatch(): self
    {
        return new self('Admin Brand Context does not belong to this user.');
    }

    public static function unsupportedScope(string $scope): self
    {
        return new self("Unsupported settings scope: {$scope}.");
    }
}
