<?php

namespace Database\Seeders;

use App\Models\CatalogItem;
use Illuminate\Database\Seeder;

/**
 * Brazilian Portuguese catalog using common BR grocery items and store hints
 * (Carrefour, Pão de Açúcar, Extra, Assaí, Atacadão).
 *
 * Run with: php artisan db:seed --class=CatalogItemSeederBr
 *
 * Pairs naturally with the Brazilian meal bundles inside MealBundles when
 * the app locale is set to "pt_BR".
 */
class CatalogItemSeederBr extends Seeder
{
    public function run(): void
    {
        $items = [
            // Frutas
            ['name' => 'Maçã', 'emoji' => '🍎', 'category' => 'fruta', 'preferred_store' => 'pao_de_acucar', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Banana', 'emoji' => '🍌', 'category' => 'fruta', 'preferred_store' => 'carrefour', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Laranja', 'emoji' => '🍊', 'category' => 'fruta', 'preferred_store' => 'carrefour', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Pera', 'emoji' => '🍐', 'category' => 'fruta', 'preferred_store' => 'pao_de_acucar', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Limão', 'emoji' => '🍋', 'category' => 'fruta', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 4],
            ['name' => 'Mexerica', 'emoji' => '🍊', 'category' => 'fruta', 'preferred_store' => 'extra', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Uva', 'emoji' => '🍇', 'category' => 'fruta', 'preferred_store' => 'pao_de_acucar', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Morango', 'emoji' => '🍓', 'category' => 'fruta', 'preferred_store' => 'pao_de_acucar', 'default_unit' => 'caixa', 'default_quantity' => 1],
            ['name' => 'Melancia', 'emoji' => '🍉', 'category' => 'fruta', 'preferred_store' => 'atacadao', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Abacate', 'emoji' => '🥑', 'category' => 'fruta', 'preferred_store' => 'extra', 'default_unit' => 'un', 'default_quantity' => 2],
            ['name' => 'Mamão', 'emoji' => '🥭', 'category' => 'fruta', 'preferred_store' => 'extra', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Manga', 'emoji' => '🥭', 'category' => 'fruta', 'preferred_store' => 'carrefour', 'default_unit' => 'un', 'default_quantity' => 2],
            ['name' => 'Maracujá', 'emoji' => '🥝', 'category' => 'fruta', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 3],
            ['name' => 'Abacaxi', 'emoji' => '🍍', 'category' => 'fruta', 'preferred_store' => 'extra', 'default_unit' => 'un', 'default_quantity' => 1],

            // Legumes & verduras
            ['name' => 'Tomate', 'emoji' => '🍅', 'category' => 'legumes', 'preferred_store' => 'carrefour', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Batata', 'emoji' => '🥔', 'category' => 'legumes', 'preferred_store' => 'atacadao', 'default_unit' => 'kg', 'default_quantity' => 2],
            ['name' => 'Cebola', 'emoji' => '🧅', 'category' => 'legumes', 'preferred_store' => null, 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Alho', 'emoji' => '🧄', 'category' => 'legumes', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Cenoura', 'emoji' => '🥕', 'category' => 'legumes', 'preferred_store' => 'extra', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Alface', 'emoji' => '🥬', 'category' => 'legumes', 'preferred_store' => 'pao_de_acucar', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Brócolis', 'emoji' => '🥦', 'category' => 'legumes', 'preferred_store' => 'pao_de_acucar', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Pepino', 'emoji' => '🥒', 'category' => 'legumes', 'preferred_store' => 'extra', 'default_unit' => 'un', 'default_quantity' => 2],
            ['name' => 'Pimentão', 'emoji' => '🫑', 'category' => 'legumes', 'preferred_store' => 'carrefour', 'default_unit' => 'un', 'default_quantity' => 2],
            ['name' => 'Cogumelo', 'emoji' => '🍄', 'category' => 'legumes', 'preferred_store' => 'pao_de_acucar', 'default_unit' => 'g', 'default_quantity' => 250],
            ['name' => 'Espinafre', 'emoji' => '🌿', 'category' => 'legumes', 'preferred_store' => 'extra', 'default_unit' => 'maço', 'default_quantity' => 1],
            ['name' => 'Mandioca', 'emoji' => '🥔', 'category' => 'legumes', 'preferred_store' => 'atacadao', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Abóbora', 'emoji' => '🎃', 'category' => 'legumes', 'preferred_store' => 'atacadao', 'default_unit' => 'kg', 'default_quantity' => 1],

            // Laticínios
            ['name' => 'Leite', 'emoji' => '🥛', 'category' => 'lacticinios', 'preferred_store' => 'carrefour', 'default_unit' => 'l', 'default_quantity' => 1],
            ['name' => 'Manteiga', 'emoji' => '🧈', 'category' => 'lacticinios', 'preferred_store' => 'pao_de_acucar', 'default_unit' => 'g', 'default_quantity' => 200],
            ['name' => 'Iogurte natural', 'emoji' => '🥛', 'category' => 'lacticinios', 'preferred_store' => 'extra', 'default_unit' => 'un', 'default_quantity' => 4],
            ['name' => 'Queijo prato', 'emoji' => '🧀', 'category' => 'lacticinios', 'preferred_store' => 'pao_de_acucar', 'default_unit' => 'g', 'default_quantity' => 200],
            ['name' => 'Mussarela', 'emoji' => '🧀', 'category' => 'lacticinios', 'preferred_store' => 'carrefour', 'default_unit' => 'g', 'default_quantity' => 200],
            ['name' => 'Requeijão', 'emoji' => '🧀', 'category' => 'lacticinios', 'preferred_store' => 'pao_de_acucar', 'default_unit' => 'g', 'default_quantity' => 200],
            ['name' => 'Creme de leite', 'emoji' => '🥛', 'category' => 'lacticinios', 'preferred_store' => 'carrefour', 'default_unit' => 'g', 'default_quantity' => 200],
            ['name' => 'Leite condensado', 'emoji' => '🥛', 'category' => 'lacticinios', 'preferred_store' => 'atacadao', 'default_unit' => 'lata', 'default_quantity' => 1],
            ['name' => 'Ovos', 'emoji' => '🥚', 'category' => 'lacticinios', 'preferred_store' => 'atacadao', 'default_unit' => 'dz', 'default_quantity' => 1],

            // Carne
            ['name' => 'Frango', 'emoji' => '🐔', 'category' => 'carne', 'preferred_store' => 'pao_de_acucar', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Peito de frango', 'emoji' => '🐔', 'category' => 'carne', 'preferred_store' => 'carrefour', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Filé de frango', 'emoji' => '🐔', 'category' => 'carne', 'preferred_store' => 'carrefour', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Bisteca suína', 'emoji' => '🥩', 'category' => 'carne', 'preferred_store' => 'assai', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Carne moída', 'emoji' => '🥩', 'category' => 'carne', 'preferred_store' => 'assai', 'default_unit' => 'g', 'default_quantity' => 500],
            ['name' => 'Linguiça', 'emoji' => '🌭', 'category' => 'carne', 'preferred_store' => 'pao_de_acucar', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Bacon', 'emoji' => '🥓', 'category' => 'carne', 'preferred_store' => null, 'default_unit' => 'g', 'default_quantity' => 200],
            ['name' => 'Presunto', 'emoji' => '🥓', 'category' => 'carne', 'preferred_store' => 'pao_de_acucar', 'default_unit' => 'g', 'default_quantity' => 200],
            ['name' => 'Costela suína', 'emoji' => '🥩', 'category' => 'carne', 'preferred_store' => 'assai', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Picanha', 'emoji' => '🥩', 'category' => 'carne', 'preferred_store' => 'pao_de_acucar', 'default_unit' => 'kg', 'default_quantity' => 1],

            // Peixe
            ['name' => 'Tilápia', 'emoji' => '🐟', 'category' => 'peixe', 'preferred_store' => 'pao_de_acucar', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Salmão', 'emoji' => '🐟', 'category' => 'peixe', 'preferred_store' => 'pao_de_acucar', 'default_unit' => 'g', 'default_quantity' => 400],
            ['name' => 'Atum em lata', 'emoji' => '🐟', 'category' => 'peixe', 'preferred_store' => 'extra', 'default_unit' => 'lata', 'default_quantity' => 3],
            ['name' => 'Bacalhau', 'emoji' => '🐟', 'category' => 'peixe', 'preferred_store' => 'pao_de_acucar', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Camarão', 'emoji' => '🦐', 'category' => 'peixe', 'preferred_store' => 'pao_de_acucar', 'default_unit' => 'g', 'default_quantity' => 300],
            ['name' => 'Sardinha em lata', 'emoji' => '🐟', 'category' => 'peixe', 'preferred_store' => 'extra', 'default_unit' => 'lata', 'default_quantity' => 2],

            // Padaria
            ['name' => 'Pão francês', 'emoji' => '🥖', 'category' => 'padaria', 'preferred_store' => null, 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Pão de forma', 'emoji' => '🍞', 'category' => 'padaria', 'preferred_store' => 'carrefour', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Pão de queijo', 'emoji' => '🥯', 'category' => 'padaria', 'preferred_store' => 'pao_de_acucar', 'default_unit' => 'g', 'default_quantity' => 500],
            ['name' => 'Bolo', 'emoji' => '🍰', 'category' => 'padaria', 'preferred_store' => 'pao_de_acucar', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Torrada', 'emoji' => '🍞', 'category' => 'padaria', 'preferred_store' => 'extra', 'default_unit' => 'pacote', 'default_quantity' => 1],

            // Bebidas
            ['name' => 'Água mineral', 'emoji' => '💧', 'category' => 'bebidas', 'preferred_store' => 'atacadao', 'default_unit' => 'l', 'default_quantity' => 6],
            ['name' => 'Suco de laranja', 'emoji' => '🍊', 'category' => 'bebidas', 'preferred_store' => 'carrefour', 'default_unit' => 'l', 'default_quantity' => 1],
            ['name' => 'Cerveja', 'emoji' => '🍺', 'category' => 'bebidas', 'preferred_store' => 'atacadao', 'default_unit' => 'un', 'default_quantity' => 6],
            ['name' => 'Vinho tinto', 'emoji' => '🍷', 'category' => 'bebidas', 'preferred_store' => 'pao_de_acucar', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Refrigerante', 'emoji' => '🥤', 'category' => 'bebidas', 'preferred_store' => 'extra', 'default_unit' => 'l', 'default_quantity' => 2],
            ['name' => 'Guaraná', 'emoji' => '🥤', 'category' => 'bebidas', 'preferred_store' => 'extra', 'default_unit' => 'l', 'default_quantity' => 2],
            ['name' => 'Café', 'emoji' => '☕', 'category' => 'bebidas', 'preferred_store' => 'atacadao', 'default_unit' => 'g', 'default_quantity' => 500],

            // Despensa
            ['name' => 'Arroz', 'emoji' => '🍚', 'category' => 'despensa', 'preferred_store' => 'atacadao', 'default_unit' => 'kg', 'default_quantity' => 5],
            ['name' => 'Feijão preto', 'emoji' => '🫘', 'category' => 'despensa', 'preferred_store' => 'atacadao', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Feijão carioca', 'emoji' => '🫘', 'category' => 'despensa', 'preferred_store' => 'atacadao', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Macarrão', 'emoji' => '🍝', 'category' => 'despensa', 'preferred_store' => 'extra', 'default_unit' => 'g', 'default_quantity' => 500],
            ['name' => 'Molho de tomate', 'emoji' => '🍅', 'category' => 'despensa', 'preferred_store' => 'extra', 'default_unit' => 'g', 'default_quantity' => 340],
            ['name' => 'Chocolate', 'emoji' => '🍫', 'category' => 'despensa', 'preferred_store' => 'pao_de_acucar', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Biscoito', 'emoji' => '🍪', 'category' => 'despensa', 'preferred_store' => 'extra', 'default_unit' => 'pacote', 'default_quantity' => 1],
            ['name' => 'Açúcar', 'emoji' => '🍬', 'category' => 'despensa', 'preferred_store' => 'atacadao', 'default_unit' => 'kg', 'default_quantity' => 5],
            ['name' => 'Sal', 'emoji' => '🧂', 'category' => 'despensa', 'preferred_store' => null, 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Farinha de trigo', 'emoji' => '🌾', 'category' => 'despensa', 'preferred_store' => 'atacadao', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Farinha de mandioca', 'emoji' => '🌾', 'category' => 'despensa', 'preferred_store' => 'atacadao', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Polvilho', 'emoji' => '🌾', 'category' => 'despensa', 'preferred_store' => 'pao_de_acucar', 'default_unit' => 'g', 'default_quantity' => 500],
            ['name' => 'Óleo de soja', 'emoji' => '🫙', 'category' => 'despensa', 'preferred_store' => 'atacadao', 'default_unit' => 'l', 'default_quantity' => 1],
            ['name' => 'Azeite', 'emoji' => '🫙', 'category' => 'despensa', 'preferred_store' => 'pao_de_acucar', 'default_unit' => 'ml', 'default_quantity' => 500],
            ['name' => 'Vinagre', 'emoji' => '🍾', 'category' => 'despensa', 'preferred_store' => null, 'default_unit' => 'ml', 'default_quantity' => 750],

            // Limpeza
            ['name' => 'Sabão em pó', 'emoji' => '🧺', 'category' => 'limpeza', 'preferred_store' => 'atacadao', 'default_unit' => 'kg', 'default_quantity' => 2],
            ['name' => 'Detergente', 'emoji' => '🧼', 'category' => 'limpeza', 'preferred_store' => 'extra', 'default_unit' => 'ml', 'default_quantity' => 500],
            ['name' => 'Amaciante', 'emoji' => '🧴', 'category' => 'limpeza', 'preferred_store' => 'carrefour', 'default_unit' => 'l', 'default_quantity' => 2],
            ['name' => 'Papel higiênico', 'emoji' => '🧻', 'category' => 'limpeza', 'preferred_store' => 'atacadao', 'default_unit' => 'un', 'default_quantity' => 12],
            ['name' => 'Esponja', 'emoji' => '🧽', 'category' => 'limpeza', 'preferred_store' => 'extra', 'default_unit' => 'un', 'default_quantity' => 4],
            ['name' => 'Saco de lixo', 'emoji' => '🗑️', 'category' => 'limpeza', 'preferred_store' => 'extra', 'default_unit' => 'rolo', 'default_quantity' => 1],
            ['name' => 'Desinfetante', 'emoji' => '🧹', 'category' => 'limpeza', 'preferred_store' => 'carrefour', 'default_unit' => 'l', 'default_quantity' => 1],

            // Higiene
            ['name' => 'Shampoo', 'emoji' => '🧴', 'category' => 'higiene', 'preferred_store' => 'pao_de_acucar', 'default_unit' => 'ml', 'default_quantity' => 400],
            ['name' => 'Sabonete', 'emoji' => '🧼', 'category' => 'higiene', 'preferred_store' => 'extra', 'default_unit' => 'un', 'default_quantity' => 4],
            ['name' => 'Pasta de dente', 'emoji' => '🪥', 'category' => 'higiene', 'preferred_store' => 'carrefour', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Escova de dente', 'emoji' => '🪥', 'category' => 'higiene', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 2],
            ['name' => 'Desodorante', 'emoji' => '🧴', 'category' => 'higiene', 'preferred_store' => 'extra', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Hidratante', 'emoji' => '🧴', 'category' => 'higiene', 'preferred_store' => 'pao_de_acucar', 'default_unit' => 'ml', 'default_quantity' => 200],
            ['name' => 'Absorvente', 'emoji' => '🩸', 'category' => 'higiene', 'preferred_store' => 'extra', 'default_unit' => 'pacote', 'default_quantity' => 1],
        ];

        foreach ($items as $item) {
            $item['locale'] = 'pt_BR';
            CatalogItem::firstOrCreate(['name' => $item['name'], 'locale' => 'pt_BR'], $item);
        }
    }
}
