<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CatalogItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CatalogItem>
 */
class CatalogItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'emoji' => '🛒',
            'category' => $this->faker->randomElement(['fruta', 'legumes', 'lacticinios', 'carne', 'despensa']),
            'preferred_store' => null,
            'default_unit' => $this->faker->randomElement(['un', 'kg', 'g', 'l']),
            'default_quantity' => $this->faker->randomFloat(2, 0.5, 3),
        ];
    }
}
