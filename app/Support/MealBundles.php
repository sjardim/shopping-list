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
        return match (app()->getLocale()) {
            'en' => self::englishBundles(),
            'pt_BR' => self::brazilianBundles(),
            default => self::portugueseBundles(),
        };
    }

    /**
     * @return array<string, array{name: string, emoji: string, items: array<int, array{name: string, quantity: float, unit: string}>}>
     */
    private static function portugueseBundles(): array
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

    /**
     * Item names match those in CatalogItemSeederEn so bundle merges link
     * to catalog items (preserving emoji, category, preferred-store hints).
     *
     * @return array<string, array{name: string, emoji: string, items: array<int, array{name: string, quantity: float, unit: string}>}>
     */
    private static function englishBundles(): array
    {
        return [
            'frango_assado' => [
                'name' => 'Roast Chicken',
                'emoji' => '🍗',
                'items' => [
                    ['name' => 'Whole chicken', 'quantity' => 1, 'unit' => 'kg'],
                    ['name' => 'Garlic', 'quantity' => 4, 'unit' => 'un'],
                    ['name' => 'Lemon', 'quantity' => 2, 'unit' => 'un'],
                    ['name' => 'Olive oil', 'quantity' => 1, 'unit' => 'ml'],
                    ['name' => 'Potato', 'quantity' => 1, 'unit' => 'kg'],
                    ['name' => 'Onion', 'quantity' => 2, 'unit' => 'un'],
                    ['name' => 'Salt', 'quantity' => 1, 'unit' => 'un'],
                ],
            ],
            'caldo_verde' => [
                'name' => 'Potato Soup',
                'emoji' => '🥣',
                'items' => [
                    ['name' => 'Potato', 'quantity' => 1, 'unit' => 'kg'],
                    ['name' => 'Sausage', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Onion', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Garlic', 'quantity' => 2, 'unit' => 'un'],
                    ['name' => 'Olive oil', 'quantity' => 1, 'unit' => 'ml'],
                ],
            ],
            'bacalhau_braga' => [
                'name' => 'Baked Cod',
                'emoji' => '🐟',
                'items' => [
                    ['name' => 'Cod', 'quantity' => 0.5, 'unit' => 'kg'],
                    ['name' => 'Onion', 'quantity' => 3, 'unit' => 'un'],
                    ['name' => 'Garlic', 'quantity' => 4, 'unit' => 'un'],
                    ['name' => 'Potato', 'quantity' => 1, 'unit' => 'kg'],
                    ['name' => 'Eggs', 'quantity' => 6, 'unit' => 'un'],
                    ['name' => 'Olive oil', 'quantity' => 1, 'unit' => 'ml'],
                ],
            ],
            'churrasco' => [
                'name' => 'Backyard BBQ',
                'emoji' => '🔥',
                'items' => [
                    ['name' => 'Pork chops', 'quantity' => 1, 'unit' => 'kg'],
                    ['name' => 'Chicken breast', 'quantity' => 0.5, 'unit' => 'kg'],
                    ['name' => 'Sausage', 'quantity' => 2, 'unit' => 'un'],
                    ['name' => 'Beer', 'quantity' => 6, 'unit' => 'un'],
                    ['name' => 'Sandwich bread', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Salt', 'quantity' => 1, 'unit' => 'un'],
                ],
            ],
            'massa_bolonhesa' => [
                'name' => 'Spaghetti Bolognese',
                'emoji' => '🍝',
                'items' => [
                    ['name' => 'Spaghetti', 'quantity' => 500, 'unit' => 'g'],
                    ['name' => 'Ground beef', 'quantity' => 500, 'unit' => 'g'],
                    ['name' => 'Tomato paste', 'quantity' => 400, 'unit' => 'ml'],
                    ['name' => 'Onion', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Garlic', 'quantity' => 2, 'unit' => 'un'],
                    ['name' => 'Olive oil', 'quantity' => 1, 'unit' => 'ml'],
                ],
            ],
            'arroz_frango' => [
                'name' => 'Chicken & Rice',
                'emoji' => '🍚',
                'items' => [
                    ['name' => 'Chicken breast', 'quantity' => 0.5, 'unit' => 'kg'],
                    ['name' => 'Rice', 'quantity' => 1, 'unit' => 'kg'],
                    ['name' => 'Tomato', 'quantity' => 2, 'unit' => 'un'],
                    ['name' => 'Onion', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Garlic', 'quantity' => 2, 'unit' => 'un'],
                    ['name' => 'Olive oil', 'quantity' => 1, 'unit' => 'ml'],
                ],
            ],
            'sopa_legumes' => [
                'name' => 'Vegetable Soup',
                'emoji' => '🍲',
                'items' => [
                    ['name' => 'Carrot', 'quantity' => 2, 'unit' => 'un'],
                    ['name' => 'Potato', 'quantity' => 3, 'unit' => 'un'],
                    ['name' => 'Onion', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Garlic', 'quantity' => 2, 'unit' => 'un'],
                    ['name' => 'Olive oil', 'quantity' => 1, 'unit' => 'ml'],
                    ['name' => 'Salt', 'quantity' => 1, 'unit' => 'un'],
                ],
            ],
            'feijoada' => [
                'name' => 'Bean Stew',
                'emoji' => '🫘',
                'items' => [
                    ['name' => 'Black beans', 'quantity' => 2, 'unit' => 'lata'],
                    ['name' => 'Pork ribs', 'quantity' => 0.5, 'unit' => 'kg'],
                    ['name' => 'Sausage', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Onion', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Garlic', 'quantity' => 2, 'unit' => 'un'],
                ],
            ],
            'ovos_mexidos' => [
                'name' => 'Scrambled Eggs with Ham',
                'emoji' => '🍳',
                'items' => [
                    ['name' => 'Eggs', 'quantity' => 6, 'unit' => 'un'],
                    ['name' => 'Ham', 'quantity' => 200, 'unit' => 'g'],
                    ['name' => 'Butter', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Sandwich bread', 'quantity' => 1, 'unit' => 'un'],
                ],
            ],
            'salada_mista' => [
                'name' => 'Mixed Salad',
                'emoji' => '🥗',
                'items' => [
                    ['name' => 'Lettuce', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Tomato', 'quantity' => 3, 'unit' => 'un'],
                    ['name' => 'Cucumber', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Onion', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Canned tuna', 'quantity' => 2, 'unit' => 'lata'],
                    ['name' => 'Olive oil', 'quantity' => 1, 'unit' => 'ml'],
                    ['name' => 'Vinegar', 'quantity' => 1, 'unit' => 'ml'],
                ],
            ],
        ];
    }

    /**
     * Item names match those in CatalogItemSeederBr so bundle merges link
     * to catalog items (preserving emoji, category, preferred-store hints).
     *
     * @return array<string, array{name: string, emoji: string, items: array<int, array{name: string, quantity: float, unit: string}>}>
     */
    private static function brazilianBundles(): array
    {
        return [
            'frango_assado' => [
                'name' => 'Frango Assado',
                'emoji' => '🍗',
                'items' => [
                    ['name' => 'Frango', 'quantity' => 1, 'unit' => 'kg'],
                    ['name' => 'Alho', 'quantity' => 4, 'unit' => 'un'],
                    ['name' => 'Limão', 'quantity' => 2, 'unit' => 'un'],
                    ['name' => 'Óleo de soja', 'quantity' => 1, 'unit' => 'ml'],
                    ['name' => 'Batata', 'quantity' => 1, 'unit' => 'kg'],
                    ['name' => 'Cebola', 'quantity' => 2, 'unit' => 'un'],
                    ['name' => 'Sal', 'quantity' => 1, 'unit' => 'un'],
                ],
            ],
            'caldo_verde' => [
                'name' => 'Sopa de Mandioca',
                'emoji' => '🥣',
                'items' => [
                    ['name' => 'Mandioca', 'quantity' => 1, 'unit' => 'kg'],
                    ['name' => 'Linguiça', 'quantity' => 0.5, 'unit' => 'kg'],
                    ['name' => 'Cebola', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Alho', 'quantity' => 2, 'unit' => 'un'],
                    ['name' => 'Óleo de soja', 'quantity' => 1, 'unit' => 'ml'],
                ],
            ],
            'bacalhau_braga' => [
                'name' => 'Bacalhau Assado',
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
                    ['name' => 'Picanha', 'quantity' => 1, 'unit' => 'kg'],
                    ['name' => 'Linguiça', 'quantity' => 0.5, 'unit' => 'kg'],
                    ['name' => 'Cerveja', 'quantity' => 6, 'unit' => 'un'],
                    ['name' => 'Pão de forma', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Sal', 'quantity' => 1, 'unit' => 'un'],
                ],
            ],
            'massa_bolonhesa' => [
                'name' => 'Macarrão à Bolonhesa',
                'emoji' => '🍝',
                'items' => [
                    ['name' => 'Macarrão', 'quantity' => 500, 'unit' => 'g'],
                    ['name' => 'Carne moída', 'quantity' => 500, 'unit' => 'g'],
                    ['name' => 'Molho de tomate', 'quantity' => 340, 'unit' => 'g'],
                    ['name' => 'Cebola', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Alho', 'quantity' => 2, 'unit' => 'un'],
                    ['name' => 'Óleo de soja', 'quantity' => 1, 'unit' => 'ml'],
                ],
            ],
            'arroz_frango' => [
                'name' => 'Frango com Arroz',
                'emoji' => '🍚',
                'items' => [
                    ['name' => 'Peito de frango', 'quantity' => 0.5, 'unit' => 'kg'],
                    ['name' => 'Arroz', 'quantity' => 1, 'unit' => 'kg'],
                    ['name' => 'Tomate', 'quantity' => 2, 'unit' => 'un'],
                    ['name' => 'Cebola', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Alho', 'quantity' => 2, 'unit' => 'un'],
                    ['name' => 'Óleo de soja', 'quantity' => 1, 'unit' => 'ml'],
                ],
            ],
            'sopa_legumes' => [
                'name' => 'Sopa de Legumes',
                'emoji' => '🍲',
                'items' => [
                    ['name' => 'Cenoura', 'quantity' => 2, 'unit' => 'un'],
                    ['name' => 'Batata', 'quantity' => 3, 'unit' => 'un'],
                    ['name' => 'Abóbora', 'quantity' => 0.5, 'unit' => 'kg'],
                    ['name' => 'Cebola', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Alho', 'quantity' => 2, 'unit' => 'un'],
                    ['name' => 'Sal', 'quantity' => 1, 'unit' => 'un'],
                ],
            ],
            'feijoada' => [
                'name' => 'Feijoada Brasileira',
                'emoji' => '🫘',
                'items' => [
                    ['name' => 'Feijão preto', 'quantity' => 1, 'unit' => 'kg'],
                    ['name' => 'Costela suína', 'quantity' => 0.5, 'unit' => 'kg'],
                    ['name' => 'Linguiça', 'quantity' => 0.5, 'unit' => 'kg'],
                    ['name' => 'Bacon', 'quantity' => 200, 'unit' => 'g'],
                    ['name' => 'Cebola', 'quantity' => 1, 'unit' => 'un'],
                    ['name' => 'Alho', 'quantity' => 2, 'unit' => 'un'],
                    ['name' => 'Farinha de mandioca', 'quantity' => 1, 'unit' => 'kg'],
                ],
            ],
            'ovos_mexidos' => [
                'name' => 'Ovos Mexidos com Bacon',
                'emoji' => '🍳',
                'items' => [
                    ['name' => 'Ovos', 'quantity' => 6, 'unit' => 'un'],
                    ['name' => 'Bacon', 'quantity' => 200, 'unit' => 'g'],
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
