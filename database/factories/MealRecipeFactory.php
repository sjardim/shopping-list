<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\MealRecipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MealRecipe>
 */
class MealRecipeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->words(2, true),
            'emoji' => '🍽️',
            'items' => [
                ['name' => 'Frango inteiro', 'quantity' => 1, 'unit' => 'kg'],
                ['name' => 'Batata', 'quantity' => 1, 'unit' => 'kg'],
            ],
        ];
    }
}
