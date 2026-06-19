<?php

namespace Tests\Unit\Settings;

use App\Models\SettingDefinition;
use App\Models\SettingValue;
use App\Services\Settings\SettingsValuePresenter;
use PHPUnit\Framework\TestCase;

class SettingsValuePresenterTest extends TestCase
{
    public function test_scalar_boolean_array_and_null_values_are_formatted(): void
    {
        $presenter = new SettingsValuePresenter();

        $this->assertSame('AKSHA', $presenter->format('AKSHA'));
        $this->assertSame('Enabled', $presenter->format(true));
        $this->assertSame('Disabled', $presenter->format(false));
        $this->assertStringContainsString(
            '"label": "Explore"',
            $presenter->format(['label' => 'Explore'])
        );
        $this->assertSame('Not configured', $presenter->format(null));
    }

    public function test_sensitive_value_is_not_serialized_without_permission(): void
    {
        $definition = new SettingDefinition(['is_sensitive' => true]);
        $value = new SettingValue(['value' => ['token' => 'secret-value']]);

        $result = (new SettingsValuePresenter())->present(
            $definition,
            $value,
            null,
            'brand',
            false
        );

        $this->assertTrue($result['masked']);
        $this->assertNull($result['raw_value']);
        $this->assertSame('••••••••••••', $result['display_value']);
        $this->assertStringNotContainsString(
            'secret-value',
            json_encode($result)
        );
    }

    public function test_sensitive_value_is_available_with_permission(): void
    {
        $definition = new SettingDefinition(['is_sensitive' => true]);
        $value = new SettingValue(['value' => ['token' => 'visible-value']]);

        $result = (new SettingsValuePresenter())->present(
            $definition,
            $value,
            null,
            'global',
            true
        );

        $this->assertFalse($result['masked']);
        $this->assertSame(['token' => 'visible-value'], $result['raw_value']);
        $this->assertStringContainsString(
            'visible-value',
            $result['display_value']
        );
    }
}
