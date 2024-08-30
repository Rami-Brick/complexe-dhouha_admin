<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'level' => $this->faker->randomElement(['bébé', '1-2 ans', '2-3 ans', '3 ans', '4 ans', '5 ans']),
            'staff_id' => Staff::factory(),
            'registration_id' => Product::type(Product::TYPE_REGISTRATION)->value('id'),
            'scholarship_id' => Product::type(Product::TYPE_SCHOLARSHIP)->value('id'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
