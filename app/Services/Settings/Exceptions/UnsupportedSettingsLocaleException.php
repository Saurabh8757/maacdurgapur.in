<?php

namespace App\Services\Settings\Exceptions;

use InvalidArgumentException;

class UnsupportedSettingsLocaleException extends InvalidArgumentException
{
    public function __construct(public string $locale)
    {
        parent::__construct("Unsupported settings locale: {$locale}.");
    }
}
