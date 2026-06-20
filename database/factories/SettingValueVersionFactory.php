<?php

namespace Database\Factories;

use App\Models\SettingValue;
use App\Models\SettingValueVersion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingValueVersionFactory extends Factory
{
    protected $model = SettingValueVersion::class;

    public function definition(): array
    {
        return [
            'setting_value_id' => SettingValue::factory(),
            'version_number' => $this->faker->numberBetween(1, 100),
            'value' => 'Sample value',
            'status' => 'published',
            'change_summary' => $this->faker->sentence(),
            'created_by' => User::factory(),
        ];
    }
}
