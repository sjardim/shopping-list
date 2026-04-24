<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\StoreBr;

class ShoppingHistorySeederBr extends BaseShoppingHistorySeeder
{
    protected function trips(): array
    {
        return [
            [StoreBr::Carrefour, 2],
            [StoreBr::PaoDeAcucar, 5],
            [StoreBr::Atacadao, 9],
            [StoreBr::Extra, 13],
            [StoreBr::Carrefour, 17],
            [StoreBr::Assai, 23],
            [StoreBr::PaoDeAcucar, 28],
            [StoreBr::Atacadao, 35],
            [StoreBr::Carrefour, 42],
            [StoreBr::Extra, 50],
            [StoreBr::Atacadao, 58],
        ];
    }

    protected function manualItems(): array
    {
        return [
            ['name' => 'Lâmina de barbear', 'emoji' => '🪒', 'category' => 'higiene', 'unit' => 'un', 'quantity' => 1],
            ['name' => 'Pilha AA', 'emoji' => '🔋', 'category' => 'casa', 'unit' => 'pacote', 'quantity' => 1],
            ['name' => 'Cartão de aniversário', 'emoji' => '🎂', 'category' => 'casa', 'unit' => 'un', 'quantity' => 1],
            ['name' => 'Flores', 'emoji' => '💐', 'category' => 'casa', 'unit' => 'un', 'quantity' => 1],
            ['name' => 'Gelo', 'emoji' => '🧊', 'category' => 'bebidas', 'unit' => 'kg', 'quantity' => 2],
        ];
    }
}
