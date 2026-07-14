<?php

namespace Database\Seeders;

use App\Models\Component;
use App\Models\Layout;
use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemeDemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat theme "default" dan aktifkan
        $theme = Theme::create([
            'name'      => 'Default Theme',
            'slug'      => 'default',
            'version'   => '1.0.0',
            'is_active' => true,
        ]);

        // 2. Daftarkan layout milik theme ini
        Layout::create([
            'theme_id'   => $theme->id,
            'name'       => 'Default Layout',
            'slug'       => 'default',
            'view_path'  => 'layouts.app', // akan jadi themes.default.layouts.app
            'is_default' => true,
        ]);

        // 3. Daftarkan 3 variant Hero
        Component::create([
            'theme_id' => $theme->id,
            'name'     => 'Hero - Simpel',
            'slug'     => 'hero-variant1',
            'type'     => 'hero',
            'variant'  => 'variant1',
            'settings' => [
                'heading'     => 'Selamat Datang di Platform Kami',
                'subheading'  => 'Solusi terbaik untuk bisnis Anda',
                'bg_color'    => '#1e293b',
                'button_text' => 'Mulai Sekarang',
                'button_url'  => '/register',
            ],
        ]);

        Component::create([
            'theme_id' => $theme->id,
            'name'     => 'Hero - Dengan Gambar',
            'slug'     => 'hero-variant2',
            'type'     => 'hero',
            'variant'  => 'variant2',
            'settings' => [
                'heading'    => 'Solusi Bisnis Modern',
                'subheading' => 'Kelola operasional lebih efisien',
                'image'      => '/storage/hero-img.jpg',
                'button_text' => 'Coba Gratis',
                'button_url'  => '/trial',
            ],
        ]);

        Component::create([
            'theme_id' => $theme->id,
            'name'     => 'Hero - Fullscreen',
            'slug'     => 'hero-variant3',
            'type'     => 'hero',
            'variant'  => 'variant3',
            'settings' => [
                'heading'    => 'Wujudkan Ide Anda',
                'subheading' => 'Bersama kami, dari konsep sampai peluncuran',
                'image'      => '/storage/hero-fullscreen.jpg',
                'button_text' => 'Hubungi Kami',
                'button_url'  => '/contact',
            ],
        ]);

        // 4. Daftarkan Pricing Table
        Component::create([
            'theme_id' => $theme->id,
            'name'     => 'Pricing - 3 Kolom',
            'slug'     => 'pricing-variant1',
            'type'     => 'pricing',
            'variant'  => 'variant1',
            'settings' => [
                'heading'    => 'Pilih Paket yang Sesuai',
                'subheading' => 'Harga transparan, tanpa biaya tersembunyi',
                'plans' => [
                    [
                        'name' => 'Basic', 'price' => '99.000', 'period' => '/bulan',
                        'description' => 'Cocok untuk individu',
                        'features' => ['5 Project', '10GB Storage', 'Email Support'],
                        'button_text' => 'Pilih Paket', 'button_url' => '/checkout/basic',
                        'is_highlighted' => false,
                    ],
                    [
                        'name' => 'Pro', 'price' => '299.000', 'period' => '/bulan',
                        'description' => 'Untuk tim kecil',
                        'features' => ['Unlimited Project', '100GB Storage', 'Priority Support'],
                        'button_text' => 'Pilih Paket', 'button_url' => '/checkout/pro',
                        'is_highlighted' => true,
                    ],
                ],
            ],
        ]);
    }
}
