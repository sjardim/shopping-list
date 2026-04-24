<?php

namespace App\Enums;

use App\Contracts\Store;

enum StorePt: string implements Store
{
    case Lidl = 'lidl';
    case Aldi = 'aldi';
    case Continente = 'continente';
    case Mercadona = 'mercadona';

    public function label(): string
    {
        return match ($this) {
            self::Lidl => 'Lidl',
            self::Aldi => 'Aldi',
            self::Continente => 'Continente',
            self::Mercadona => 'Mercadona',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Lidl => '#f5c518',
            self::Aldi => '#1a73e8',
            self::Continente => '#e53935',
            self::Mercadona => '#2f7d4f',
        };
    }

    public function initial(): string
    {
        return strtoupper($this->value[0]);
    }

    public function hasDarkText(): bool
    {
        return $this === self::Lidl;
    }
}
