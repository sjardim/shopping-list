<?php

namespace App\Support;

class MealBundles
{
    /**
     * @return array<string, array{name: string, emoji: string, items: array<int, array{name: string, quantity: float, unit: string}>}>
     */
    public static function all(): array
    {
        return self::bundles();
    }

    /**
     * @return array{name: string, emoji: string, items: array<int, array{name: string, quantity: float, unit: string}>}|null
     */
    public static function get(string $key): ?array
    {
        return self::bundles()[$key] ?? null;
    }

    /**
     * @return array<string, array{name: string, emoji: string, items: array<int, array{name: string, quantity: float, unit: string}>}>
     */
    private static function bundles(): array
    {
        return [
            'frango_assado' => [
                'name' => 'Frango Assado',
                'emoji' => '🍗',
                'items' => [
                    ['name' => 'Frango inteiro', 'quantity' => 1, 'unit' => 'kg'],
                    ['name' => 'Alho', 'quantity' => 4, 'unit' => 'un'],
                    ['name' => 'Limão', 'quantity' => 2, 'unit' => 'un'],
                    ['name' => 'Azeite', 'quantity' => 1, 'unit' => 'ml'],
                    ['name' => 'Batata', 'quantity' => 1, 'unit' => 'kg'],
                    ['name' => 'Cebola', 'quantity' => 2, 'unit' => 'un'],
                    ['name' => 'Sal', 'quantity' => 1, 'unit' => 'un'],
                ],
            ],
            'caldo_verde' => [
                'name' => 'Caldo Verde',
                'emoji' => '🥣',
                'items' => [
                    ['name' => 'Couve', 'quantity' => 0.5, 'unit' => 'kg'],
                    ['name' => 'Batata', 'quantity' => 1, 'unit' => 'kg'],
                    ['name' => 'Chouriço', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Cebola', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Alho', 'quantity' => 2, 'unit' => 'un'],
                    ['name' => 'Azeite', 'quantity' => 1, 'unit' => 'ml'],
                ],
            ],
            'bacalhau_braga' => [
                'name' => 'Bacalhau à Braga',
                'emoji' => '🐟',
                'items' => [
                    ['name' => 'Bacalhau', 'quantity' => 0.5, 'unit' => 'kg'],
                    ['name' => 'Cebola', 'quantity' => 3, 'unit' => 'un'],
                    ['name' => 'Alho', 'quantity' => 4, 'unit' => 'un'],
                    ['name' => 'Batata', 'quantity' => 1, 'unit' => 'kg'],
                    ['name' => 'Ovos', 'quantity' => 6, 'unit' => 'un'],
                    ['name' => 'Azeite', 'quantity' => 1, 'unit' => 'ml'],
                ],
            ],
            'churrasco' => [
                'name' => 'Churrasco',
                'emoji' => '🔥',
                'items' => [
                    ['name' => 'Costeletas de porco', 'quantity' => 1, 'unit' => 'kg'],
                    ['name' => 'Peito de frango', 'quantity' => 0.5, 'unit' => 'kg'],
                    ['name' => 'Chouriço', 'quantity' => 2, 'unit' => 'un'],
                    ['name' => 'Cerveja', 'quantity' => 6, 'unit' => 'un'],
                    ['name' => 'Pão de forma', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Sal grosso', 'quantity' => 1, 'unit' => 'un'],
                ],
            ],
            'massa_bolonhesa' => [
                'name' => 'Esparguete à Bolonhesa',
                'emoji' => '🍝',
                'items' => [
                    ['name' => 'Massa esparguete', 'quantity' => 500, 'unit' => 'g'],
                    ['name' => 'Carne picada', 'quantity' => 500, 'unit' => 'g'],
                    ['name' => 'Polpa de tomate', 'quantity' => 400, 'unit' => 'ml'],
                    ['name' => 'Cebola', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Alho', 'quantity' => 2, 'unit' => 'un'],
                    ['name' => 'Azeite', 'quantity' => 1, 'unit' => 'ml'],
                ],
            ],
            'arroz_frango' => [
                'name' => 'Arroz de Frango',
                'emoji' => '🍚',
                'items' => [
                    ['name' => 'Peito de frango', 'quantity' => 0.5, 'unit' => 'kg'],
                    ['name' => 'Arroz', 'quantity' => 1, 'unit' => 'kg'],
                    ['name' => 'Tomate', 'quantity' => 2, 'unit' => 'un'],
                    ['name' => 'Cebola', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Alho', 'quantity' => 2, 'unit' => 'un'],
                    ['name' => 'Azeite', 'quantity' => 1, 'unit' => 'ml'],
                ],
            ],
            'sopa_legumes' => [
                'name' => 'Sopa de Legumes',
                'emoji' => '🍲',
                'items' => [
                    ['name' => 'Cenoura', 'quantity' => 2, 'unit' => 'un'],
                    ['name' => 'Batata', 'quantity' => 3, 'unit' => 'un'],
                    ['name' => 'Cebola', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Alho', 'quantity' => 2, 'unit' => 'un'],
                    ['name' => 'Azeite', 'quantity' => 1, 'unit' => 'ml'],
                    ['name' => 'Sal', 'quantity' => 1, 'unit' => 'un'],
                ],
            ],
            'feijoada' => [
                'name' => 'Feijoada Portuguesa',
                'emoji' => '🫘',
                'items' => [
                    ['name' => 'Feijão em lata', 'quantity' => 2, 'unit' => 'lata'],
                    ['name' => 'Entrecosto', 'quantity' => 0.5, 'unit' => 'kg'],
                    ['name' => 'Chouriço', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Alheira', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Cebola', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Alho', 'quantity' => 2, 'unit' => 'un'],
                ],
            ],
            'ovos_mexidos' => [
                'name' => 'Ovos Mexidos com Presunto',
                'emoji' => '🍳',
                'items' => [
                    ['name' => 'Ovos', 'quantity' => 6, 'unit' => 'un'],
                    ['name' => 'Presunto', 'quantity' => 200, 'unit' => 'g'],
                    ['name' => 'Manteiga', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Pão de forma', 'quantity' => 1, 'unit' => 'un'],
                ],
            ],
            'salada_mista' => [
                'name' => 'Salada Mista',
                'emoji' => '🥗',
                'items' => [
                    ['name' => 'Alface', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Tomate', 'quantity' => 3, 'unit' => 'un'],
                    ['name' => 'Pepino', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Cebola', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Atum em lata', 'quantity' => 2, 'unit' => 'lata'],
                    ['name' => 'Azeite', 'quantity' => 1, 'unit' => 'ml'],
                    ['name' => 'Vinagre', 'quantity' => 1, 'unit' => 'ml'],
                ],
            ],
        ];
    }
}
