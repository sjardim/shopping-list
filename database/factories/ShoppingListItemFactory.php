<?php

namespace Database\Factories;

use App\Enums\Category;
use App\Models\ShoppingList;
use App\Models\ShoppingListItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ShoppingListItem>
 */
class ShoppingListItemFactory extends Factory
{
    private static array $items = [
        ['Milk', '🥛', 'lacticinios', 'l'],
        ['Eggs', '🥚', 'lacticinios', 'dz'],
        ['Bread', '🍞', 'padaria', 'un'],
        ['Butter', '🧈', 'lacticinios', 'g'],
        ['Tomatoes', '🍅', 'legumes', 'kg'],
        ['Apples', '🍎', 'fruta', 'kg'],
        ['Chicken', '🐔', 'carne', 'kg'],
        ['Pasta', '🍝', 'despensa', 'g'],
        ['Olive oil', '🫙', 'despensa', 'ml'],
        ['Onions', '🧅', 'legumes', 'kg'],
    ];

    public function definition(): array
    {
        $item = $this->faker->randomElement(self::$items);
        $this->faker->randomElement(Category::cases());

        return [
            'shopping_list_id' => ShoppingList::factory(),
            'catalog_item_id' => null,
            'name' => $item[0],
            'emoji' => $item[1],
            'category' => $item[2],
            'quantity' => $this->faker->randomFloat(2, 0.5, 5),
            'unit' => $item[3],
            'preferred_store' => null,
            'is_bought' => false,
            'bought_at' => null,
            'sort_order' => 0,
        ];
    }

    public function bought(): static
    {
        return $this->state(fn (): array => [
            'is_bought' => true,
            'bought_at' => now(),
        ]);
    }
}
