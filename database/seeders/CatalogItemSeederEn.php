<?php

namespace Database\Seeders;

use App\Models\CatalogItem;
use Illuminate\Database\Seeder;

/**
 * English-language catalog using common US grocery items and store hints
 * (Walmart, Target, Trader Joe's, Whole Foods, plus Lidl/Aldi where relevant).
 *
 * Run with: php artisan db:seed --class=CatalogItemSeederEn
 *
 * Pairs naturally with the English meal bundles inside MealBundles when the
 * app locale is set to "en".
 */
class CatalogItemSeederEn extends Seeder
{
    public function run(): void
    {
        $items = [
            // Fruit
            ['name' => 'Apple', 'emoji' => '🍎', 'category' => 'fruta', 'preferred_store' => 'whole_foods', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Banana', 'emoji' => '🍌', 'category' => 'fruta', 'preferred_store' => 'walmart', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Orange', 'emoji' => '🍊', 'category' => 'fruta', 'preferred_store' => 'target', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Pear', 'emoji' => '🍐', 'category' => 'fruta', 'preferred_store' => 'whole_foods', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Lemon', 'emoji' => '🍋', 'category' => 'fruta', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 3],
            ['name' => 'Lime', 'emoji' => '🍋', 'category' => 'fruta', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 3],
            ['name' => 'Grapes', 'emoji' => '🍇', 'category' => 'fruta', 'preferred_store' => 'aldi', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Strawberries', 'emoji' => '🍓', 'category' => 'fruta', 'preferred_store' => 'trader_joes', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Watermelon', 'emoji' => '🍉', 'category' => 'fruta', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Avocado', 'emoji' => '🥑', 'category' => 'fruta', 'preferred_store' => 'trader_joes', 'default_unit' => 'un', 'default_quantity' => 2],

            // Vegetables
            ['name' => 'Tomato', 'emoji' => '🍅', 'category' => 'legumes', 'preferred_store' => 'whole_foods', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Potato', 'emoji' => '🥔', 'category' => 'legumes', 'preferred_store' => 'walmart', 'default_unit' => 'kg', 'default_quantity' => 2],
            ['name' => 'Onion', 'emoji' => '🧅', 'category' => 'legumes', 'preferred_store' => null, 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Garlic', 'emoji' => '🧄', 'category' => 'legumes', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Carrot', 'emoji' => '🥕', 'category' => 'legumes', 'preferred_store' => 'aldi', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Lettuce', 'emoji' => '🥬', 'category' => 'legumes', 'preferred_store' => 'target', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Broccoli', 'emoji' => '🥦', 'category' => 'legumes', 'preferred_store' => 'whole_foods', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Cucumber', 'emoji' => '🥒', 'category' => 'legumes', 'preferred_store' => 'target', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Bell pepper', 'emoji' => '🫑', 'category' => 'legumes', 'preferred_store' => 'target', 'default_unit' => 'un', 'default_quantity' => 2],
            ['name' => 'Mushrooms', 'emoji' => '🍄', 'category' => 'legumes', 'preferred_store' => 'whole_foods', 'default_unit' => 'g', 'default_quantity' => 250],
            ['name' => 'Spinach', 'emoji' => '🌿', 'category' => 'legumes', 'preferred_store' => 'aldi', 'default_unit' => 'g', 'default_quantity' => 200],

            // Dairy & eggs
            ['name' => 'Milk', 'emoji' => '🥛', 'category' => 'lacticinios', 'preferred_store' => 'walmart', 'default_unit' => 'l', 'default_quantity' => 1],
            ['name' => 'Butter', 'emoji' => '🧈', 'category' => 'lacticinios', 'preferred_store' => 'target', 'default_unit' => 'g', 'default_quantity' => 250],
            ['name' => 'Yogurt', 'emoji' => '🥛', 'category' => 'lacticinios', 'preferred_store' => 'trader_joes', 'default_unit' => 'un', 'default_quantity' => 4],
            ['name' => 'Cheddar cheese', 'emoji' => '🧀', 'category' => 'lacticinios', 'preferred_store' => 'whole_foods', 'default_unit' => 'g', 'default_quantity' => 200],
            ['name' => 'Heavy cream', 'emoji' => '🥛', 'category' => 'lacticinios', 'preferred_store' => 'walmart', 'default_unit' => 'ml', 'default_quantity' => 200],
            ['name' => 'Eggs', 'emoji' => '🥚', 'category' => 'lacticinios', 'preferred_store' => 'aldi', 'default_unit' => 'dz', 'default_quantity' => 1],
            ['name' => 'Parmesan', 'emoji' => '🧀', 'category' => 'lacticinios', 'preferred_store' => 'whole_foods', 'default_unit' => 'g', 'default_quantity' => 200],

            // Meat
            ['name' => 'Whole chicken', 'emoji' => '🐔', 'category' => 'carne', 'preferred_store' => 'whole_foods', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Chicken breast', 'emoji' => '🐔', 'category' => 'carne', 'preferred_store' => 'target', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Pork chops', 'emoji' => '🥩', 'category' => 'carne', 'preferred_store' => 'aldi', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Ground beef', 'emoji' => '🥩', 'category' => 'carne', 'preferred_store' => 'walmart', 'default_unit' => 'g', 'default_quantity' => 500],
            ['name' => 'Sausage', 'emoji' => '🌭', 'category' => 'carne', 'preferred_store' => 'whole_foods', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Bacon', 'emoji' => '🥓', 'category' => 'carne', 'preferred_store' => null, 'default_unit' => 'g', 'default_quantity' => 200],
            ['name' => 'Ham', 'emoji' => '🥓', 'category' => 'carne', 'preferred_store' => 'target', 'default_unit' => 'g', 'default_quantity' => 200],
            ['name' => 'Pork ribs', 'emoji' => '🥩', 'category' => 'carne', 'preferred_store' => 'whole_foods', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Turkey slices', 'emoji' => '🦃', 'category' => 'carne', 'preferred_store' => 'target', 'default_unit' => 'g', 'default_quantity' => 200],

            // Fish & seafood
            ['name' => 'Cod', 'emoji' => '🐟', 'category' => 'peixe', 'preferred_store' => 'whole_foods', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Salmon', 'emoji' => '🐟', 'category' => 'peixe', 'preferred_store' => 'whole_foods', 'default_unit' => 'g', 'default_quantity' => 400],
            ['name' => 'Canned tuna', 'emoji' => '🐟', 'category' => 'peixe', 'preferred_store' => 'target', 'default_unit' => 'can', 'default_quantity' => 3],
            ['name' => 'Shrimp', 'emoji' => '🦐', 'category' => 'peixe', 'preferred_store' => 'whole_foods', 'default_unit' => 'g', 'default_quantity' => 300],
            ['name' => 'Sardines', 'emoji' => '🐟', 'category' => 'peixe', 'preferred_store' => 'trader_joes', 'default_unit' => 'can', 'default_quantity' => 2],

            // Bakery
            ['name' => 'Bread loaf', 'emoji' => '🍞', 'category' => 'padaria', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Sandwich bread', 'emoji' => '🍞', 'category' => 'padaria', 'preferred_store' => 'walmart', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Baguette', 'emoji' => '🥖', 'category' => 'padaria', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 2],
            ['name' => 'Bagels', 'emoji' => '🥯', 'category' => 'padaria', 'preferred_store' => 'trader_joes', 'default_unit' => 'un', 'default_quantity' => 6],
            ['name' => 'Croissant', 'emoji' => '🥐', 'category' => 'padaria', 'preferred_store' => 'aldi', 'default_unit' => 'un', 'default_quantity' => 4],
            ['name' => 'Crackers', 'emoji' => '🍘', 'category' => 'padaria', 'preferred_store' => 'target', 'default_unit' => 'pack', 'default_quantity' => 1],
            ['name' => 'Tortillas', 'emoji' => '🫓', 'category' => 'padaria', 'preferred_store' => 'walmart', 'default_unit' => 'pack', 'default_quantity' => 1],

            // Beverages
            ['name' => 'Bottled water', 'emoji' => '💧', 'category' => 'bebidas', 'preferred_store' => 'target', 'default_unit' => 'l', 'default_quantity' => 6],
            ['name' => 'Orange juice', 'emoji' => '🍊', 'category' => 'bebidas', 'preferred_store' => 'walmart', 'default_unit' => 'l', 'default_quantity' => 1],
            ['name' => 'Beer', 'emoji' => '🍺', 'category' => 'bebidas', 'preferred_store' => 'aldi', 'default_unit' => 'un', 'default_quantity' => 6],
            ['name' => 'Red wine', 'emoji' => '🍷', 'category' => 'bebidas', 'preferred_store' => 'trader_joes', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'White wine', 'emoji' => '🍾', 'category' => 'bebidas', 'preferred_store' => 'trader_joes', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Soda', 'emoji' => '🥤', 'category' => 'bebidas', 'preferred_store' => 'walmart', 'default_unit' => 'l', 'default_quantity' => 1.5],
            ['name' => 'Coffee', 'emoji' => '☕', 'category' => 'bebidas', 'preferred_store' => 'whole_foods', 'default_unit' => 'g', 'default_quantity' => 250],

            // Pantry
            ['name' => 'Olive oil', 'emoji' => '🫙', 'category' => 'despensa', 'preferred_store' => 'whole_foods', 'default_unit' => 'ml', 'default_quantity' => 750],
            ['name' => 'Rice', 'emoji' => '🍚', 'category' => 'despensa', 'preferred_store' => 'walmart', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Pasta', 'emoji' => '🍝', 'category' => 'despensa', 'preferred_store' => 'aldi', 'default_unit' => 'g', 'default_quantity' => 500],
            ['name' => 'Spaghetti', 'emoji' => '🍝', 'category' => 'despensa', 'preferred_store' => 'aldi', 'default_unit' => 'g', 'default_quantity' => 500],
            ['name' => 'Tomato sauce', 'emoji' => '🍅', 'category' => 'despensa', 'preferred_store' => 'walmart', 'default_unit' => 'ml', 'default_quantity' => 400],
            ['name' => 'Chocolate', 'emoji' => '🍫', 'category' => 'despensa', 'preferred_store' => 'aldi', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Cookies', 'emoji' => '🍪', 'category' => 'despensa', 'preferred_store' => 'target', 'default_unit' => 'pack', 'default_quantity' => 1],
            ['name' => 'Black beans', 'emoji' => '🫘', 'category' => 'despensa', 'preferred_store' => 'walmart', 'default_unit' => 'can', 'default_quantity' => 2],
            ['name' => 'Chickpeas', 'emoji' => '🫘', 'category' => 'despensa', 'preferred_store' => 'walmart', 'default_unit' => 'can', 'default_quantity' => 2],
            ['name' => 'Salt', 'emoji' => '🧂', 'category' => 'despensa', 'preferred_store' => null, 'default_unit' => 'g', 'default_quantity' => 500],
            ['name' => 'Sugar', 'emoji' => '🍬', 'category' => 'despensa', 'preferred_store' => null, 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Flour', 'emoji' => '🌾', 'category' => 'despensa', 'preferred_store' => 'walmart', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Tomato paste', 'emoji' => '🍅', 'category' => 'despensa', 'preferred_store' => 'walmart', 'default_unit' => 'ml', 'default_quantity' => 400],
            ['name' => 'Vinegar', 'emoji' => '🍾', 'category' => 'despensa', 'preferred_store' => null, 'default_unit' => 'ml', 'default_quantity' => 250],
            ['name' => 'Mayonnaise', 'emoji' => '🫙', 'category' => 'despensa', 'preferred_store' => 'walmart', 'default_unit' => 'g', 'default_quantity' => 250],

            // Cleaning
            ['name' => 'Laundry detergent', 'emoji' => '🧺', 'category' => 'limpeza', 'preferred_store' => 'walmart', 'default_unit' => 'l', 'default_quantity' => 2],
            ['name' => 'Dish soap', 'emoji' => '🧼', 'category' => 'limpeza', 'preferred_store' => 'target', 'default_unit' => 'ml', 'default_quantity' => 500],
            ['name' => 'Fabric softener', 'emoji' => '🧴', 'category' => 'limpeza', 'preferred_store' => 'walmart', 'default_unit' => 'l', 'default_quantity' => 1],
            ['name' => 'Toilet paper', 'emoji' => '🧻', 'category' => 'limpeza', 'preferred_store' => 'target', 'default_unit' => 'un', 'default_quantity' => 12],
            ['name' => 'Sponges', 'emoji' => '🧽', 'category' => 'limpeza', 'preferred_store' => 'walmart', 'default_unit' => 'un', 'default_quantity' => 4],
            ['name' => 'Trash bags', 'emoji' => '🗑️', 'category' => 'limpeza', 'preferred_store' => 'target', 'default_unit' => 'roll', 'default_quantity' => 1],
            ['name' => 'All-purpose cleaner', 'emoji' => '🧹', 'category' => 'limpeza', 'preferred_store' => 'target', 'default_unit' => 'un', 'default_quantity' => 1],

            // Personal care
            ['name' => 'Shampoo', 'emoji' => '🧴', 'category' => 'higiene', 'preferred_store' => 'target', 'default_unit' => 'ml', 'default_quantity' => 400],
            ['name' => 'Body wash', 'emoji' => '🧴', 'category' => 'higiene', 'preferred_store' => 'target', 'default_unit' => 'ml', 'default_quantity' => 400],
            ['name' => 'Toothpaste', 'emoji' => '🪥', 'category' => 'higiene', 'preferred_store' => 'walmart', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Toothbrush', 'emoji' => '🪥', 'category' => 'higiene', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 2],
            ['name' => 'Deodorant', 'emoji' => '🧴', 'category' => 'higiene', 'preferred_store' => 'target', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Moisturizer', 'emoji' => '🧴', 'category' => 'higiene', 'preferred_store' => 'whole_foods', 'default_unit' => 'ml', 'default_quantity' => 200],
            ['name' => 'Pads', 'emoji' => '🩸', 'category' => 'higiene', 'preferred_store' => 'target', 'default_unit' => 'pack', 'default_quantity' => 1],
        ];

        foreach ($items as $item) {
            CatalogItem::firstOrCreate(['name' => $item['name']], $item);
        }
    }
}
