<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Relative>
 */
class RelativeFactory extends Factory
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
            'father_name' => fake()->Name(),
            'mother_name' => fake()->Name(),
            'phone_father' => $this->faker->unique()->numberBetween(93000000,99000000),
            'phone_mother' => $this->faker->unique()->numberBetween(93000000,99000000),
            'job_father' => fake()->JobTitle(),
            'job_mother' => fake()->JobTitle(),
            'cin_father' => $this->faker->unique()->numberBetween(10000000,30000000),
            'cin_mother' => $this->faker->unique()->numberBetween(10000000,30000000),
            'email' => fake()->unique()->Email(),
            'address' => fake()->Address(),
            'notes' => fake()->Text(),
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
