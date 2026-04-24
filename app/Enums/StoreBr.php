<?php

namespace App\Enums;

use App\Contracts\Store;

enum StoreBr: string implements Store
{
    case Carrefour = 'carrefour';
    case PaoDeAcucar = 'pao_de_acucar';
    case Extra = 'extra';
    case Assai = 'assai';
    case Atacadao = 'atacadao';

    public function label(): string
    {
        return match ($this) {
            self::Carrefour => 'Carrefour',
            self::PaoDeAcucar => 'Pão de Açúcar',
            self::Extra => 'Extra',
            self::Assai => 'Assaí',
            self::Atacadao => 'Atacadão',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Carrefour => '#003876',
            self::PaoDeAcucar => '#ce0033',
            self::Extra => '#ffb81c',
            self::Assai => '#c80c0c',
            self::Atacadao => '#fcb017',
        };
    }

    public function initial(): string
    {
        return match ($this) {
            self::PaoDeAcucar => 'P',
            default => strtoupper($this->value[0]),
        };
    }

    public function hasDarkText(): bool
    {
        return in_array($this, [self::Extra, self::Atacadao], true);
    }
}
