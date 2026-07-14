<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pricing;

class PricingSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic',
                'price' => '9.99',
                'billing_period' => 'month',
                'description' => 'Perfect for small projects and individuals.',
                'features' => ['1 User', '5GB Storage', 'Basic Support'],
                'button_text' => 'Get Started',
                'button_url' => '/checkout/basic',
                'is_featured' => false,
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name' => 'Pro',
                'price' => '29.99',
                'billing_period' => 'month',
                'description' => 'Ideal for growing businesses and teams.',
                'features' => ['5 Users', '50GB Storage', 'Priority Support', 'Custom Domain'],
                'button_text' => 'Choose Pro',
                'button_url' => '/checkout/pro',
                'is_featured' => true,
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'Enterprise',
                'price' => '99.99',
                'billing_period' => 'month',
                'description' => 'For large scale organizations.',
                'features' => ['Unlimited Users', '500GB Storage', '24/7 Dedicated Support', 'Custom Integrations'],
                'button_text' => 'Contact Sales',
                'button_url' => '/contact',
                'is_featured' => false,
                'is_active' => true,
                'order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            Pricing::firstOrCreate(['name' => $plan['name']], $plan);
        }
    }
}
