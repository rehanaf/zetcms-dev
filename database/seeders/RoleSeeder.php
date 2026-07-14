<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super-admin', 'description' => 'Akses penuh ke semua fitur dan pengaturan'],
            ['name' => 'Administrator', 'slug' => 'admin',       'description' => 'Dapat mengelola konten dan pengguna'],
            ['name' => 'Editor',        'slug' => 'editor',      'description' => 'Dapat membuat dan mengedit semua konten'],
            ['name' => 'Author',        'slug' => 'author',      'description' => 'Hanya dapat membuat dan mengedit konten sendiri'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
