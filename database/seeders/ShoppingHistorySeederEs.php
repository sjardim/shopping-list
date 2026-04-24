<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\StoreEs;

class ShoppingHistorySeederEs extends BaseShoppingHistorySeeder
{
    protected function trips(): array
    {
        return [
            [StoreEs::Mercadona, 2],
            [StoreEs::Carrefour, 5],
            [StoreEs::Dia, 9],
            [StoreEs::Mercadona, 13],
            [StoreEs::Eroski, 17],
            [StoreEs::Alcampo, 23],
            [StoreEs::Mercadona, 28],
            [StoreEs::Hipercor, 35],
            [StoreEs::Carrefour, 42],
            [StoreEs::Mercadona, 50],
            [StoreEs::Dia, 58],
        ];
    }

    protected function manualItems(): array
    {
        return [
            ['name' => 'Cuchillas de afeitar', 'emoji' => '🪒', 'category' => 'higiene', 'unit' => 'un', 'quantity' => 1],
            ['name' => 'Pilas AA', 'emoji' => '🔋', 'category' => 'casa', 'unit' => 'pacote', 'quantity' => 1],
            ['name' => 'Tarjeta de cumpleaños', 'emoji' => '🎂', 'category' => 'casa', 'unit' => 'un', 'quantity' => 1],
            ['name' => 'Flores', 'emoji' => '💐', 'category' => 'casa', 'unit' => 'un', 'quantity' => 1],
            ['name' => 'Bolsa de hielo', 'emoji' => '🧊', 'category' => 'bebidas', 'unit' => 'kg', 'quantity' => 2],
        ];
    }
}
