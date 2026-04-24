<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\StorePt;

class ShoppingHistorySeeder extends BaseShoppingHistorySeeder
{
    protected function trips(): array
    {
        return [
            [StorePt::Continente, 2],
            [StorePt::Lidl, 5],
            [StorePt::Mercadona, 9],
            [StorePt::Aldi, 13],
            [StorePt::Continente, 17],
            [StorePt::Mercadona, 23],
            [StorePt::Lidl, 28],
            [StorePt::Continente, 35],
            [StorePt::Mercadona, 42],
            [StorePt::Aldi, 50],
            [StorePt::Continente, 58],
        ];
    }

    protected function manualItems(): array
    {
        return [
            ['name' => 'Lâminas de barbear', 'emoji' => '🪒', 'category' => 'higiene', 'unit' => 'un', 'quantity' => 1],
            ['name' => 'Pilhas AA', 'emoji' => '🔋', 'category' => 'casa', 'unit' => 'pacote', 'quantity' => 1],
            ['name' => 'Cartão de aniversário', 'emoji' => '🎂', 'category' => 'casa', 'unit' => 'un', 'quantity' => 1],
            ['name' => 'Flores', 'emoji' => '💐', 'category' => 'casa', 'unit' => 'un', 'quantity' => 1],
            ['name' => 'Gelo', 'emoji' => '🧊', 'category' => 'bebidas', 'unit' => 'kg', 'quantity' => 2],
        ];
    }
}
