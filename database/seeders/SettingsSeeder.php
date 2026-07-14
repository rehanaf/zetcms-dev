<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [

            // ─────────────────────────────────────────
            // GROUP: General Settings
            // ─────────────────────────────────────────
            [
                'key'   => 'site_name',
                'value' => 'ZetCMS',
                'type'  => 'text',
                'group' => 'general',
            ],
            [
                'key'   => 'site_tagline',
                'value' => 'Powerful Content Management System',
                'type'  => 'text',
                'group' => 'general',
            ],
            [
                'key'   => 'site_email',
                'value' => 'info@example.com',
                'type'  => 'text',
                'group' => 'general',
            ],
            [
                'key'   => 'site_phone',
                'value' => '+62 000 0000 0000',
                'type'  => 'text',
                'group' => 'general',
            ],
            [
                'key'   => 'site_address',
                'value' => 'Jl. Contoh No. 1, Jakarta, Indonesia',
                'type'  => 'textarea',
                'group' => 'general',
            ],
            [
                'key'   => 'site_logo',
                'value' => null,
                'type'  => 'image',
                'group' => 'general',
            ],
            [
                'key'   => 'site_favicon',
                'value' => null,
                'type'  => 'image',
                'group' => 'general',
            ],
            [
                'key'   => 'homepage_page_id',
                'value' => null,
                'type'  => 'text',
                'group' => 'general',
            ],
            [
                'key'   => 'maintenance_mode',
                'value' => '0',
                'type'  => 'boolean',
                'group' => 'general',
            ],
            [
                'key'   => 'copyright_text',
                'value' => '© 2025 ZetCMS. All rights reserved.',
                'type'  => 'text',
                'group' => 'general',
            ],

            // ─────────────────────────────────────────
            // GROUP: Menu Locations
            // ─────────────────────────────────────────
            [
                'key'   => 'menu_header',
                'value' => null,
                'type'  => 'text',
                'group' => 'general',
            ],
            [
                'key'   => 'menu_footer',
                'value' => null,
                'type'  => 'text',
                'group' => 'general',
            ],
            [
                'key'   => 'menu_sidebar',
                'value' => null,
                'type'  => 'text',
                'group' => 'general',
            ],

            // ─────────────────────────────────────────
            // GROUP: SEO Default Settings
            // ─────────────────────────────────────────
            [
                'key'   => 'seo_meta_title',
                'value' => 'ZetCMS — Powerful CMS',
                'type'  => 'text',
                'group' => 'seo',
            ],
            [
                'key'   => 'seo_meta_description',
                'value' => 'ZetCMS adalah platform manajemen konten yang powerful dan fleksibel untuk website modern.',
                'type'  => 'textarea',
                'group' => 'seo',
            ],
            [
                'key'   => 'seo_meta_keywords',
                'value' => 'cms, laravel, filament, website',
                'type'  => 'text',
                'group' => 'seo',
            ],
            [
                'key'   => 'seo_og_image',
                'value' => null,
                'type'  => 'image',
                'group' => 'seo',
            ],
            [
                'key'   => 'seo_google_analytics_id',
                'value' => '',
                'type'  => 'text',
                'group' => 'seo',
            ],
            [
                'key'   => 'seo_google_tag_manager_id',
                'value' => '',
                'type'  => 'text',
                'group' => 'seo',
            ],
            [
                'key'   => 'seo_robots_txt',
                'value' => "User-agent: *\nAllow: /",
                'type'  => 'textarea',
                'group' => 'seo',
            ],

            // ─────────────────────────────────────────
            // GROUP: Social Media Links
            // ─────────────────────────────────────────
            [
                'key'   => 'social_facebook',
                'value' => '',
                'type'  => 'text',
                'group' => 'social',
            ],
            [
                'key'   => 'social_instagram',
                'value' => '',
                'type'  => 'text',
                'group' => 'social',
            ],
            [
                'key'   => 'social_twitter',
                'value' => '',
                'type'  => 'text',
                'group' => 'social',
            ],
            [
                'key'   => 'social_youtube',
                'value' => '',
                'type'  => 'text',
                'group' => 'social',
            ],
            [
                'key'   => 'social_tiktok',
                'value' => '',
                'type'  => 'text',
                'group' => 'social',
            ],
            [
                'key'   => 'social_linkedin',
                'value' => '',
                'type'  => 'text',
                'group' => 'social',
            ],
            [
                'key'   => 'social_whatsapp',
                'value' => '',
                'type'  => 'text',
                'group' => 'social',
            ],

            // ─────────────────────────────────────────
            // GROUP: Custom Settings
            // ─────────────────────────────────────────
            [
                'key'   => 'custom_header_scripts',
                'value' => '',
                'type'  => 'textarea',
                'group' => 'custom',
            ],
            [
                'key'   => 'custom_footer_scripts',
                'value' => '',
                'type'  => 'textarea',
                'group' => 'custom',
            ],
            [
                'key'   => 'custom_css',
                'value' => '',
                'type'  => 'textarea',
                'group' => 'custom',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('✅ Settings seeded: ' . count($settings) . ' entries.');
    }
}
