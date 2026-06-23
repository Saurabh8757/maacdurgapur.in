<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Recruiter;
use Illuminate\Database\Seeder;

class RecruiterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brand = Brand::where('slug', 'maac')->first();
        $brandId = $brand ? $brand->id : null;

        $recruiters = [
            [
                'company_name' => 'Netflix',
                'css_class' => 'netflix',
                'custom_html' => 'NETFLIX',
            ],
            [
                'company_name' => 'Rockstar Games',
                'css_class' => 'rockstar',
                'custom_html' => 'R★<br><small>GAMES</small>',
            ],
            [
                'company_name' => 'Tata Elxsi',
                'css_class' => 'tata-elxsi',
                'custom_html' => 'TATA<br>ELXSI',
            ],
            [
                'company_name' => 'Pogo',
                'css_class' => 'pogo',
                'custom_html' => 'pogo',
            ],
            [
                'company_name' => 'DNEG',
                'css_class' => 'dneg',
                'custom_html' => 'DNEG',
            ],
            [
                'company_name' => 'Prime Video',
                'css_class' => 'prime',
                'custom_html' => 'prime<br>video',
            ],
            [
                'company_name' => 'EA',
                'css_class' => 'ea',
                'custom_html' => 'EA',
            ],
            [
                'company_name' => 'Ubisoft',
                'css_class' => 'ubisoft',
                'custom_html' => 'UBISOFT',
            ]
        ];

        $sortOrder = 1;
        foreach ($recruiters as $recruiterData) {
            Recruiter::create([
                'brand_id' => $brandId,
                'company_name' => $recruiterData['company_name'],
                'css_class' => $recruiterData['css_class'],
                'custom_html' => $recruiterData['custom_html'],
                'sort_order' => $sortOrder++,
                'is_featured' => true,
                'is_active' => true,
            ]);
        }
    }
}
