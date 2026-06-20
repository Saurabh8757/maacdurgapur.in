<?php

namespace Tests\Unit\Settings;

use App\Models\SettingDefinition;
use App\Services\Settings\SettingValidator;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class SettingValidatorTest extends TestCase
{
    private SettingValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new SettingValidator();
    }

    public function test_validates_required_string(): void
    {
        $definition = new SettingDefinition([
            'data_type' => 'string',
            'is_required' => true,
        ]);

        $result = $this->validator->validate($definition, 'test value');
        $this->assertSame(['value' => 'test value'], $result);

        $this->expectException(ValidationException::class);
        $this->validator->validate($definition, null);
    }

    public function test_validates_nullable_integer(): void
    {
        $definition = new SettingDefinition([
            'data_type' => 'integer',
            'is_required' => false,
        ]);

        $this->assertSame(['value' => null], $this->validator->validate($definition, null));
        $this->assertSame(['value' => 42], $this->validator->validate($definition, 42));

        $this->expectException(ValidationException::class);
        $this->validator->validate($definition, 'not-an-int');
    }

    public function test_appends_custom_validation_rules(): void
    {
        $definition = new SettingDefinition([
            'data_type' => 'string',
            'is_required' => true,
            'validation_rules' => ['min:5'],
        ]);

        $this->assertSame(['value' => 'valid'], $this->validator->validate($definition, 'valid'));

        $this->expectException(ValidationException::class);
        $this->validator->validate($definition, 'bad');
    }
}
