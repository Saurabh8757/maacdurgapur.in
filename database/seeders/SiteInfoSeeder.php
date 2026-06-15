<?php

namespace Database\Seeders;

use App\Models\SiteInformationModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allData = [
            [
                'key' => 'site_name',
                'is_image' => 'No',
                'value' => 'MAAC',
            ],
            [
                'key' => 'site_logo',
                'is_image' => 'yes',
                'value' => url('/').'/image/logo.jpeg',
            ],
            [
                'key' => 'fav_icon',
                'is_image' => 'Yes',
                'value' => url('/').'/image/logo.jpeg',
            ],
            [
                'key' => 'copy_right',
                'is_image' => 'No',
                'value' => '© 2026 MAAC. All Rights Reserved',
            ],
        ];

        foreach ($allData as $item) {
            $save = new SiteInformationModel();
            $save->key = $item['key'];
            $save->is_image = $item['is_image'];
            $save->value = $item['value'];
            $save->save();
        }
    }
}
