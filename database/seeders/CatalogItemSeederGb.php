<?php

namespace Database\Seeders;

use App\Models\CatalogItem;
use Illuminate\Database\Seeder;

/**
 * British English catalog using common UK grocery items and store hints
 * (Tesco, Sainsbury's, Asda, Morrisons, Waitrose, plus Lidl and Aldi which
 * also operate in the UK).
 *
 * Run with: php artisan db:seed --class=CatalogItemSeederGb
 *
 * Pairs naturally with the English meal bundles (MealBundles serves the
 * English bundles for any locale starting with "en", including en_GB).
 */
class CatalogItemSeederGb extends Seeder
{
    public function run(): void
    {
        $items = [
            // Fruit
            ['name' => 'Apple', 'emoji' => '🍎', 'category' => 'fruta', 'preferred_store' => 'tesco', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Banana', 'emoji' => '🍌', 'category' => 'fruta', 'preferred_store' => 'aldi', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Orange', 'emoji' => '🍊', 'category' => 'fruta', 'preferred_store' => 'sainsburys', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Pear', 'emoji' => '🍐', 'category' => 'fruta', 'preferred_store' => 'waitrose', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Lemon', 'emoji' => '🍋', 'category' => 'fruta', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 3],
            ['name' => 'Lime', 'emoji' => '🍋', 'category' => 'fruta', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 3],
            ['name' => 'Grapes', 'emoji' => '🍇', 'category' => 'fruta', 'preferred_store' => 'lidl', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Strawberries', 'emoji' => '🍓', 'category' => 'fruta', 'preferred_store' => 'sainsburys', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Watermelon', 'emoji' => '🍉', 'category' => 'fruta', 'preferred_store' => 'asda', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Avocado', 'emoji' => '🥑', 'category' => 'fruta', 'preferred_store' => 'waitrose', 'default_unit' => 'un', 'default_quantity' => 2],

            // Vegetables
            ['name' => 'Tomato', 'emoji' => '🍅', 'category' => 'legumes', 'preferred_store' => 'tesco', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Potato', 'emoji' => '🥔', 'category' => 'legumes', 'preferred_store' => 'asda', 'default_unit' => 'kg', 'default_quantity' => 2],
            ['name' => 'Onion', 'emoji' => '🧅', 'category' => 'legumes', 'preferred_store' => null, 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Garlic', 'emoji' => '🧄', 'category' => 'legumes', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Carrot', 'emoji' => '🥕', 'category' => 'legumes', 'preferred_store' => 'aldi', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Lettuce', 'emoji' => '🥬', 'category' => 'legumes', 'preferred_store' => 'morrisons', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Broccoli', 'emoji' => '🥦', 'category' => 'legumes', 'preferred_store' => 'waitrose', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Cucumber', 'emoji' => '🥒', 'category' => 'legumes', 'preferred_store' => 'sainsburys', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Pepper', 'emoji' => '🫑', 'category' => 'legumes', 'preferred_store' => 'sainsburys', 'default_unit' => 'un', 'default_quantity' => 2],
            ['name' => 'Mushrooms', 'emoji' => '🍄', 'category' => 'legumes', 'preferred_store' => 'morrisons', 'default_unit' => 'g', 'default_quantity' => 250],
            ['name' => 'Spinach', 'emoji' => '🌿', 'category' => 'legumes', 'preferred_store' => 'aldi', 'default_unit' => 'g', 'default_quantity' => 200],
            ['name' => 'Courgette', 'emoji' => '🥒', 'category' => 'legumes', 'preferred_store' => 'tesco', 'default_unit' => 'un', 'default_quantity' => 2],
            ['name' => 'Aubergine', 'emoji' => '🍆', 'category' => 'legumes', 'preferred_store' => 'sainsburys', 'default_unit' => 'un', 'default_quantity' => 1],

            // Dairy & Eggs
            ['name' => 'Whole milk', 'emoji' => '🥛', 'category' => 'lacticinios', 'preferred_store' => 'tesco', 'default_unit' => 'l', 'default_quantity' => 2],
            ['name' => 'Butter', 'emoji' => '🧈', 'category' => 'lacticinios', 'preferred_store' => 'sainsburys', 'default_unit' => 'g', 'default_quantity' => 250],
            ['name' => 'Yoghurt', 'emoji' => '🥛', 'category' => 'lacticinios', 'preferred_store' => 'morrisons', 'default_unit' => 'un', 'default_quantity' => 4],
            ['name' => 'Cheddar', 'emoji' => '🧀', 'category' => 'lacticinios', 'preferred_store' => 'waitrose', 'default_unit' => 'g', 'default_quantity' => 250],
            ['name' => 'Double cream', 'emoji' => '🥛', 'category' => 'lacticinios', 'preferred_store' => 'tesco', 'default_unit' => 'ml', 'default_quantity' => 300],
            ['name' => 'Eggs', 'emoji' => '🥚', 'category' => 'lacticinios', 'preferred_store' => 'aldi', 'default_unit' => 'dz', 'default_quantity' => 1],
            ['name' => 'Brie', 'emoji' => '🧀', 'category' => 'lacticinios', 'preferred_store' => 'waitrose', 'default_unit' => 'g', 'default_quantity' => 200],

            // Meat
            ['name' => 'Whole chicken', 'emoji' => '🐔', 'category' => 'carne', 'preferred_store' => 'morrisons', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Chicken breast', 'emoji' => '🐔', 'category' => 'carne', 'preferred_store' => 'sainsburys', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Pork chops', 'emoji' => '🥩', 'category' => 'carne', 'preferred_store' => 'aldi', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Beef mince', 'emoji' => '🥩', 'category' => 'carne', 'preferred_store' => 'tesco', 'default_unit' => 'g', 'default_quantity' => 500],
            ['name' => 'Sausages', 'emoji' => '🌭', 'category' => 'carne', 'preferred_store' => 'morrisons', 'default_unit' => 'pack', 'default_quantity' => 1],
            ['name' => 'Bacon', 'emoji' => '🥓', 'category' => 'carne', 'preferred_store' => null, 'default_unit' => 'g', 'default_quantity' => 250],
            ['name' => 'Ham', 'emoji' => '🥓', 'category' => 'carne', 'preferred_store' => 'sainsburys', 'default_unit' => 'g', 'default_quantity' => 200],
            ['name' => 'Pork ribs', 'emoji' => '🥩', 'category' => 'carne', 'preferred_store' => 'morrisons', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Turkey slices', 'emoji' => '🦃', 'category' => 'carne', 'preferred_store' => 'sainsburys', 'default_unit' => 'g', 'default_quantity' => 200],

            // Fish & Seafood
            ['name' => 'Cod', 'emoji' => '🐟', 'category' => 'peixe', 'preferred_store' => 'waitrose', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Salmon', 'emoji' => '🐟', 'category' => 'peixe', 'preferred_store' => 'waitrose', 'default_unit' => 'g', 'default_quantity' => 400],
            ['name' => 'Tinned tuna', 'emoji' => '🐟', 'category' => 'peixe', 'preferred_store' => 'tesco', 'default_unit' => 'can', 'default_quantity' => 3],
            ['name' => 'Prawns', 'emoji' => '🦐', 'category' => 'peixe', 'preferred_store' => 'waitrose', 'default_unit' => 'g', 'default_quantity' => 300],
            ['name' => 'Sardines', 'emoji' => '🐟', 'category' => 'peixe', 'preferred_store' => 'tesco', 'default_unit' => 'can', 'default_quantity' => 2],

            // Bakery
            ['name' => 'Loaf of bread', 'emoji' => '🍞', 'category' => 'padaria', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Sandwich loaf', 'emoji' => '🍞', 'category' => 'padaria', 'preferred_store' => 'tesco', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Baguette', 'emoji' => '🥖', 'category' => 'padaria', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Crumpets', 'emoji' => '🥯', 'category' => 'padaria', 'preferred_store' => 'tesco', 'default_unit' => 'pack', 'default_quantity' => 1],
            ['name' => 'Croissant', 'emoji' => '🥐', 'category' => 'padaria', 'preferred_store' => 'waitrose', 'default_unit' => 'un', 'default_quantity' => 4],
            ['name' => 'Crackers', 'emoji' => '🍘', 'category' => 'padaria', 'preferred_store' => 'sainsburys', 'default_unit' => 'pack', 'default_quantity' => 1],
            ['name' => 'Pitta bread', 'emoji' => '🫓', 'category' => 'padaria', 'preferred_store' => 'morrisons', 'default_unit' => 'pack', 'default_quantity' => 1],

            // Beverages
            ['name' => 'Bottled water', 'emoji' => '💧', 'category' => 'bebidas', 'preferred_store' => 'asda', 'default_unit' => 'l', 'default_quantity' => 6],
            ['name' => 'Orange juice', 'emoji' => '🍊', 'category' => 'bebidas', 'preferred_store' => 'tesco', 'default_unit' => 'l', 'default_quantity' => 1],
            ['name' => 'Beer', 'emoji' => '🍺', 'category' => 'bebidas', 'preferred_store' => 'asda', 'default_unit' => 'un', 'default_quantity' => 6],
            ['name' => 'Red wine', 'emoji' => '🍷', 'category' => 'bebidas', 'preferred_store' => 'waitrose', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'White wine', 'emoji' => '🍾', 'category' => 'bebidas', 'preferred_store' => 'waitrose', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Squash', 'emoji' => '🥤', 'category' => 'bebidas', 'preferred_store' => 'asda', 'default_unit' => 'l', 'default_quantity' => 1],
            ['name' => 'Tea bags', 'emoji' => '🫖', 'category' => 'bebidas', 'preferred_store' => 'tesco', 'default_unit' => 'pack', 'default_quantity' => 1],
            ['name' => 'Coffee', 'emoji' => '☕', 'category' => 'bebidas', 'preferred_store' => 'waitrose', 'default_unit' => 'g', 'default_quantity' => 250],

            // Pantry
            ['name' => 'Olive oil', 'emoji' => '🫙', 'category' => 'despensa', 'preferred_store' => 'waitrose', 'default_unit' => 'ml', 'default_quantity' => 750],
            ['name' => 'Rice', 'emoji' => '🍚', 'category' => 'despensa', 'preferred_store' => 'asda', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Pasta', 'emoji' => '🍝', 'category' => 'despensa', 'preferred_store' => 'aldi', 'default_unit' => 'g', 'default_quantity' => 500],
            ['name' => 'Spaghetti', 'emoji' => '🍝', 'category' => 'despensa', 'preferred_store' => 'aldi', 'default_unit' => 'g', 'default_quantity' => 500],
            ['name' => 'Tomato sauce', 'emoji' => '🍅', 'category' => 'despensa', 'preferred_store' => 'tesco', 'default_unit' => 'ml', 'default_quantity' => 400],
            ['name' => 'Chocolate', 'emoji' => '🍫', 'category' => 'despensa', 'preferred_store' => 'lidl', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Biscuits', 'emoji' => '🍪', 'category' => 'despensa', 'preferred_store' => 'sainsburys', 'default_unit' => 'pack', 'default_quantity' => 1],
            ['name' => 'Baked beans', 'emoji' => '🫘', 'category' => 'despensa', 'preferred_store' => 'asda', 'default_unit' => 'can', 'default_quantity' => 4],
            ['name' => 'Chickpeas', 'emoji' => '🫘', 'category' => 'despensa', 'preferred_store' => 'asda', 'default_unit' => 'can', 'default_quantity' => 2],
            ['name' => 'Salt', 'emoji' => '🧂', 'category' => 'despensa', 'preferred_store' => null, 'default_unit' => 'g', 'default_quantity' => 500],
            ['name' => 'Sugar', 'emoji' => '🍬', 'category' => 'despensa', 'preferred_store' => null, 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Plain flour', 'emoji' => '🌾', 'category' => 'despensa', 'preferred_store' => 'tesco', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Tomato paste', 'emoji' => '🍅', 'category' => 'despensa', 'preferred_store' => 'tesco', 'default_unit' => 'ml', 'default_quantity' => 400],
            ['name' => 'Vinegar', 'emoji' => '🍾', 'category' => 'despensa', 'preferred_store' => null, 'default_unit' => 'ml', 'default_quantity' => 250],
            ['name' => 'Mayonnaise', 'emoji' => '🫙', 'category' => 'despensa', 'preferred_store' => 'tesco', 'default_unit' => 'g', 'default_quantity' => 250],

            // Cleaning
            ['name' => 'Washing powder', 'emoji' => '🧺', 'category' => 'limpeza', 'preferred_store' => 'asda', 'default_unit' => 'kg', 'default_quantity' => 2],
            ['name' => 'Washing-up liquid', 'emoji' => '🧼', 'category' => 'limpeza', 'preferred_store' => 'sainsburys', 'default_unit' => 'ml', 'default_quantity' => 500],
            ['name' => 'Fabric conditioner', 'emoji' => '🧴', 'category' => 'limpeza', 'preferred_store' => 'asda', 'default_unit' => 'l', 'default_quantity' => 1],
            ['name' => 'Toilet roll', 'emoji' => '🧻', 'category' => 'limpeza', 'preferred_store' => 'lidl', 'default_unit' => 'un', 'default_quantity' => 12],
            ['name' => 'Sponges', 'emoji' => '🧽', 'category' => 'limpeza', 'preferred_store' => 'asda', 'default_unit' => 'un', 'default_quantity' => 4],
            ['name' => 'Bin bags', 'emoji' => '🗑️', 'category' => 'limpeza', 'preferred_store' => 'sainsburys', 'default_unit' => 'roll', 'default_quantity' => 1],
            ['name' => 'Multi-surface cleaner', 'emoji' => '🧹', 'category' => 'limpeza', 'preferred_store' => 'sainsburys', 'default_unit' => 'un', 'default_quantity' => 1],

            // Personal care
            ['name' => 'Shampoo', 'emoji' => '🧴', 'category' => 'higiene', 'preferred_store' => 'sainsburys', 'default_unit' => 'ml', 'default_quantity' => 400],
            ['name' => 'Shower gel', 'emoji' => '🧴', 'category' => 'higiene', 'preferred_store' => 'sainsburys', 'default_unit' => 'ml', 'default_quantity' => 400],
            ['name' => 'Toothpaste', 'emoji' => '🪥', 'category' => 'higiene', 'preferred_store' => 'tesco', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Toothbrush', 'emoji' => '🪥', 'category' => 'higiene', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 2],
            ['name' => 'Deodorant', 'emoji' => '🧴', 'category' => 'higiene', 'preferred_store' => 'sainsburys', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Moisturiser', 'emoji' => '🧴', 'category' => 'higiene', 'preferred_store' => 'waitrose', 'default_unit' => 'ml', 'default_quantity' => 200],
            ['name' => 'Sanitary pads', 'emoji' => '🩸', 'category' => 'higiene', 'preferred_store' => 'sainsburys', 'default_unit' => 'pack', 'default_quantity' => 1],
        ];

        foreach ($items as $item) {
            CatalogItem::firstOrCreate(['name' => $item['name']], $item);
        }
    }
}
