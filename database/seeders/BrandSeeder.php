<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            [
                'code' => 'maac',
                'name' => 'MAAC',
                'legal_name' => 'MAAC Durgapur',
                'slug' => 'maac',
                'default_locale' => 'en',
                'timezone' => 'Asia/Calcutta',
                'status' => 'active',
                'is_primary' => true,
            ],
            [
                'code' => 'aksha',
                'name' => 'AKSHA',
                'legal_name' => 'AKSHA',
                'slug' => 'aksha',
                'default_locale' => 'en',
                'timezone' => 'Asia/Calcutta',
                'status' => 'active',
                'is_primary' => false,
            ],
            [
                'code' => 'space_e_fic',
                'name' => 'Space-E-Fic',
                'legal_name' => 'Space-E-Fic',
                'slug' => 'space-e-fic',
                'default_locale' => 'en',
                'timezone' => 'Asia/Calcutta',
                'status' => 'active',
                'is_primary' => false,
            ],
        ];

        foreach ($brands as $attributes) {
            $brand = Brand::firstOrNew([
                'code' => $attributes['code'],
            ]);

            if (!$brand->exists) {
                $brand->uuid = (string) Str::uuid();
            }

            $brand->fill($attributes);
            $brand->save();
        }
    }
}
