<?php

namespace Database\Factories;

use App\Contracts\Store;
use App\Enums\ShoppingListStatus;
use App\Models\ShoppingList;
use App\Models\User;
use App\Support\Stores;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ShoppingList>
 */
class ShoppingListFactory extends Factory
{
    public function definition(): array
    {
        $store = $this->faker->randomElement(Stores::active());

        return [
            'user_id' => User::factory(),
            'name' => $store->label().' · '.$this->faker->date('d M'),
            'store' => $store->value,
            'status' => ShoppingListStatus::Active->value,
            'share_token' => (string) Str::uuid(),
            'completed_at' => null,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (): array => [
            'status' => ShoppingListStatus::Completed->value,
            'completed_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ]);
    }

    public function forStore(Store $store): static
    {
        return $this->state(fn (): array => ['store' => $store->value]);
    }
}
