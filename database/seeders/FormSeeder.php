<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Form;

class FormSeeder extends Seeder
{
    public function run(): void
    {
        $contactForm = Form::firstOrCreate(
            ['slug' => 'contact-us'],
            [
                'name' => 'Contact Us',
                'success_message' => 'Thank you for reaching out! We will get back to you shortly.',
                'notification_email' => 'admin@example.com',
                'is_active' => true,
            ]
        );

        if ($contactForm->fields()->count() === 0) {
            $contactForm->fields()->createMany([
                ['name' => 'name', 'label' => 'Full Name', 'type' => 'text', 'is_required' => true, 'order' => 1],
                ['name' => 'email', 'label' => 'Email Address', 'type' => 'email', 'is_required' => true, 'order' => 2],
                ['name' => 'subject', 'label' => 'Subject', 'type' => 'text', 'is_required' => false, 'order' => 3],
                ['name' => 'message', 'label' => 'Message', 'type' => 'textarea', 'is_required' => true, 'order' => 4],
            ]);
        }
    }
}
