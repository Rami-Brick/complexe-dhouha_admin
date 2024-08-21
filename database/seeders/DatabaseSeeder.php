<?php

namespace Database\Seeders;

use App\Models\Event;
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
        \App\Models\Student::factory(10)->create();
        \App\Models\Staff::factory(10)->create();
        \App\Models\Relative::factory(10)->create();
        \App\Models\Event::factory(10)->create();
        \App\Models\Objective::factory(10)->create();
        \App\Models\Course::factory(10)->create();
        \App\Models\Comment::factory(10)->create();
        \App\Models\User::factory(1)->create();

       // User::factory()->create([
       //     'name' => 'Test User',
       //     'email' => 'test@example.com',
       // ]);
    }
}
