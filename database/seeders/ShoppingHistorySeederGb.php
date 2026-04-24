<?php

namespace Database\Seeders;

use App\Enums\StoreUk;

class ShoppingHistorySeederGb extends BaseShoppingHistorySeeder
{
    protected function trips(): array
    {
        return [
            [StoreUk::Tesco, 2],
            [StoreUk::Sainsburys, 5],
            [StoreUk::Lidl, 9],
            [StoreUk::Asda, 13],
            [StoreUk::Tesco, 17],
            [StoreUk::Waitrose, 23],
            [StoreUk::Morrisons, 28],
            [StoreUk::Tesco, 35],
            [StoreUk::Aldi, 42],
            [StoreUk::Sainsburys, 50],
            [StoreUk::Asda, 58],
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
