<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AkshaMajorProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\AkshaMajorProgram::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $programs = [
            [
                'title' => 'Professional Program in 3D Animation',
                'slug' => 'professional-program-in-3d-animation',
                'short_description' => 'Master the art of 3D animation, character design, and modeling using industry-standard tools like Maya and Blender.',
                'outcome' => '3D Animator / Character Artist',
                'skills' => ['Character Design', 'Modeling', 'Rigging', 'Animation'],
                'image' => 'frontend/images/animation.webp',
                'sort_order' => 1,
            ],
            [
                'title' => 'Professional Program in 3D Animation & VFX',
                'slug' => 'professional-program-in-3d-animation-vfx',
                'short_description' => 'Combine advanced 3D animation techniques with high-end visual effects for film, television, and gaming.',
                'outcome' => 'VFX Artist / Compositor',
                'skills' => ['VFX Compositing', 'Motion Tracking', 'CGI', 'Dynamics'],
                'image' => 'frontend/images/pg-04.webp',
                'sort_order' => 2,
            ],
            [
                'title' => 'Diploma in Core Programming & Languages',
                'slug' => 'diploma-in-core-programming-languages',
                'short_description' => 'Build a strong foundation in computer science with deep dives into algorithms, data structures, and multiple coding languages.',
                'outcome' => 'Software Developer / Programmer',
                'skills' => ['Python', 'C / C++', 'JavaScript', 'Logic Building'],
                'image' => 'frontend/images/aksha/bg/fantasy1.webp',
                'sort_order' => 3,
            ],
            [
                'title' => 'Professional Program in Digital Marketing & AI Analytics',
                'slug' => 'professional-program-in-digital-marketing-ai-analytics',
                'short_description' => 'Master modern marketing strategies integrated with AI tools, performance marketing, and data-driven growth tactics.',
                'outcome' => 'Digital Marketing Manager',
                'skills' => ['SEO', 'Meta Ads', 'Google Ads', 'AI Marketing'],
                'image' => 'frontend/images/aksha/bg/fantasy1.webp',
                'sort_order' => 4,
            ]
        ];

        // Ensure target directory exists
        $targetDir = storage_path('app/public/cms/aksha_programs');
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $firstUser = \App\Models\User::first();
        $firstBrand = \App\Models\Brand::first();
        $userId = $firstUser ? $firstUser->id : null;
        $brandId = $firstBrand ? $firstBrand->id : null;

        foreach ($programs as $index => $p) {
            $mediaId = null;
            $sourcePath = public_path($p['image']);
            
            if (file_exists($sourcePath)) {
                $filename = basename($sourcePath);
                $uniqueFilename = time() . '-' . uniqid() . '-' . $filename;
                $destPath = 'cms/aksha_programs/' . $uniqueFilename;
                
                copy($sourcePath, storage_path('app/public/' . $destPath));
                
                $media = \App\Models\MediaAsset::create([
                    'brand_id' => $brandId,
                    'uploaded_by' => $userId,
                    'storage_disk' => 'public',
                    'storage_key' => $destPath,
                    'original_filename' => $filename,
                    'display_name' => $filename,
                    'extension' => pathinfo($filename, PATHINFO_EXTENSION),
                    'media_type' => 'image',
                    'mime_type' => mime_content_type($sourcePath),
                    'size_bytes' => filesize($sourcePath),
                    'checksum_sha256' => hash_file('sha256', $sourcePath),
                    'visibility' => 'public',
                ]);
                $mediaId = $media->id;
            }

            \App\Models\AkshaMajorProgram::create([
                'brand_id' => $brandId,
                'title' => $p['title'],
                'slug' => $p['slug'],
                'short_description' => $p['short_description'],
                'outcome' => $p['outcome'],
                'skills' => $p['skills'],
                'featured_image_media_id' => $mediaId,
                'sort_order' => $p['sort_order'],
                'is_featured' => true,
                'is_active' => true,
            ]);
        }
    }
}
