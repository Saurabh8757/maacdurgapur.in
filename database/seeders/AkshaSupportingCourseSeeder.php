<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AkshaSupportingCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $brand = \App\Models\Brand::where('slug', 'aksha')->first();
        $brandId = $brand ? $brand->id : null;

        \App\Models\AkshaSupportingCourse::truncate();

        $courses = [
            [
                'title' => 'Graphic Design',
                'slug' => 'graphic-design',
                'course_category' => 'Design',
                'short_description' => 'Master visual communication, branding, and layout design using Photoshop, Illustrator, and InDesign.',
                'outcome' => 'Graphic Designer',
                'skills' => ['Photoshop', 'Illustrator', 'Branding'],
                'sort_order' => 1,
            ],
            [
                'title' => 'UI/UX Design',
                'slug' => 'ui-ux-design',
                'course_category' => 'Design',
                'short_description' => 'Design intuitive and engaging user experiences for web and mobile applications using Figma and Adobe XD.',
                'outcome' => 'UI/UX Designer',
                'skills' => ['Figma', 'Wireframing', 'Prototyping'],
                'sort_order' => 2,
            ],
            [
                'title' => 'Motion Graphics',
                'slug' => 'motion-graphics',
                'course_category' => 'Animation',
                'short_description' => 'Bring static designs to life with advanced animation techniques in After Effects and Premiere Pro.',
                'outcome' => 'Motion Designer',
                'skills' => ['After Effects', 'Kinetic Typography'],
                'sort_order' => 3,
            ],
            [
                'title' => 'Video Editing',
                'slug' => 'video-editing',
                'course_category' => 'Video',
                'short_description' => 'Learn professional non-linear editing, color grading, and audio mixing for YouTube, films, and social media.',
                'outcome' => 'Video Editor',
                'skills' => ['Premiere Pro', 'Color Grading'],
                'sort_order' => 4,
            ],
            [
                'title' => 'Web Development',
                'slug' => 'web-development',
                'course_category' => 'Development',
                'short_description' => 'Build responsive, modern websites from scratch using HTML5, CSS3, JavaScript, and popular frontend frameworks.',
                'outcome' => 'Frontend Developer',
                'skills' => ['HTML/CSS', 'JavaScript', 'React'],
                'sort_order' => 5,
            ],
            [
                'title' => 'Full Stack Development',
                'slug' => 'full-stack-development',
                'course_category' => 'Development',
                'short_description' => 'Master both frontend interfaces and backend databases to build complete, scalable web applications.',
                'outcome' => 'Full Stack Engineer',
                'skills' => ['Node.js', 'Databases', 'APIs'],
                'sort_order' => 6,
            ],
            [
                'title' => 'Python Programming',
                'slug' => 'python-programming',
                'course_category' => 'Programming',
                'short_description' => 'Learn the world\'s most versatile language for automation, backend development, and introductory data science.',
                'outcome' => 'Python Developer',
                'skills' => ['Python', 'Automation', 'Django'],
                'sort_order' => 7,
            ],
            [
                'title' => 'Data Analytics',
                'slug' => 'data-analytics',
                'course_category' => 'Marketing',
                'short_description' => 'Turn raw data into actionable business insights using SQL, Excel, Tableau, and Python data libraries.',
                'outcome' => 'Data Analyst',
                'skills' => ['SQL', 'Tableau', 'Data Viz'],
                'sort_order' => 8,
            ],
            [
                'title' => 'AI Tools & Automation',
                'slug' => 'ai-tools-and-automation',
                'course_category' => 'Technology',
                'short_description' => 'Future-proof your career by integrating generative AI and automated workflows into daily business operations.',
                'outcome' => 'AI Specialist',
                'skills' => ['ChatGPT', 'MidJourney', 'Zapier'],
                'sort_order' => 9,
            ],
            [
                'title' => 'SEO & Performance',
                'slug' => 'seo-and-performance',
                'course_category' => 'Marketing',
                'short_description' => 'Drive organic traffic and manage high-budget paid ad campaigns across search and social networks.',
                'outcome' => 'Performance Marketer',
                'skills' => ['Google Ads', 'On-Page SEO', 'Analytics'],
                'sort_order' => 10,
            ],
            [
                'title' => 'Social Media Marketing',
                'slug' => 'social-media-marketing',
                'course_category' => 'Marketing',
                'short_description' => 'Build vibrant brand communities, run influencer campaigns, and master engagement on Instagram, LinkedIn & X.',
                'outcome' => 'Social Media Manager',
                'skills' => ['Community', 'Instagram', 'Strategy'],
                'sort_order' => 11,
            ],
            [
                'title' => 'Content & Branding',
                'slug' => 'content-and-branding',
                'course_category' => 'Marketing',
                'short_description' => 'Master the art of storytelling, copywriting, and visual branding to create compelling corporate narratives.',
                'outcome' => 'Content Strategist',
                'skills' => ['Copywriting', 'Storytelling', 'Brand Identity'],
                'sort_order' => 12,
            ],
        ];

        foreach ($courses as $course) {
            \App\Models\AkshaSupportingCourse::create(array_merge($course, [
                'brand_id' => $brandId,
                'is_active' => true,
                'is_featured' => false,
                'featured_image_media_id' => null, // Relying on frontend fallback to prevent duplicate storage
            ]));
        }
    }
}
