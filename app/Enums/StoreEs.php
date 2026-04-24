<?php

declare(strict_types=1);

namespace App\Enums;

use App\Contracts\Store;

enum StoreEs: string implements Store
{
    case Mercadona = 'mercadona';
    case Carrefour = 'carrefour';
    case Dia = 'dia';
    case Eroski = 'eroski';
    case Alcampo = 'alcampo';
    case Hipercor = 'hipercor';

    public function label(): string
    {
        return match ($this) {
            self::Mercadona => 'Mercadona',
            self::Carrefour => 'Carrefour',
            self::Dia => 'DIA',
            self::Eroski => 'Eroski',
            self::Alcampo => 'Alcampo',
            self::Hipercor => 'Hipercor',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Mercadona => '#2f7d4f',
            self::Carrefour => '#003876',
            self::Dia => '#e30613',
            self::Eroski => '#c8102e',
            self::Alcampo => '#fcb017',
            self::Hipercor => '#005ca9',
        };
    }

    public function initial(): string
    {
        return strtoupper($this->value[0]);
    }

    public function hasDarkText(): bool
    {
        return $this === self::Alcampo;
    }
}
