<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'John Doe',
                'role' => 'CEO',
                'company' => 'Acme Corp',
                'content' => 'This platform completely transformed the way we manage our content.',
                'rating' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Jane Smith',
                'role' => 'Marketing Director',
                'company' => 'Global Tech',
                'content' => 'Extremely easy to use and very fast. Highly recommended!',
                'rating' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Michael Johnson',
                'role' => 'Freelance Developer',
                'company' => '',
                'content' => 'I can build client sites much faster now. Great features.',
                'rating' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($testimonials as $testi) {
            Testimonial::firstOrCreate(['name' => $testi['name']], $testi);
        }
    }
}
