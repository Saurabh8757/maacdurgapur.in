<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['brands.view', 'brand', 'view', 'brand', 'low'],
            ['brands.access_global_scope', 'brand', 'access_global_scope', 'global', 'critical'],

            ['settings.brand.view', 'settings', 'view', 'brand', 'low'],
            ['settings.brand.edit', 'settings', 'edit', 'brand', 'medium'],
            ['settings.brand.submit', 'settings', 'submit', 'brand', 'medium'],
            ['settings.brand.approve', 'settings', 'approve', 'brand', 'high'],
            ['settings.brand.publish', 'settings', 'publish', 'brand', 'high'],
            ['settings.brand.rollback', 'settings', 'rollback', 'brand', 'critical'],
            ['settings.global.view', 'settings', 'view', 'global', 'medium'],
            ['settings.global.edit', 'settings', 'edit', 'global', 'high'],
            ['settings.global.publish', 'settings', 'publish', 'global', 'critical'],
            ['settings.definitions.manage', 'settings', 'manage_definitions', 'global', 'critical'],
            ['settings.sensitive.view', 'settings', 'view_sensitive', 'either', 'high'],
            ['settings.sensitive.edit', 'settings', 'edit_sensitive', 'either', 'critical'],

            ['media.assets.view', 'media', 'view', 'brand', 'low'],
            ['media.assets.upload', 'media', 'upload', 'brand', 'medium'],
            ['media.assets.edit_metadata', 'media', 'edit_metadata', 'brand', 'medium'],
            ['media.assets.organize', 'media', 'organize', 'brand', 'medium'],
            ['media.assets.attach', 'media', 'attach', 'brand', 'medium'],
            ['media.assets.publish', 'media', 'publish', 'brand', 'high'],
            ['media.assets.replace', 'media', 'replace', 'brand', 'high'],
            ['media.assets.archive', 'media', 'archive', 'brand', 'high'],
            ['media.assets.restore', 'media', 'restore', 'brand', 'high'],
            ['media.assets.purge', 'media', 'purge', 'either', 'critical'],
            ['media.assets.manage_shared', 'media', 'manage_shared', 'global', 'critical'],
            ['media.private.view', 'media', 'view_private', 'either', 'high'],
            ['media.private.download', 'media', 'download_private', 'either', 'critical'],

            ['cms.faqs.view', 'cms', 'view', 'brand', 'low'],
            ['cms.faqs.create', 'cms', 'create', 'brand', 'medium'],
            ['cms.faqs.edit', 'cms', 'edit', 'brand', 'medium'],
            ['cms.faqs.delete', 'cms', 'delete', 'brand', 'high'],

            ['cms.courses.view', 'cms', 'view', 'brand', 'low'],
            ['cms.courses.create', 'cms', 'create', 'brand', 'medium'],
            ['cms.courses.edit', 'cms', 'edit', 'brand', 'medium'],
            ['cms.courses.delete', 'cms', 'delete', 'brand', 'high'],

            ['cms.features.view', 'cms', 'view', 'brand', 'low'],
            ['cms.features.create', 'cms', 'create', 'brand', 'medium'],
            ['cms.features.edit', 'cms', 'edit', 'brand', 'medium'],
            ['cms.features.delete', 'cms', 'delete', 'brand', 'high'],

            ['cms.showcase.view', 'cms', 'view', 'brand', 'low'],
            ['cms.showcase.create', 'cms', 'create', 'brand', 'medium'],
            ['cms.showcase.edit', 'cms', 'edit', 'brand', 'medium'],
            ['cms.showcase.delete', 'cms', 'delete', 'brand', 'high'],
            ['cms.showcase.publish', 'cms', 'publish', 'brand', 'high'],
        ];

        foreach ($permissions as [$code, $domain, $action, $scopeType, $riskLevel]) {
            $segments = explode('.', $code);
            $resource = count($segments) > 2 ? $segments[1] : $segments[0];

            Permission::updateOrCreate(
                ['code' => $code],
                [
                    'domain' => $domain,
                    'resource' => $resource,
                    'action' => $action,
                    'name' => Str::of($code)->replace('.', ' ')->title(),
                    'description' => null,
                    'scope_type' => $scopeType,
                    'risk_level' => $riskLevel,
                    'requires_mfa' => in_array($riskLevel, ['critical'], true),
                    'requires_reauthentication' => in_array($riskLevel, ['critical'], true),
                    'is_delegable' => !in_array($riskLevel, ['critical'], true),
                    'status' => 'active',
                ]
            );
        }
    }
}
