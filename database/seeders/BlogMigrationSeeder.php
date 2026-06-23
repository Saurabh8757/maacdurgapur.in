<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\BlogCategory;
use App\Models\Blog;
use Illuminate\Support\Str;

class BlogMigrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $categories = [
            'Animation & VFX' => 'animation-vfx',
            'UI/UX & Design' => 'ui-ux-design',
            'Gaming' => 'gaming',
            'AI & Tech' => 'ai-tech'
        ];

        $categoryIds = [];
        foreach ($categories as $name => $slug) {
            $cat = BlogCategory::firstOrCreate(
                ['slug' => $slug],
                ['name' => $name, 'status' => 'active']
            );
            $categoryIds[$name] = $cat->id;
        }

        $blogs = [
            [
                'title' => 'How AI is Revolutionizing the VFX Pipeline in 2026',
                'category_name' => 'Animation & VFX',
                'excerpt' => 'Discover how major studios are integrating AI-powered compositing and rotoscoping to cut production time in half without sacrificing quality.',
                'reading_time' => '5 min read',
                'published_at' => '2025-10-12 10:00:00'
            ],
            [
                'title' => 'The Death of Flat Design: Rise of Neumorphism & 3D Web',
                'category_name' => 'UI/UX & Design',
                'excerpt' => 'Flat design has ruled the web for a decade, but immersive 3D elements and tactile UI components are rapidly becoming the new standard for premium brands.',
                'reading_time' => '4 min read',
                'published_at' => '2025-09-28 10:00:00'
            ],
            [
                'title' => 'Getting Started with Unreal Engine 5: Nanite & Lumen Explained',
                'category_name' => 'Gaming',
                'excerpt' => 'A beginner-friendly breakdown of how Unreal Engine 5\'s revolutionary rendering technologies are changing game development forever.',
                'reading_time' => '7 min read',
                'published_at' => '2025-09-15 10:00:00'
            ],
            [
                'title' => 'Prompt Engineering for Artists: Mastering Midjourney & DALL-E',
                'category_name' => 'AI & Tech',
                'excerpt' => 'Learn how traditional artists are leveraging generative AI as an advanced brainstorming and concept art tool rather than seeing it as competition.',
                'reading_time' => '6 min read',
                'published_at' => '2025-08-30 10:00:00'
            ],
            [
                'title' => 'Blender vs. Maya: Which Should You Learn First?',
                'category_name' => 'Animation & VFX',
                'excerpt' => 'An objective comparison of the two leading 3D software packages, breaking down industry standard expectations versus open-source flexibility.',
                'reading_time' => '8 min read',
                'published_at' => '2025-08-12 10:00:00'
            ]
        ];

        foreach ($blogs as $b) {
            Blog::firstOrCreate(
                ['slug' => Str::slug($b['title'])],
                [
                    'title' => $b['title'],
                    'category_id' => $categoryIds[$b['category_name']],
                    'excerpt' => $b['excerpt'],
                    'content' => '<p>' . $b['excerpt'] . '</p><p>This is a full placeholder content for the article. The original article was hardcoded as a card only, so content needs to be filled from the CMS.</p>',
                    'author' => 'MAAC Team',
                    'reading_time' => $b['reading_time'],
                    'status' => 'published',
                    'published_at' => $b['published_at']
                ]
            );
        }
    }
}
