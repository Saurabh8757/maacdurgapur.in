<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminLoginSeeder::class,
            BrandSeeder::class,
            BrandDomainSeeder::class,
            BrandUserSeeder::class,
            SettingGroupSeeder::class,
            SettingDefinitionSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            CmsSeeder::class,
            SiteInfoSeeder::class,
            ContactInfoSeeder::class,
            AboutPageSeeder::class,
        ]);
    }
}
