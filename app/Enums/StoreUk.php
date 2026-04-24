<?php

namespace App\Enums;

use App\Contracts\Store;

enum StoreUk: string implements Store
{
    case Tesco = 'tesco';
    case Sainsburys = 'sainsburys';
    case Asda = 'asda';
    case Morrisons = 'morrisons';
    case Waitrose = 'waitrose';
    case Lidl = 'lidl';
    case Aldi = 'aldi';

    public function label(): string
    {
        return match ($this) {
            self::Tesco => 'Tesco',
            self::Sainsburys => "Sainsbury's",
            self::Asda => 'Asda',
            self::Morrisons => 'Morrisons',
            self::Waitrose => 'Waitrose',
            self::Lidl => 'Lidl',
            self::Aldi => 'Aldi',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Tesco => '#e11530',
            self::Sainsburys => '#f06c00',
            self::Asda => '#7dc242',
            self::Morrisons => '#ffd400',
            self::Waitrose => '#5f8800',
            self::Lidl => '#f5c518',
            self::Aldi => '#1a73e8',
        };
    }

    public function initial(): string
    {
        return strtoupper($this->value[0]);
    }

    public function hasDarkText(): bool
    {
        return in_array($this, [self::Lidl, self::Morrisons], true);
    }
}
