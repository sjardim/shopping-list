<?php

namespace App\Enums;

enum Store: string
{
    // Portugal / Iberia
    case Lidl = 'lidl';
    case Aldi = 'aldi';
    case Continente = 'continente';
    case Mercadona = 'mercadona';

    // United States
    case Walmart = 'walmart';
    case Target = 'target';
    case TraderJoes = 'trader_joes';
    case WholeFoods = 'whole_foods';

    public function label(): string
    {
        return match ($this) {
            self::Lidl => 'Lidl',
            self::Aldi => 'Aldi',
            self::Continente => 'Continente',
            self::Mercadona => 'Mercadona',
            self::Walmart => 'Walmart',
            self::Target => 'Target',
            self::TraderJoes => "Trader Joe's",
            self::WholeFoods => 'Whole Foods',
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
            self::Walmart => '#0071ce',
            self::Target => '#cc0000',
            self::TraderJoes => '#b22234',
            self::WholeFoods => '#006847',
        };
    }

    /** Single uppercase letter shown inside the badge. */
    public function initial(): string
    {
        return match ($this) {
            self::TraderJoes => 'T',
            self::WholeFoods => 'W',
            default => strtoupper($this->value[0]),
        };
    }

    /** Whether the badge initial should use dark text (for light bg colors). */
    public function hasDarkText(): bool
    {
        return $this === self::Lidl;
    }
}
