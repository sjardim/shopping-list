<?php

namespace App\Enums;

enum Category: string
{
    case Fruit = 'fruta';
    case Vegetables = 'legumes';
    case Dairy = 'lacticinios';
    case Meat = 'carne';
    case Fish = 'peixe';
    case Bakery = 'padaria';
    case Beverages = 'bebidas';
    case Pantry = 'despensa';
    case Cleaning = 'limpeza';
    case Personal = 'higiene';

    public function label(): string
    {
        return match ($this) {
            self::Fruit => __('app.category_fruit'),
            self::Vegetables => __('app.category_vegetables'),
            self::Dairy => __('app.category_dairy'),
            self::Meat => __('app.category_meat'),
            self::Fish => __('app.category_fish'),
            self::Bakery => __('app.category_bakery'),
            self::Beverages => __('app.category_beverages'),
            self::Pantry => __('app.category_pantry'),
            self::Cleaning => __('app.category_cleaning'),
            self::Personal => __('app.category_personal'),
        };
    }

    public function emoji(): string
    {
        return match ($this) {
            self::Fruit => '🍎',
            self::Vegetables => '🥦',
            self::Dairy => '🥛',
            self::Meat => '🥩',
            self::Fish => '🐟',
            self::Bakery => '🍞',
            self::Beverages => '🥤',
            self::Pantry => '🫙',
            self::Cleaning => '🧹',
            self::Personal => '🧴',
        };
    }
}
