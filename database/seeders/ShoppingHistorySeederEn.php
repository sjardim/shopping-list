<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\StoreUs;

class ShoppingHistorySeederEn extends BaseShoppingHistorySeeder
{
    protected function trips(): array
    {
        return [
            [StoreUs::Walmart, 2],
            [StoreUs::Target, 5],
            [StoreUs::TraderJoes, 9],
            [StoreUs::WholeFoods, 13],
            [StoreUs::Walmart, 17],
            [StoreUs::TraderJoes, 23],
            [StoreUs::Target, 28],
            [StoreUs::Walmart, 35],
            [StoreUs::TraderJoes, 42],
            [StoreUs::WholeFoods, 50],
            [StoreUs::Walmart, 58],
        ];
    }

    protected function manualItems(): array
    {
        return [
            ['name' => 'Razor blades', 'emoji' => '🪒', 'category' => 'higiene', 'unit' => 'un', 'quantity' => 1],
            ['name' => 'AA batteries', 'emoji' => '🔋', 'category' => 'casa', 'unit' => 'pacote', 'quantity' => 1],
            ['name' => 'Birthday card', 'emoji' => '🎂', 'category' => 'casa', 'unit' => 'un', 'quantity' => 1],
            ['name' => 'Flowers', 'emoji' => '💐', 'category' => 'casa', 'unit' => 'un', 'quantity' => 1],
            ['name' => 'Bag of ice', 'emoji' => '🧊', 'category' => 'bebidas', 'unit' => 'kg', 'quantity' => 2],
        ];
    }
}
