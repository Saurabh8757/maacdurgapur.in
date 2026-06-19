<?php

namespace Database\Seeders;

use App\Models\SettingDefinition;
use App\Models\SettingGroup;
use Illuminate\Database\Seeder;
use RuntimeException;

class SettingDefinitionSeeder extends Seeder
{
    public function run(): void
    {
        $groupIds = SettingGroup::query()->pluck('id', 'code');

        $definitions = [
            ['general', 'general.site_name', 'Site Name', 'string', 'text', true, true, ['max' => 150]],
            ['general', 'general.short_name', 'Short Name', 'string', 'text', false, true, ['max' => 50]],
            ['general', 'general.tagline', 'Tagline', 'string', 'text', false, true, ['max' => 255]],
            ['general', 'general.copyright_text', 'Copyright Text', 'string', 'text', false, true, ['max' => 255]],
            ['general', 'general.default_locale', 'Default Locale', 'enum', 'select', true, false, ['allowed' => ['en']]],
            ['general', 'general.timezone', 'Timezone', 'enum', 'select', true, false, ['allowed' => ['Asia/Calcutta']]],

            ['contact', 'contact.primary_email', 'Primary Email', 'email', 'email', false, true, ['max' => 254]],
            ['contact', 'contact.primary_phone', 'Primary Phone', 'phone', 'text', false, true, ['max' => 30]],
            ['contact', 'contact.whatsapp_number', 'WhatsApp Number', 'phone', 'text', false, true, ['max' => 30]],
            ['contact', 'contact.address', 'Address', 'text', 'textarea', false, true, ['max' => 1000]],
            ['contact', 'contact.map_url', 'Map URL', 'url', 'url', false, true, ['schemes' => ['http', 'https']]],

            ['theme', 'theme.primary_color', 'Primary Color', 'color', 'color', true, false, ['format' => 'hex']],
            ['theme', 'theme.secondary_color', 'Secondary Color', 'color', 'color', true, false, ['format' => 'hex']],
            ['theme', 'theme.accent_color', 'Accent Color', 'color', 'color', true, false, ['format' => 'hex']],
            ['theme', 'theme.background_color', 'Background Color', 'color', 'color', true, false, ['format' => 'hex']],
            ['theme', 'theme.surface_color', 'Surface Color', 'color', 'color', false, false, ['format' => 'hex']],
            ['theme', 'theme.text_color', 'Text Color', 'color', 'color', true, false, ['format' => 'hex']],
            ['theme', 'theme.muted_text_color', 'Muted Text Color', 'color', 'color', false, false, ['format' => 'hex']],
            ['theme', 'theme.link_color', 'Link Color', 'color', 'color', false, false, ['format' => 'hex']],
            ['theme', 'theme.button_style', 'Button Style', 'enum', 'select', false, false, ['allowed' => ['square', 'rounded', 'pill']]],
            ['theme', 'theme.border_radius', 'Border Radius', 'enum', 'select', false, false, ['allowed' => ['none', 'small', 'medium', 'large']]],
            ['theme', 'theme.shadow_preset', 'Shadow Preset', 'enum', 'select', false, false, ['allowed' => ['none', 'subtle', 'medium', 'strong']]],

            ['typography', 'typography.heading_font', 'Heading Font', 'font', 'select', true, false, ['catalogue' => 'approved']],
            ['typography', 'typography.body_font', 'Body Font', 'font', 'select', true, false, ['catalogue' => 'approved']],
            ['typography', 'typography.accent_font', 'Accent Font', 'font', 'select', false, false, ['catalogue' => 'approved']],
            ['typography', 'typography.base_font_size', 'Base Font Size', 'integer', 'number', false, false, ['min' => 12, 'max' => 24]],
            ['typography', 'typography.heading_scale', 'Heading Scale', 'enum', 'select', false, false, ['allowed' => ['compact', 'standard', 'display']]],
            ['typography', 'typography.line_height', 'Line Height', 'decimal', 'number', false, false, ['min' => 1, 'max' => 2.5]],

            ['header', 'header.sticky_enabled', 'Sticky Header', 'boolean', 'toggle', false, false, []],
            ['header', 'header.primary_cta_label', 'Primary CTA Label', 'string', 'text', false, true, ['max' => 80]],
            ['header', 'header.primary_cta_url', 'Primary CTA URL', 'url', 'url', false, false, ['schemes' => ['http', 'https']]],
            ['header', 'header.announcement_enabled', 'Announcement Enabled', 'boolean', 'toggle', false, false, []],
            ['header', 'header.announcement_text', 'Announcement Text', 'string', 'text', false, true, ['max' => 255]],

            ['footer', 'footer.summary', 'Footer Summary', 'text', 'textarea', false, true, ['max' => 1000]],
            ['footer', 'footer.newsletter_enabled', 'Newsletter Enabled', 'boolean', 'toggle', false, false, []],
            ['footer', 'footer.legal_text', 'Footer Legal Text', 'text', 'textarea', false, true, ['max' => 1000]],

            ['loader', 'loader.enabled', 'Loader Enabled', 'boolean', 'toggle', false, false, []],
            ['loader', 'loader.type', 'Loader Type', 'enum', 'select', false, false, ['allowed' => ['logo', 'spinner', 'progress']]],
            ['loader', 'loader.minimum_duration_ms', 'Minimum Duration', 'integer', 'number', false, false, ['min' => 0, 'max' => 4000]],
            ['loader', 'loader.maximum_duration_ms', 'Maximum Duration', 'integer', 'number', false, false, ['min' => 0, 'max' => 10000]],
            ['loader', 'loader.background_color', 'Loader Background Color', 'color', 'color', false, false, ['format' => 'hex']],
            ['loader', 'loader.reduced_motion_behavior', 'Reduced Motion Behavior', 'enum', 'select', false, false, ['allowed' => ['disable', 'static', 'minimal']]],

            ['global_visuals', 'visual.reduced_motion_fallback', 'Reduced Motion Fallback', 'enum', 'select', true, false, ['allowed' => ['disable', 'minimal', 'static']]],
            ['global_visuals', 'visual.default_section_spacing', 'Default Section Spacing', 'enum', 'select', false, false, ['allowed' => ['compact', 'standard', 'spacious']]],
            ['global_visuals', 'visual.image_treatment', 'Image Treatment', 'enum', 'select', false, false, ['allowed' => ['natural', 'rounded', 'framed']]],
            ['global_visuals', 'visual.video_autoplay_policy', 'Video Autoplay Policy', 'enum', 'select', false, false, ['allowed' => ['disabled', 'muted_only']]],
            ['global_visuals', 'visual.parallax_intensity', 'Parallax Intensity', 'integer', 'number', false, false, ['min' => 0, 'max' => 100]],
            ['global_visuals', 'visual.animations_enabled', 'Animations Enabled', 'boolean', 'toggle', false, false, []],
            ['global_visuals', 'visual.threejs_enabled', 'Three.js Enabled', 'boolean', 'toggle', false, false, []],
            ['global_visuals', 'visual.mobile_effects_mode', 'Mobile Effects Mode', 'enum', 'select', false, false, ['allowed' => ['disabled', 'reduced', 'full']]],

            ['forms', 'forms.default_success_message', 'Default Success Message', 'string', 'text', false, true, ['max' => 500]],
            ['forms', 'forms.consent_text', 'Consent Text', 'text', 'textarea', false, true, ['max' => 2000]],
            ['forms', 'forms.privacy_policy_url', 'Privacy Policy URL', 'url', 'url', false, false, ['schemes' => ['http', 'https']]],
            ['forms', 'forms.notification_email', 'Notification Email', 'email', 'email', false, false, ['max' => 254]],

            ['social_media', 'social.facebook_url', 'Facebook URL', 'url', 'url', false, false, ['schemes' => ['http', 'https']]],
            ['social_media', 'social.instagram_url', 'Instagram URL', 'url', 'url', false, false, ['schemes' => ['http', 'https']]],
            ['social_media', 'social.youtube_url', 'YouTube URL', 'url', 'url', false, false, ['schemes' => ['http', 'https']]],
            ['social_media', 'social.linkedin_url', 'LinkedIn URL', 'url', 'url', false, false, ['schemes' => ['http', 'https']]],
            ['social_media', 'social.whatsapp_url', 'WhatsApp URL', 'url', 'url', false, false, ['schemes' => ['http', 'https']]],

            ['legal', 'legal.privacy_summary', 'Privacy Summary', 'text', 'textarea', false, true, ['max' => 3000]],
            ['legal', 'legal.cookie_notice', 'Cookie Notice', 'text', 'textarea', false, true, ['max' => 3000]],
            ['legal', 'legal.terms_url', 'Terms URL', 'url', 'url', false, false, ['schemes' => ['http', 'https']]],
            ['legal', 'legal.data_consent_text', 'Data Consent Text', 'text', 'textarea', false, true, ['max' => 3000]],
            ['legal', 'legal.media_copyright_notice', 'Media Copyright Notice', 'text', 'textarea', false, true, ['max' => 2000]],
        ];

        $sortOrders = [];

        foreach ($definitions as $definition) {
            [$groupCode, $key, $name, $dataType, $inputType, $required, $translatable, $rules] = $definition;

            if (!isset($groupIds[$groupCode])) {
                throw new RuntimeException("Missing setting group: {$groupCode}");
            }

            $sortOrders[$groupCode] = ($sortOrders[$groupCode] ?? 0) + 10;

            SettingDefinition::updateOrCreate(
                ['key' => $key],
                [
                    'setting_group_id' => $groupIds[$groupCode],
                    'name' => $name,
                    'description' => null,
                    'data_type' => $dataType,
                    'input_type' => $inputType,
                    'default_value' => null,
                    'validation_rules' => $rules,
                    'options' => $rules['allowed'] ?? null,
                    'is_required' => $required,
                    'is_translatable' => $translatable,
                    'is_brand_overridable' => true,
                    'is_sensitive' => false,
                    'is_public' => true,
                    'requires_publish' => true,
                    'sort_order' => $sortOrders[$groupCode],
                    'status' => 'inactive',
                ]
            );
        }
    }
}
