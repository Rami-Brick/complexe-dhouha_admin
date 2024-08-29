<?php

namespace Database\Factories;

use App\Models\Configs;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Configs>
 */
class ConfigsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $products = [
            'Inscription' => 100,
            'Scholarship' => 80,
            'Canteen' => 60,
            'Daycare' => 40,
            'Daycare weekend' => 20,
        ];

        $productName = $this->faker->randomElement(array_keys($products));

        return [
            'name' => $productName,
            'value' => $products[$productName],
        ];
    }
}
