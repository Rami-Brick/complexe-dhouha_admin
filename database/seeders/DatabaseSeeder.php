<?php

namespace Database\Seeders;

use App\Models\Configs;
use App\Models\Event;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $products = [
            ['name' => 'Inscription', 'type' => Product::TYPE_REGISTRATION, 'fee' => '100'],
            ['name' => 'Scholarship', 'type' => Product::TYPE_SCHOLARSHIP, 'fee' => '80'],
            ['name' => 'Canteen', 'type' => Product::TYPE_OPTION, 'fee' => '60'],
            ['name' => 'Daycare', 'type' => Product::TYPE_OPTION, 'fee' => '40'],
            ['name' => 'Daycare weekend', 'type' => Product::TYPE_OPTION, 'fee' => '20'],
        ];

        Product::query()->insert($products);
        \App\Models\Student::factory(4)->create();
        \App\Models\Staff::factory(2)->create();
        \App\Models\Relative::factory(2)->create();
//         \App\Models\Event::factory(10)->create();
//         \App\Models\Objective::factory(10)->create();
        \App\Models\Course::factory(4)->create();
//         \App\Models\Comment::factory(10)->create();
        \App\Models\User::factory(1)->create();


       // User::factory()->create([
       //     'name' => 'Test User',
       //     'email' => 'test@example.com',
       // ]);
    }
}
