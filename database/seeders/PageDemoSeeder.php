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
            ['slug' => 'default'],
            ['name' => 'Default Theme', 'version' => '1.0.0', 'is_active' => true]
        );

        $layout = Layout::firstOrCreate(
            ['slug' => 'default'],
            ['theme_id' => $theme->id, 'name' => 'Default Layout', 'view_path' => 'layouts.app', 'is_default' => true]
        );

        $user = User::first();
        if (!$user) {
            $user = User::factory()->create(['name' => 'Admin', 'email' => 'admin@example.com']);
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
                'title' => 'All Components Demo',
                'content' => [
                    ['type' => 'hero', 'data' => ['variant' => 'default', 'title' => 'Hero Block', 'subtitle' => 'This is the hero subtitle', 'content' => '<p>Welcome to the all-components page.</p>']],
                    ['type' => 'feature', 'data' => ['variant' => 'default', 'title' => 'Features', 'features' => [['title' => 'Fast Performance', 'description' => 'Blazing fast'], ['title' => 'Secure', 'description' => 'Top notch security']]]],
                    ['type' => 'about', 'data' => ['variant' => 'default', 'title' => 'About Us', 'content' => '<p>We are a leading tech company.</p>']],
                    ['type' => 'testimonial', 'data' => ['variant' => 'default', 'title' => 'Testimonials', 'source' => 'manual', 'manual_testimonials' => [['name' => 'John Doe', 'content' => 'Amazing product!']]]],
                    ['type' => 'cta', 'data' => ['variant' => 'default', 'title' => 'Ready to get started?', 'description' => 'Join thousands of users today.']],
                    ['type' => 'pricing', 'data' => ['variant' => 'default', 'title' => 'Our Plans', 'pricing_ids' => [1, 2, 3]]],
                    ['type' => 'faq', 'data' => ['variant' => 'default', 'title' => 'Frequently Asked Questions', 'faqs' => [['question' => 'How does it work?', 'answer' => 'It works perfectly.']]]],
                    ['type' => 'teamMember', 'data' => ['variant' => 'default', 'title' => 'Meet the Team', 'members' => [['name' => 'Alice', 'role' => 'CEO'], ['name' => 'Bob', 'role' => 'CTO']]]],
                    ['type' => 'gallery', 'data' => ['variant' => 'default', 'title' => 'Our Gallery', 'images' => [['caption' => 'Office'], ['caption' => 'Event']]]],
                    ['type' => 'stats', 'data' => ['variant' => 'default', 'title' => 'Our Impact', 'stats' => [['number' => '1M+', 'label' => 'Active Users'], ['number' => '99%', 'label' => 'Satisfaction']]]],
                    ['type' => 'logoCloud', 'data' => ['variant' => 'default', 'title' => 'Trusted By', 'logos' => [['name' => 'Company A'], ['name' => 'Company B']]]],
                    ['type' => 'video', 'data' => ['variant' => 'default', 'title' => 'Watch our Demo', 'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ']],
                    ['type' => 'newsletter', 'data' => ['variant' => 'default', 'title' => 'Subscribe to our Newsletter']],
                    ['type' => 'form', 'data' => ['variant' => 'default', 'title' => 'Contact Us', 'form_id' => 1]],
                    ['type' => 'menu', 'data' => ['variant' => 'default', 'title' => 'Useful Links', 'menu_id' => 1]],
                    ['type' => 'texteditor', 'data' => ['variant' => 'default', 'content' => '<p>This is a standard text editor block.</p>']],
                    ['type' => 'post', 'data' => ['variant' => 'default', 'title' => 'Latest Articles', 'limit' => 3]],
                    ['type' => 'table', 'data' => ['variant' => 'default', 'title' => 'Data Overview', 'rows' => [ ['cells' => [['value' => 'Row 1, Cell 1'], ['value' => 'Row 1, Cell 2']]] ]]],
                    ['type' => 'carousel', 'data' => ['variant' => 'default', 'title' => 'Featured Items', 'items' => [['title' => 'Item 1', 'description' => 'Desc 1']]]],
                    ['type' => 'price_table', 'data' => ['variant' => 'default', 'title' => 'Custom Pricing', 'categories' => [['title' => 'Services', 'items' => [['name' => 'Consulting', 'price' => '$100/hr']]]]]],
                ],
                'status' => 'published',
                'published_at' => now(),
                'is_homepage' => false,
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
