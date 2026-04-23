<?php

namespace Database\Seeders;

use App\Models\CatalogItem;
use Illuminate\Database\Seeder;

class CatalogItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // Fruta
            ['name' => 'Maçã', 'emoji' => '🍎', 'category' => 'fruta', 'preferred_store' => 'continente', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Banana', 'emoji' => '🍌', 'category' => 'fruta', 'preferred_store' => 'lidl', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Laranja', 'emoji' => '🍊', 'category' => 'fruta', 'preferred_store' => 'mercadona', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Pera', 'emoji' => '🍐', 'category' => 'fruta', 'preferred_store' => 'continente', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Lima', 'emoji' => '🍋', 'category' => 'fruta', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 3],
            ['name' => 'Limão', 'emoji' => '🍋', 'category' => 'fruta', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 3],
            ['name' => 'Uvas', 'emoji' => '🍇', 'category' => 'fruta', 'preferred_store' => 'aldi', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Morango', 'emoji' => '🍓', 'category' => 'fruta', 'preferred_store' => 'lidl', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Melancia', 'emoji' => '🍉', 'category' => 'fruta', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Abacate', 'emoji' => '🥑', 'category' => 'fruta', 'preferred_store' => 'mercadona', 'default_unit' => 'un', 'default_quantity' => 2],

            // Legumes
            ['name' => 'Tomate', 'emoji' => '🍅', 'category' => 'legumes', 'preferred_store' => 'continente', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Batata', 'emoji' => '🥔', 'category' => 'legumes', 'preferred_store' => 'lidl', 'default_unit' => 'kg', 'default_quantity' => 2],
            ['name' => 'Cebola', 'emoji' => '🧅', 'category' => 'legumes', 'preferred_store' => null, 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Alho', 'emoji' => '🧄', 'category' => 'legumes', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Cenoura', 'emoji' => '🥕', 'category' => 'legumes', 'preferred_store' => 'aldi', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Alface', 'emoji' => '🥬', 'category' => 'legumes', 'preferred_store' => 'mercadona', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Couve', 'emoji' => '🥦', 'category' => 'legumes', 'preferred_store' => 'continente', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Pepino', 'emoji' => '🥒', 'category' => 'legumes', 'preferred_store' => 'mercadona', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Pimento', 'emoji' => '🫑', 'category' => 'legumes', 'preferred_store' => 'mercadona', 'default_unit' => 'un', 'default_quantity' => 2],
            ['name' => 'Cogumelos', 'emoji' => '🍄', 'category' => 'legumes', 'preferred_store' => 'continente', 'default_unit' => 'g', 'default_quantity' => 250],
            ['name' => 'Espinafres', 'emoji' => '🌿', 'category' => 'legumes', 'preferred_store' => 'aldi', 'default_unit' => 'g', 'default_quantity' => 200],

            // Laticínios
            ['name' => 'Leite', 'emoji' => '🥛', 'category' => 'lacticinios', 'preferred_store' => 'continente', 'default_unit' => 'l', 'default_quantity' => 1],
            ['name' => 'Manteiga', 'emoji' => '🧈', 'category' => 'lacticinios', 'preferred_store' => 'mercadona', 'default_unit' => 'g', 'default_quantity' => 250],
            ['name' => 'Iogurte natural', 'emoji' => '🥛', 'category' => 'lacticinios', 'preferred_store' => 'mercadona', 'default_unit' => 'un', 'default_quantity' => 4],
            ['name' => 'Queijo flamengo', 'emoji' => '🧀', 'category' => 'lacticinios', 'preferred_store' => 'continente', 'default_unit' => 'g', 'default_quantity' => 200],
            ['name' => 'Natas', 'emoji' => '🥛', 'category' => 'lacticinios', 'preferred_store' => 'continente', 'default_unit' => 'ml', 'default_quantity' => 200],
            ['name' => 'Ovos', 'emoji' => '🥚', 'category' => 'lacticinios', 'preferred_store' => 'lidl', 'default_unit' => 'dz', 'default_quantity' => 1],
            ['name' => 'Queijo da Serra', 'emoji' => '🧀', 'category' => 'lacticinios', 'preferred_store' => 'continente', 'default_unit' => 'g', 'default_quantity' => 200],

            // Carne
            ['name' => 'Frango inteiro', 'emoji' => '🐔', 'category' => 'carne', 'preferred_store' => 'continente', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Peito de frango', 'emoji' => '🐔', 'category' => 'carne', 'preferred_store' => 'mercadona', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Costeletas de porco', 'emoji' => '🥩', 'category' => 'carne', 'preferred_store' => 'lidl', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Carne picada', 'emoji' => '🥩', 'category' => 'carne', 'preferred_store' => 'continente', 'default_unit' => 'g', 'default_quantity' => 500],
            ['name' => 'Chouriço', 'emoji' => '🌭', 'category' => 'carne', 'preferred_store' => 'continente', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Bacon', 'emoji' => '🥓', 'category' => 'carne', 'preferred_store' => null, 'default_unit' => 'g', 'default_quantity' => 200],
            ['name' => 'Presunto', 'emoji' => '🥓', 'category' => 'carne', 'preferred_store' => 'mercadona', 'default_unit' => 'g', 'default_quantity' => 200],
            ['name' => 'Entrecosto', 'emoji' => '🥩', 'category' => 'carne', 'preferred_store' => 'continente', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Alheira', 'emoji' => '🌭', 'category' => 'carne', 'preferred_store' => 'continente', 'default_unit' => 'un', 'default_quantity' => 2],
            ['name' => 'Fiambre', 'emoji' => '🥩', 'category' => 'carne', 'preferred_store' => 'mercadona', 'default_unit' => 'g', 'default_quantity' => 200],

            // Peixe
            ['name' => 'Bacalhau', 'emoji' => '🐟', 'category' => 'peixe', 'preferred_store' => 'continente', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Sardinha', 'emoji' => '🐟', 'category' => 'peixe', 'preferred_store' => 'continente', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Salmão', 'emoji' => '🐟', 'category' => 'peixe', 'preferred_store' => 'continente', 'default_unit' => 'g', 'default_quantity' => 400],
            ['name' => 'Atum em lata', 'emoji' => '🐟', 'category' => 'peixe', 'preferred_store' => 'mercadona', 'default_unit' => 'lata', 'default_quantity' => 3],
            ['name' => 'Camarão', 'emoji' => '🦐', 'category' => 'peixe', 'preferred_store' => 'continente', 'default_unit' => 'g', 'default_quantity' => 300],
            ['name' => 'Polvo', 'emoji' => '🐙', 'category' => 'peixe', 'preferred_store' => 'continente', 'default_unit' => 'kg', 'default_quantity' => 1],

            // Padaria
            ['name' => 'Pão', 'emoji' => '🍞', 'category' => 'padaria', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Pão de forma', 'emoji' => '🍞', 'category' => 'padaria', 'preferred_store' => 'continente', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Baguete', 'emoji' => '🥖', 'category' => 'padaria', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 2],
            ['name' => 'Papo-seco', 'emoji' => '🍞', 'category' => 'padaria', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 6],
            ['name' => 'Croissant', 'emoji' => '🥐', 'category' => 'padaria', 'preferred_store' => 'lidl', 'default_unit' => 'un', 'default_quantity' => 4],
            ['name' => 'Tostas', 'emoji' => '🍞', 'category' => 'padaria', 'preferred_store' => 'mercadona', 'default_unit' => 'pacote', 'default_quantity' => 1],
            ['name' => 'Broa de milho', 'emoji' => '🌽', 'category' => 'padaria', 'preferred_store' => 'continente', 'default_unit' => 'un', 'default_quantity' => 1],

            // Bebidas
            ['name' => 'Água mineral', 'emoji' => '💧', 'category' => 'bebidas', 'preferred_store' => 'mercadona', 'default_unit' => 'l', 'default_quantity' => 6],
            ['name' => 'Sumo de laranja', 'emoji' => '🍊', 'category' => 'bebidas', 'preferred_store' => 'continente', 'default_unit' => 'l', 'default_quantity' => 1],
            ['name' => 'Cerveja', 'emoji' => '🍺', 'category' => 'bebidas', 'preferred_store' => 'lidl', 'default_unit' => 'un', 'default_quantity' => 6],
            ['name' => 'Vinho tinto', 'emoji' => '🍷', 'category' => 'bebidas', 'preferred_store' => 'continente', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Vinho branco', 'emoji' => '🍾', 'category' => 'bebidas', 'preferred_store' => 'continente', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Refrigerante', 'emoji' => '🥤', 'category' => 'bebidas', 'preferred_store' => 'mercadona', 'default_unit' => 'l', 'default_quantity' => 1.5],
            ['name' => 'Café', 'emoji' => '☕', 'category' => 'bebidas', 'preferred_store' => 'mercadona', 'default_unit' => 'g', 'default_quantity' => 250],

            // Despensa
            ['name' => 'Azeite', 'emoji' => '🫙', 'category' => 'despensa', 'preferred_store' => 'continente', 'default_unit' => 'ml', 'default_quantity' => 750],
            ['name' => 'Arroz', 'emoji' => '🍚', 'category' => 'despensa', 'preferred_store' => 'mercadona', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Massa', 'emoji' => '🍝', 'category' => 'despensa', 'preferred_store' => 'mercadona', 'default_unit' => 'g', 'default_quantity' => 500],
            ['name' => 'Massa esparguete', 'emoji' => '🍝', 'category' => 'despensa', 'preferred_store' => 'mercadona', 'default_unit' => 'g', 'default_quantity' => 500],
            ['name' => 'Molho de tomate', 'emoji' => '🍅', 'category' => 'despensa', 'preferred_store' => 'mercadona', 'default_unit' => 'ml', 'default_quantity' => 400],
            ['name' => 'Chocolate', 'emoji' => '🍫', 'category' => 'despensa', 'preferred_store' => 'lidl', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Bolachas', 'emoji' => '🍪', 'category' => 'despensa', 'preferred_store' => 'mercadona', 'default_unit' => 'pacote', 'default_quantity' => 1],
            ['name' => 'Feijão em lata', 'emoji' => '🫘', 'category' => 'despensa', 'preferred_store' => 'mercadona', 'default_unit' => 'lata', 'default_quantity' => 2],
            ['name' => 'Grão-de-bico', 'emoji' => '🫘', 'category' => 'despensa', 'preferred_store' => 'mercadona', 'default_unit' => 'lata', 'default_quantity' => 2],
            ['name' => 'Sal', 'emoji' => '🧂', 'category' => 'despensa', 'preferred_store' => null, 'default_unit' => 'g', 'default_quantity' => 500],
            ['name' => 'Açúcar', 'emoji' => '🍬', 'category' => 'despensa', 'preferred_store' => null, 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Farinha', 'emoji' => '🌾', 'category' => 'despensa', 'preferred_store' => 'continente', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Polpa de tomate', 'emoji' => '🍅', 'category' => 'despensa', 'preferred_store' => 'mercadona', 'default_unit' => 'ml', 'default_quantity' => 400],
            ['name' => 'Vinagre', 'emoji' => '🍾', 'category' => 'despensa', 'preferred_store' => null, 'default_unit' => 'ml', 'default_quantity' => 250],
            ['name' => 'Maionese', 'emoji' => '🫙', 'category' => 'despensa', 'preferred_store' => 'mercadona', 'default_unit' => 'g', 'default_quantity' => 250],

            // Limpeza
            ['name' => 'Detergente roupa', 'emoji' => '🧺', 'category' => 'limpeza', 'preferred_store' => 'aldi', 'default_unit' => 'l', 'default_quantity' => 2],
            ['name' => 'Detergente loiça', 'emoji' => '🧼', 'category' => 'limpeza', 'preferred_store' => 'mercadona', 'default_unit' => 'ml', 'default_quantity' => 500],
            ['name' => 'Amaciador', 'emoji' => '🧴', 'category' => 'limpeza', 'preferred_store' => 'aldi', 'default_unit' => 'l', 'default_quantity' => 1],
            ['name' => 'Papel higiénico', 'emoji' => '🧻', 'category' => 'limpeza', 'preferred_store' => 'lidl', 'default_unit' => 'un', 'default_quantity' => 12],
            ['name' => 'Esponjas', 'emoji' => '🧽', 'category' => 'limpeza', 'preferred_store' => 'aldi', 'default_unit' => 'un', 'default_quantity' => 4],
            ['name' => 'Sacos do lixo', 'emoji' => '🗑️', 'category' => 'limpeza', 'preferred_store' => 'mercadona', 'default_unit' => 'rolo', 'default_quantity' => 1],
            ['name' => 'Desengordurante', 'emoji' => '🧹', 'category' => 'limpeza', 'preferred_store' => 'mercadona', 'default_unit' => 'un', 'default_quantity' => 1],

            // Higiene
            ['name' => 'Champô', 'emoji' => '🧴', 'category' => 'higiene', 'preferred_store' => 'mercadona', 'default_unit' => 'ml', 'default_quantity' => 400],
            ['name' => 'Gel de duche', 'emoji' => '🧴', 'category' => 'higiene', 'preferred_store' => 'mercadona', 'default_unit' => 'ml', 'default_quantity' => 400],
            ['name' => 'Pasta de dentes', 'emoji' => '🪥', 'category' => 'higiene', 'preferred_store' => 'mercadona', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Escova de dentes', 'emoji' => '🪥', 'category' => 'higiene', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 2],
            ['name' => 'Desodorizante', 'emoji' => '🧴', 'category' => 'higiene', 'preferred_store' => 'mercadona', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Creme hidratante', 'emoji' => '🧴', 'category' => 'higiene', 'preferred_store' => 'continente', 'default_unit' => 'ml', 'default_quantity' => 200],
            ['name' => 'Absorventes', 'emoji' => '🩸', 'category' => 'higiene', 'preferred_store' => 'mercadona', 'default_unit' => 'pacote', 'default_quantity' => 1],
        ];

        foreach ($items as $item) {
            CatalogItem::firstOrCreate(['name' => $item['name']], $item);
        }
    }
}
