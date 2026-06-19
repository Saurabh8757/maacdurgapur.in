<?php

namespace Tests\Unit\Settings;

use App\Services\Settings\SettingsAuditLogger;
use PHPUnit\Framework\TestCase;

class SettingsAuditLoggerTest extends TestCase
{
    public function test_non_sensitive_values_remain_unchanged(): void
    {
        $logger = new SettingsAuditLogger();
        $value = ['site_name' => 'MAAC Durgapur'];

        $this->assertSame(
            $value,
            $logger->redactValue($value, false)
        );
    }

    public function test_sensitive_values_are_replaced_with_hash_evidence(): void
    {
        $logger = new SettingsAuditLogger();
        $value = ['api_key' => 'private-value'];
        $redacted = $logger->redactValue($value, true);

        $this->assertSame(true, $redacted['redacted']);
        $this->assertMatchesRegularExpression(
            '/^[a-f0-9]{64}$/',
            $redacted['sha256']
        );
        $this->assertStringNotContainsString(
            'private-value',
            json_encode($redacted)
        );
        $this->assertSame(
            $redacted,
            $logger->redactValue($value, true)
        );
    }

    public function test_null_sensitive_values_remain_null(): void
    {
        $this->assertNull(
            (new SettingsAuditLogger())->redactValue(null, true)
        );
    }

    public function test_sensitive_metadata_keys_are_recursively_redacted(): void
    {
        $logger = new SettingsAuditLogger();

        $redacted = $logger->redactMetadata([
            'reason' => 'publication review',
            'access_token' => 'secret-token',
            'nested' => [
                'password_confirmation' => 'secret-password',
                'safe' => 'visible',
            ],
            'Authorization' => 'Bearer private',
        ]);

        $this->assertSame('publication review', $redacted['reason']);
        $this->assertSame('[REDACTED]', $redacted['access_token']);
        $this->assertSame(
            '[REDACTED]',
            $redacted['nested']['password_confirmation']
        );
        $this->assertSame('visible', $redacted['nested']['safe']);
        $this->assertSame('[REDACTED]', $redacted['Authorization']);
    }
}
