<?php

namespace App\Enums;

use App\Contracts\Store;

enum StoreUs: string implements Store
{
    case Walmart = 'walmart';
    case Target = 'target';
    case TraderJoes = 'trader_joes';
    case WholeFoods = 'whole_foods';

    public function label(): string
    {
        return match ($this) {
            self::Walmart => 'Walmart',
            self::Target => 'Target',
            self::TraderJoes => "Trader Joe's",
            self::WholeFoods => 'Whole Foods',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Walmart => '#0071ce',
            self::Target => '#cc0000',
            self::TraderJoes => '#b22234',
            self::WholeFoods => '#006847',
        };
    }

    public function initial(): string
    {
        return match ($this) {
            self::TraderJoes => 'T',
            self::WholeFoods => 'W',
            default => strtoupper($this->value[0]),
        };
    }

    public function hasDarkText(): bool
    {
        return false;
    }
}
