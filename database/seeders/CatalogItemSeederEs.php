<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\CatalogItem;
use Illuminate\Database\Seeder;

/**
 * Spanish catalog using common ES grocery items and store hints
 * (Mercadona, Carrefour, DIA, Eroski, Alcampo, Hipercor).
 *
 * Run with: php artisan db:seed --class=CatalogItemSeederEs
 *
 * Pairs with the Spanish meal bundles inside MealBundles when the app
 * locale is set to "es".
 */
class CatalogItemSeederEs extends Seeder
{
    public function run(): void
    {
        $items = [
            // Frutas
            ['name' => 'Manzana', 'emoji' => '🍎', 'category' => 'fruta', 'preferred_store' => 'mercadona', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Plátano', 'emoji' => '🍌', 'category' => 'fruta', 'preferred_store' => 'carrefour', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Naranja', 'emoji' => '🍊', 'category' => 'fruta', 'preferred_store' => 'mercadona', 'default_unit' => 'kg', 'default_quantity' => 2],
            ['name' => 'Pera', 'emoji' => '🍐', 'category' => 'fruta', 'preferred_store' => 'mercadona', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Limón', 'emoji' => '🍋', 'category' => 'fruta', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 4],
            ['name' => 'Lima', 'emoji' => '🍋', 'category' => 'fruta', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 3],
            ['name' => 'Uvas', 'emoji' => '🍇', 'category' => 'fruta', 'preferred_store' => 'eroski', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Fresas', 'emoji' => '🍓', 'category' => 'fruta', 'preferred_store' => 'mercadona', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Sandía', 'emoji' => '🍉', 'category' => 'fruta', 'preferred_store' => 'alcampo', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Aguacate', 'emoji' => '🥑', 'category' => 'fruta', 'preferred_store' => 'mercadona', 'default_unit' => 'un', 'default_quantity' => 2],
            ['name' => 'Melocotón', 'emoji' => '🍑', 'category' => 'fruta', 'preferred_store' => 'eroski', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Mandarina', 'emoji' => '🍊', 'category' => 'fruta', 'preferred_store' => 'mercadona', 'default_unit' => 'kg', 'default_quantity' => 1],

            // Verduras
            ['name' => 'Tomate', 'emoji' => '🍅', 'category' => 'legumes', 'preferred_store' => 'mercadona', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Patata', 'emoji' => '🥔', 'category' => 'legumes', 'preferred_store' => 'alcampo', 'default_unit' => 'kg', 'default_quantity' => 2],
            ['name' => 'Cebolla', 'emoji' => '🧅', 'category' => 'legumes', 'preferred_store' => null, 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Ajo', 'emoji' => '🧄', 'category' => 'legumes', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Zanahoria', 'emoji' => '🥕', 'category' => 'legumes', 'preferred_store' => 'dia', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Lechuga', 'emoji' => '🥬', 'category' => 'legumes', 'preferred_store' => 'mercadona', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Brócoli', 'emoji' => '🥦', 'category' => 'legumes', 'preferred_store' => 'mercadona', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Pepino', 'emoji' => '🥒', 'category' => 'legumes', 'preferred_store' => 'mercadona', 'default_unit' => 'un', 'default_quantity' => 2],
            ['name' => 'Pimiento', 'emoji' => '🫑', 'category' => 'legumes', 'preferred_store' => 'mercadona', 'default_unit' => 'un', 'default_quantity' => 2],
            ['name' => 'Champiñón', 'emoji' => '🍄', 'category' => 'legumes', 'preferred_store' => 'mercadona', 'default_unit' => 'g', 'default_quantity' => 250],
            ['name' => 'Espinacas', 'emoji' => '🌿', 'category' => 'legumes', 'preferred_store' => 'eroski', 'default_unit' => 'g', 'default_quantity' => 200],
            ['name' => 'Calabacín', 'emoji' => '🥒', 'category' => 'legumes', 'preferred_store' => 'mercadona', 'default_unit' => 'un', 'default_quantity' => 2],
            ['name' => 'Berenjena', 'emoji' => '🍆', 'category' => 'legumes', 'preferred_store' => 'mercadona', 'default_unit' => 'un', 'default_quantity' => 1],

            // Lácteos y huevos
            ['name' => 'Leche', 'emoji' => '🥛', 'category' => 'lacticinios', 'preferred_store' => 'mercadona', 'default_unit' => 'l', 'default_quantity' => 6],
            ['name' => 'Mantequilla', 'emoji' => '🧈', 'category' => 'lacticinios', 'preferred_store' => 'mercadona', 'default_unit' => 'g', 'default_quantity' => 250],
            ['name' => 'Yogur natural', 'emoji' => '🥛', 'category' => 'lacticinios', 'preferred_store' => 'mercadona', 'default_unit' => 'un', 'default_quantity' => 4],
            ['name' => 'Queso manchego', 'emoji' => '🧀', 'category' => 'lacticinios', 'preferred_store' => 'mercadona', 'default_unit' => 'g', 'default_quantity' => 250],
            ['name' => 'Queso fresco', 'emoji' => '🧀', 'category' => 'lacticinios', 'preferred_store' => 'mercadona', 'default_unit' => 'g', 'default_quantity' => 250],
            ['name' => 'Nata', 'emoji' => '🥛', 'category' => 'lacticinios', 'preferred_store' => 'mercadona', 'default_unit' => 'ml', 'default_quantity' => 200],
            ['name' => 'Huevos', 'emoji' => '🥚', 'category' => 'lacticinios', 'preferred_store' => 'mercadona', 'default_unit' => 'dz', 'default_quantity' => 1],

            // Carne
            ['name' => 'Pollo entero', 'emoji' => '🐔', 'category' => 'carne', 'preferred_store' => 'mercadona', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Pechuga de pollo', 'emoji' => '🐔', 'category' => 'carne', 'preferred_store' => 'mercadona', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Chuletas de cerdo', 'emoji' => '🥩', 'category' => 'carne', 'preferred_store' => 'eroski', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Carne picada', 'emoji' => '🥩', 'category' => 'carne', 'preferred_store' => 'mercadona', 'default_unit' => 'g', 'default_quantity' => 500],
            ['name' => 'Chorizo', 'emoji' => '🌭', 'category' => 'carne', 'preferred_store' => 'mercadona', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Morcilla', 'emoji' => '🌭', 'category' => 'carne', 'preferred_store' => 'mercadona', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Jamón serrano', 'emoji' => '🥓', 'category' => 'carne', 'preferred_store' => 'hipercor', 'default_unit' => 'g', 'default_quantity' => 200],
            ['name' => 'Jamón ibérico', 'emoji' => '🥓', 'category' => 'carne', 'preferred_store' => 'hipercor', 'default_unit' => 'g', 'default_quantity' => 100],
            ['name' => 'Bacon', 'emoji' => '🥓', 'category' => 'carne', 'preferred_store' => null, 'default_unit' => 'g', 'default_quantity' => 200],
            ['name' => 'Costillas', 'emoji' => '🥩', 'category' => 'carne', 'preferred_store' => 'eroski', 'default_unit' => 'kg', 'default_quantity' => 1],

            // Pescado y marisco
            ['name' => 'Bacalao', 'emoji' => '🐟', 'category' => 'peixe', 'preferred_store' => 'hipercor', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Merluza', 'emoji' => '🐟', 'category' => 'peixe', 'preferred_store' => 'mercadona', 'default_unit' => 'kg', 'default_quantity' => 0.5],
            ['name' => 'Salmón', 'emoji' => '🐟', 'category' => 'peixe', 'preferred_store' => 'mercadona', 'default_unit' => 'g', 'default_quantity' => 400],
            ['name' => 'Atún en lata', 'emoji' => '🐟', 'category' => 'peixe', 'preferred_store' => 'mercadona', 'default_unit' => 'lata', 'default_quantity' => 3],
            ['name' => 'Gambas', 'emoji' => '🦐', 'category' => 'peixe', 'preferred_store' => 'mercadona', 'default_unit' => 'g', 'default_quantity' => 300],
            ['name' => 'Mejillones', 'emoji' => '🦪', 'category' => 'peixe', 'preferred_store' => 'mercadona', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Sardinas en lata', 'emoji' => '🐟', 'category' => 'peixe', 'preferred_store' => 'mercadona', 'default_unit' => 'lata', 'default_quantity' => 2],

            // Panadería
            ['name' => 'Pan de barra', 'emoji' => '🥖', 'category' => 'padaria', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Pan de molde', 'emoji' => '🍞', 'category' => 'padaria', 'preferred_store' => 'mercadona', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Pan integral', 'emoji' => '🍞', 'category' => 'padaria', 'preferred_store' => 'mercadona', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Croissant', 'emoji' => '🥐', 'category' => 'padaria', 'preferred_store' => 'mercadona', 'default_unit' => 'un', 'default_quantity' => 4],
            ['name' => 'Magdalenas', 'emoji' => '🧁', 'category' => 'padaria', 'preferred_store' => 'mercadona', 'default_unit' => 'paquete', 'default_quantity' => 1],

            // Bebidas
            ['name' => 'Agua mineral', 'emoji' => '💧', 'category' => 'bebidas', 'preferred_store' => 'alcampo', 'default_unit' => 'l', 'default_quantity' => 6],
            ['name' => 'Zumo de naranja', 'emoji' => '🍊', 'category' => 'bebidas', 'preferred_store' => 'mercadona', 'default_unit' => 'l', 'default_quantity' => 1],
            ['name' => 'Cerveza', 'emoji' => '🍺', 'category' => 'bebidas', 'preferred_store' => 'mercadona', 'default_unit' => 'un', 'default_quantity' => 6],
            ['name' => 'Vino tinto', 'emoji' => '🍷', 'category' => 'bebidas', 'preferred_store' => 'hipercor', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Vino blanco', 'emoji' => '🍾', 'category' => 'bebidas', 'preferred_store' => 'hipercor', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Refresco', 'emoji' => '🥤', 'category' => 'bebidas', 'preferred_store' => 'dia', 'default_unit' => 'l', 'default_quantity' => 1.5],
            ['name' => 'Café', 'emoji' => '☕', 'category' => 'bebidas', 'preferred_store' => 'mercadona', 'default_unit' => 'g', 'default_quantity' => 250],

            // Despensa
            ['name' => 'Aceite de oliva', 'emoji' => '🫙', 'category' => 'despensa', 'preferred_store' => 'mercadona', 'default_unit' => 'l', 'default_quantity' => 1],
            ['name' => 'Arroz', 'emoji' => '🍚', 'category' => 'despensa', 'preferred_store' => 'mercadona', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Pasta', 'emoji' => '🍝', 'category' => 'despensa', 'preferred_store' => 'dia', 'default_unit' => 'g', 'default_quantity' => 500],
            ['name' => 'Espaguetis', 'emoji' => '🍝', 'category' => 'despensa', 'preferred_store' => 'dia', 'default_unit' => 'g', 'default_quantity' => 500],
            ['name' => 'Tomate frito', 'emoji' => '🍅', 'category' => 'despensa', 'preferred_store' => 'mercadona', 'default_unit' => 'g', 'default_quantity' => 350],
            ['name' => 'Chocolate', 'emoji' => '🍫', 'category' => 'despensa', 'preferred_store' => 'mercadona', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Galletas', 'emoji' => '🍪', 'category' => 'despensa', 'preferred_store' => 'mercadona', 'default_unit' => 'paquete', 'default_quantity' => 1],
            ['name' => 'Garbanzos', 'emoji' => '🫘', 'category' => 'despensa', 'preferred_store' => 'mercadona', 'default_unit' => 'lata', 'default_quantity' => 2],
            ['name' => 'Lentejas', 'emoji' => '🫘', 'category' => 'despensa', 'preferred_store' => 'mercadona', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Judías blancas', 'emoji' => '🫘', 'category' => 'despensa', 'preferred_store' => 'mercadona', 'default_unit' => 'lata', 'default_quantity' => 2],
            ['name' => 'Sal', 'emoji' => '🧂', 'category' => 'despensa', 'preferred_store' => null, 'default_unit' => 'g', 'default_quantity' => 500],
            ['name' => 'Azúcar', 'emoji' => '🍬', 'category' => 'despensa', 'preferred_store' => null, 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Harina', 'emoji' => '🌾', 'category' => 'despensa', 'preferred_store' => 'mercadona', 'default_unit' => 'kg', 'default_quantity' => 1],
            ['name' => 'Vinagre', 'emoji' => '🍾', 'category' => 'despensa', 'preferred_store' => null, 'default_unit' => 'ml', 'default_quantity' => 750],
            ['name' => 'Mayonesa', 'emoji' => '🫙', 'category' => 'despensa', 'preferred_store' => 'mercadona', 'default_unit' => 'g', 'default_quantity' => 250],
            ['name' => 'Aceitunas', 'emoji' => '🫒', 'category' => 'despensa', 'preferred_store' => 'mercadona', 'default_unit' => 'g', 'default_quantity' => 200],
            ['name' => 'Pimentón', 'emoji' => '🌶️', 'category' => 'despensa', 'preferred_store' => 'mercadona', 'default_unit' => 'g', 'default_quantity' => 75],

            // Limpieza
            ['name' => 'Detergente ropa', 'emoji' => '🧺', 'category' => 'limpeza', 'preferred_store' => 'alcampo', 'default_unit' => 'l', 'default_quantity' => 2],
            ['name' => 'Lavavajillas', 'emoji' => '🧼', 'category' => 'limpeza', 'preferred_store' => 'mercadona', 'default_unit' => 'ml', 'default_quantity' => 750],
            ['name' => 'Suavizante', 'emoji' => '🧴', 'category' => 'limpeza', 'preferred_store' => 'alcampo', 'default_unit' => 'l', 'default_quantity' => 1],
            ['name' => 'Papel higiénico', 'emoji' => '🧻', 'category' => 'limpeza', 'preferred_store' => 'alcampo', 'default_unit' => 'un', 'default_quantity' => 12],
            ['name' => 'Estropajos', 'emoji' => '🧽', 'category' => 'limpeza', 'preferred_store' => 'dia', 'default_unit' => 'un', 'default_quantity' => 4],
            ['name' => 'Bolsas de basura', 'emoji' => '🗑️', 'category' => 'limpeza', 'preferred_store' => 'mercadona', 'default_unit' => 'rollo', 'default_quantity' => 1],
            ['name' => 'Limpiador multiusos', 'emoji' => '🧹', 'category' => 'limpeza', 'preferred_store' => 'mercadona', 'default_unit' => 'un', 'default_quantity' => 1],

            // Higiene personal
            ['name' => 'Champú', 'emoji' => '🧴', 'category' => 'higiene', 'preferred_store' => 'mercadona', 'default_unit' => 'ml', 'default_quantity' => 400],
            ['name' => 'Gel de ducha', 'emoji' => '🧴', 'category' => 'higiene', 'preferred_store' => 'mercadona', 'default_unit' => 'ml', 'default_quantity' => 500],
            ['name' => 'Pasta de dientes', 'emoji' => '🪥', 'category' => 'higiene', 'preferred_store' => 'mercadona', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Cepillo de dientes', 'emoji' => '🪥', 'category' => 'higiene', 'preferred_store' => null, 'default_unit' => 'un', 'default_quantity' => 2],
            ['name' => 'Desodorante', 'emoji' => '🧴', 'category' => 'higiene', 'preferred_store' => 'mercadona', 'default_unit' => 'un', 'default_quantity' => 1],
            ['name' => 'Crema hidratante', 'emoji' => '🧴', 'category' => 'higiene', 'preferred_store' => 'mercadona', 'default_unit' => 'ml', 'default_quantity' => 200],
            ['name' => 'Compresas', 'emoji' => '🩸', 'category' => 'higiene', 'preferred_store' => 'mercadona', 'default_unit' => 'paquete', 'default_quantity' => 1],
        ];

        foreach ($items as $item) {
            CatalogItem::firstOrCreate(['name' => $item['name']], $item);
        }
    }
}
