<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\BrandDomain;
use Illuminate\Database\Seeder;

class BrandDomainSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment('local')) {
            return;
        }

        $maac = Brand::where('code', 'maac')->firstOrFail();

        BrandDomain::updateOrCreate(
            [
                'hostname' => 'maacdurgapur.local',
            ],
            [
                'brand_id' => $maac->id,
                'scheme' => 'http',
                'is_primary' => true,
                'is_preview' => false,
                'redirect_to_primary' => false,
                'status' => 'active',
            ]
        );
    }
}
