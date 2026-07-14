<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\MenuItem;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $mainMenu = Menu::firstOrCreate(
            ['slug' => 'main-menu'],
            ['name' => 'Main Menu', 'location' => 'header']
        );

        MenuItem::firstOrCreate(['menu_id' => $mainMenu->id, 'url' => '/'], ['title' => 'Home', 'order' => 1]);
        MenuItem::firstOrCreate(['menu_id' => $mainMenu->id, 'url' => '/about'], ['title' => 'About', 'order' => 2]);
        MenuItem::firstOrCreate(['menu_id' => $mainMenu->id, 'url' => '/services'], ['title' => 'Services', 'order' => 3]);
        MenuItem::firstOrCreate(['menu_id' => $mainMenu->id, 'url' => '/contact'], ['title' => 'Contact', 'order' => 4]);
    }
}
