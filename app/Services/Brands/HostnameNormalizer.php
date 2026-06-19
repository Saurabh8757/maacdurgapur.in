<?php

namespace App\Services\Brands;

use InvalidArgumentException;

class HostnameNormalizer
{
    public function normalize(string $hostname): string
    {
        $hostname = trim($hostname);

        if ($hostname === '') {
            throw new InvalidArgumentException('Hostname cannot be empty.');
        }

        if (preg_match('/[\\x00-\\x20\\x7f\\/@?#\\\\]/', $hostname)) {
            throw new InvalidArgumentException('Hostname contains invalid characters.');
        }

        if (substr_count($hostname, ':') === 1) {
            [$host, $port] = explode(':', $hostname, 2);

            if ($port === '' || !ctype_digit($port)) {
                throw new InvalidArgumentException('Hostname contains an invalid port.');
            }

            $hostname = $host;
        } elseif (str_contains($hostname, ':')) {
            throw new InvalidArgumentException('IP address hosts are not supported.');
        }

        $hostname = rtrim($hostname, '.');

        if (function_exists('idn_to_ascii') && preg_match('/[^\\x20-\\x7e]/', $hostname)) {
            $idnFlags = defined('IDNA_DEFAULT') ? IDNA_DEFAULT : 0;
            $idnVariant = defined('INTL_IDNA_VARIANT_UTS46')
                ? INTL_IDNA_VARIANT_UTS46
                : 0;
            $asciiHostname = idn_to_ascii($hostname, $idnFlags, $idnVariant);

            if ($asciiHostname === false) {
                throw new InvalidArgumentException('Hostname cannot be normalized.');
            }

            $hostname = $asciiHostname;
        }

        $hostname = strtolower($hostname);

        if (filter_var($hostname, FILTER_VALIDATE_IP) !== false) {
            throw new InvalidArgumentException('IP address hosts are not supported.');
        }

        if (strlen($hostname) > 253) {
            throw new InvalidArgumentException('Hostname is too long.');
        }

        $labels = explode('.', $hostname);

        foreach ($labels as $label) {
            if (
                $label === ''
                || strlen($label) > 63
                || !preg_match('/^[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/', $label)
            ) {
                throw new InvalidArgumentException('Hostname is invalid.');
            }
        }

        return $hostname;
    }
}
