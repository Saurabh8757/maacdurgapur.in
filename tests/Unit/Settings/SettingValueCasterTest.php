<?php

namespace Tests\Unit\Settings;

use App\Models\SettingDefinition;
use App\Services\Settings\SettingValueCaster;
use PHPUnit\Framework\TestCase;

class SettingValueCasterTest extends TestCase
{
    private SettingValueCaster $caster;

    protected function setUp(): void
    {
        parent::setUp();
        $this->caster = new SettingValueCaster();
    }

    public function test_casts_null_to_null(): void
    {
        $definition = new SettingDefinition(['data_type' => 'string']);
        $this->assertNull($this->caster->cast($definition, null));
    }

    public function test_casts_string(): void
    {
        $definition = new SettingDefinition(['data_type' => 'string']);
        $this->assertSame('123', $this->caster->cast($definition, 123));
    }

    public function test_casts_integer(): void
    {
        $definition = new SettingDefinition(['data_type' => 'integer']);
        $this->assertSame(42, $this->caster->cast($definition, '42'));
    }

    public function test_casts_boolean(): void
    {
        $definition = new SettingDefinition(['data_type' => 'boolean']);
        $this->assertTrue($this->caster->cast($definition, 'true'));
        $this->assertFalse($this->caster->cast($definition, 'false'));
        $this->assertTrue($this->caster->cast($definition, 1));
        $this->assertFalse($this->caster->cast($definition, 0));
    }

    public function test_casts_json_array(): void
    {
        $definition = new SettingDefinition(['data_type' => 'json']);
        $this->assertSame(['key' => 'value'], $this->caster->cast($definition, '{"key": "value"}'));
        $this->assertSame(['key' => 'value'], $this->caster->cast($definition, ['key' => 'value']));
    }
}
