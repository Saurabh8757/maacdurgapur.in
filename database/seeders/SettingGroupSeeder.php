<?php

namespace Database\Seeders;

use App\Models\SettingGroup;
use Illuminate\Database\Seeder;

class SettingGroupSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            ['code' => 'general', 'name' => 'General', 'icon' => 'fas fa-cog'],
            ['code' => 'brand_identity', 'name' => 'Brand Identity', 'icon' => 'fas fa-copyright'],
            ['code' => 'theme', 'name' => 'Theme', 'icon' => 'fas fa-palette'],
            ['code' => 'typography', 'name' => 'Typography', 'icon' => 'fas fa-font'],
            ['code' => 'header', 'name' => 'Header', 'icon' => 'fas fa-window-maximize'],
            ['code' => 'footer', 'name' => 'Footer', 'icon' => 'fas fa-window-minimize'],
            ['code' => 'contact', 'name' => 'Contact', 'icon' => 'fas fa-address-card'],
            ['code' => 'social_media', 'name' => 'Social Media', 'icon' => 'fas fa-share-alt'],
            ['code' => 'forms', 'name' => 'Forms', 'icon' => 'fas fa-list-alt'],
            ['code' => 'loader', 'name' => 'Loader', 'icon' => 'fas fa-spinner'],
            ['code' => 'global_visuals', 'name' => 'Global Visual Settings', 'icon' => 'fas fa-magic'],
            ['code' => 'legal', 'name' => 'Legal', 'icon' => 'fas fa-balance-scale'],
            ['code' => 'integrations', 'name' => 'Integrations', 'icon' => 'fas fa-plug'],
        ];

        foreach ($groups as $index => $group) {
            SettingGroup::updateOrCreate(
                ['code' => $group['code']],
                [
                    'parent_id' => null,
                    'name' => $group['name'],
                    'description' => null,
                    'icon' => $group['icon'],
                    'sort_order' => ($index + 1) * 10,
                    'status' => 'active',
                ]
            );
        }
    }
}
