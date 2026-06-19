<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\BrandMembership;
use App\Models\User;
use Illuminate\Database\Seeder;

class BrandUserSeeder extends Seeder
{
    public function run(): void
    {
        $maac = Brand::where('code', 'maac')->firstOrFail();

        User::query()
            ->where('user_type', 'Admin')
            ->each(function (User $user) use ($maac): void {
                BrandMembership::updateOrCreate(
                    [
                        'brand_id' => $maac->id,
                        'user_id' => $user->id,
                    ],
                    [
                        'is_default' => true,
                    ]
                );
            });
    }
}
