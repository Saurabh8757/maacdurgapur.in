<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlacementShowcaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $placements = [
            [
                'student_name' => 'Priya S.',
                'company_name' => 'Netflix Studios',
                'designation' => 'VFX Artist',
                'annual_package' => 379000,
                'image_path' => 'upload/SERVICE/photo-1512295767273-ac109ac3acfa.jpeg',
            ],
            [
                'student_name' => 'Rahul M.',
                'company_name' => 'Prime Focus',
                'designation' => 'Motion Designer',
                'annual_package' => 170000,
                'image_path' => 'upload/SERVICE/photo-1540242908484-50aa09aea5a7.jpeg',
            ],
            [
                'student_name' => 'Anjali K.',
                'company_name' => 'Adobe',
                'designation' => 'UI/UX Designer',
                'annual_package' => 360000,
                'image_path' => 'upload/SERVICE/photo-1572044162444-ad60f128bdea.jpeg',
            ],
            [
                'student_name' => 'Arjun D.',
                'company_name' => 'DNEG',
                'designation' => '3D Animator',
                'annual_package' => 440000,
                'image_path' => 'upload/SERVICE/photo-1611241893603-3c359704e0ee.jpeg',
            ],
            [
                'student_name' => 'Sneha R.',
                'company_name' => 'Tata Elxsi',
                'designation' => 'Graphic Designer',
                'annual_package' => 240000,
                'image_path' => 'upload/SERVICE/photo-1613909207039-6b173b755cc1.jpeg',
            ],
            [
                'student_name' => 'Vikram P.',
                'company_name' => 'Ubisoft',
                'designation' => 'Game Developer',
                'annual_package' => 520000,
                'image_path' => 'upload/SERVICE/animation2.jpg',
            ]
        ];

        $maacBrand = \App\Models\Brand::where('slug', 'maac')->first();

        foreach ($placements as $index => $data) {
            // Check if company exists
            $company = \App\Models\Company::firstOrCreate([
                'name' => $data['company_name']
            ]);

            // Ensure media asset exists (mock basic fields if missing)
            $mediaAsset = \App\Models\MediaAsset::where('storage_key', $data['image_path'])->first();
            
            if (!$mediaAsset) {
                $mediaAsset = \App\Models\MediaAsset::create([
                    'storage_disk' => 'public',
                    'storage_key' => $data['image_path'],
                    'original_filename' => basename($data['image_path']),
                    'display_name' => $data['student_name'] . ' Placement Image',
                    'extension' => pathinfo($data['image_path'], PATHINFO_EXTENSION),
                    'mime_type' => 'image/jpeg',
                    'media_type' => 'image',
                    'visibility' => 'public',
                    'status' => 'ready',
                    'brand_id' => $maacBrand ? $maacBrand->id : null,
                    'size_bytes' => 0,
                    'width' => 800,
                    'height' => 800,
                    'checksum_sha256' => hash('sha256', $data['image_path']),
                ]);
            }

            \App\Models\PlacementShowcase::create([
                'brand_id' => $maacBrand ? $maacBrand->id : null,
                'company_id' => $company->id,
                'student_name' => $data['student_name'],
                'company_name' => $data['company_name'],
                'designation' => $data['designation'],
                'annual_package' => $data['annual_package'],
                'student_image_media_id' => $mediaAsset->id,
                'sort_order' => $index + 1,
                'is_featured' => true,
                'is_active' => true,
            ]);
        }
    }
}
