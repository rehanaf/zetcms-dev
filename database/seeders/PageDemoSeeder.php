<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\Theme;
use App\Models\Layout;
use App\Models\Component;
use App\Models\PageComponent;
use App\Models\User;
use App\Models\Form;
use Illuminate\Support\Str;

class PageDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure Theme and Layout exist
        $theme = Theme::firstOrCreate(
            ['slug' => 'elegant'],
            ['name' => 'Elegant Theme', 'version' => '1.0.0', 'is_active' => true]
        );

        $layout = Layout::firstOrCreate(
            ['slug' => 'default'],
            ['theme_id' => $theme->id, 'name' => 'Default Layout', 'view_path' => 'layouts.app', 'is_default' => true]
        );

        $user = User::first();
        if (!$user) {
            $user = User::updateOrCreate(
                ['email' => 'admin@example.com'],
                [
                    'name' => 'Admin',
                    'password' => \Illuminate\Support\Facades\Hash::make('password'),
                    'email_verified_at' => now(),
                    'role_id' => 1,
                ]
            );
        }

        // Create Components for different block types
        $components = [];

        // 1. Hero Block
        $components[] = Component::firstOrCreate(
            ['slug' => 'hero-all-in-one'],
            [
                'theme_id' => $theme->id,
                'name' => 'Hero Demo',
                'type' => 'hero',
                'variant' => 'demo',
                'settings' => [
                    'heading' => 'All Components Demo',
                    'subheading' => 'Scroll down to see all the blocks in action.',
                    'button_text' => 'Get Started',
                    'button_url' => '#',
                ]
            ]
        );

        // 2. Pricing Block
        $components[] = Component::firstOrCreate(
            ['slug' => 'pricing-block-demo'],
            [
                'theme_id' => $theme->id,
                'name' => 'Pricing Block Demo',
                'type' => 'pricing',
                'variant' => 'demo',
                'settings' => [
                    'heading' => 'Our Pricing Plans',
                    'subheading' => 'Choose the plan that fits your needs.',
                ]
            ]
        );

        // 3. Testimonial Block
        $components[] = Component::firstOrCreate(
            ['slug' => 'testimonial-block-demo'],
            [
                'theme_id' => $theme->id,
                'name' => 'Testimonial Block Demo',
                'type' => 'testimonial',
                'variant' => 'demo',
                'settings' => [
                    'heading' => 'What Our Clients Say',
                    'subheading' => 'Read testimonials from our satisfied customers.',
                ]
            ]
        );

        // 4. Form Block
        $contactForm = Form::where('slug', 'contact-us')->first();
        $components[] = Component::firstOrCreate(
            ['slug' => 'form-block-demo'],
            [
                'theme_id' => $theme->id,
                'name' => 'Form Block Demo',
                'type' => 'widget',
                'variant' => 'demo',
                'settings' => [
                    'heading' => 'Contact Us',
                    'subheading' => 'We would love to hear from you.',
                    'form_id' => $contactForm ? $contactForm->id : null,
                ]
            ]
        );

        // Create the Page
        $page = Page::firstOrCreate(
            ['slug' => 'all-components'],
            [
                'user_id' => $user->id,
                'layout_id' => $layout->id,
                'title' => 'Home Elegant',
                'content' => [
                    [
                        'type' => 'carousel',
                        'data' => [
                            'variant' => 'default',
                            'title' => 'Hero Slider',
                            'items' => [
                                ['title' => 'Rayakan momen terindah hidup Anda', 'description' => 'Dapoer Cendana menghadirkan kemewahan ruang, suasana elegan, dan layanan premium untuk hari pernikahan impian Anda.'],
                                ['title' => 'Ruang pertemuan representatif & modern', 'description' => 'Tingkatkan produktivitas bisnis Anda dengan ruang rapat premium yang didukung fasilitas audio-visual mutakhir dan hidangan kelas dunia.'],
                                ['title' => 'Perpaduan cita rasa autentik & elegansi', 'description' => 'Manjakan selera Anda dengan sajian kuliner bercita rasa istimewa racikan Chef profesional kami di suasana restoran yang hangat.']
                            ]
                        ]
                    ],
                    [
                        'type' => 'about',
                        'data' => [
                            'variant' => 'default',
                            'title' => 'Sinergi antara Kuliner Berkelas dan Ruang Acara Eksklusif',
                            'content' => '<p>Berlokasi strategis, <strong>Dapoer Cendana</strong> merupakan destinasi prima yang memadukan keindahan restoran bernuansa estetika kolonial-modern dengan fungsionalitas ruang serbaguna tingkat tinggi.</p><p>Kami menyediakan area elegan untuk berbagai perhelatan istimewa Anda, mulai dari pernikahan megah, rapat bisnis korporat yang kondusif, pelatihan profesional, hingga pengajian keluarga besar yang khidmat. Kami hadir untuk memastikan setiap momen berharga Anda di dapoercendana.id berjalan dengan sempurna.</p>'
                        ]
                    ],
                    [
                        'type' => 'feature',
                        'data' => [
                            'variant' => 'default',
                            'title' => 'Pilihan Ruang & Acara Sesuai Kebutuhan Anda',
                            'features' => [
                                ['title' => 'Wedding Hall', 'description' => 'Rayakan perhelatan janji suci romantis Anda dalam suasana megah yang intim dan artistik bersama keluarga besar.'],
                                ['title' => 'Meeting Space', 'description' => 'Tingkatkan performa bisnis Anda melalui ruang pertemuan representatif dengan kelengkapan mutakhir.'],
                                ['title' => 'Training Center', 'description' => 'Konfigurasi ruangan fleksibel yang ramah untuk edukasi, seminar interaktif, dan sertifikasi tim.'],
                                ['title' => 'Pengajian & Aqiqah', 'description' => 'Selenggarakan doa bersama, tasyakuran aqiqah, dan arisan keluarga dalam nuansa bersih nan menenangkan.']
                            ]
                        ]
                    ],
                ],
                'status' => 'published',
                'published_at' => now(),
                'is_homepage' => true,
            ]
        );

        // Attach Components to Page
        // First, clear existing components for this page if any
        $page->components()->delete();

        foreach ($components as $index => $component) {
            PageComponent::create([
                'page_id' => $page->id,
                'component_id' => $component->id,
                'region' => 'main',
                'order' => $index + 1,
                'is_active' => true,
            ]);
        }
    }
}
