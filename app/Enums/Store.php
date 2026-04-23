<?php

namespace App\Enums;

enum Store: string
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

    /** Hex background color for the store badge. */
    public function color(): string
    {
        return match ($this) {
            self::Lidl => '#f5c518',
            self::Aldi => '#1a73e8',
            self::Continente => '#e53935',
            self::Mercadona => '#2f7d4f',
        };
    }

    /** Single uppercase letter shown inside the badge. */
    public function initial(): string
    {
        return strtoupper($this->value[0]);
    }

    /** Whether the badge initial should use dark text (for light bg colors). */
    public function hasDarkText(): bool
    {
        return $this === self::Lidl;
    }
}
