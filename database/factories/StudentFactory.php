<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Relative;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
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
            'first_name'=>fake()->firstName(),
            'last_name'=>fake()->lastName(),
            'birth_date'=>fake()->date(),
            'course_id'=>Course::factory(),
            'gender' =>'boy',
            'relative_id'=>Relative::factory(),
            'payment_status' => $this->faker->randomElement(['Paid','Overdue','Partial']),
            'comments'=> fake()->Text(),
            'leave_with' => $this->faker->word(),
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
