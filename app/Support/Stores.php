<?php

namespace App\Support;

use App\Contracts\Store;
use App\Enums\StorePt;
use App\Enums\StoreUk;
use App\Enums\StoreUs;

/**
 * Region-aware accessor for the regional Store enums.
 *
 * Picks the active region from config('lista.stores.region'). Other regional enums
 * stay loaded as a fallback so historical data with foreign slugs still resolves.
 */
class Stores
{
    /** @var array<string, class-string<Store>> */
    private const REGIONS = [
        'pt' => StorePt::class,
        'us' => StoreUs::class,
        'uk' => StoreUk::class,
    ];

    /**
     * Cases of the active region — what the store picker should show.
     *
     * @return array<int, Store>
     */
    public static function active(): array
    {
        $enum = self::activeEnum();

        return $enum::cases();
    }

    /**
     * Resolve a slug to a Store case. Prefers the active region; falls back to
     * any other known region so older lists don't lose their badge.
     */
    public static function tryFrom(?string $key): ?Store
    {
        if ($key === null || $key === '') {
            return null;
        }

        $active = self::activeEnum();

        if ($case = $active::tryFrom($key)) {
            return $case;
        }

        foreach (self::REGIONS as $enumClass) {
            if ($enumClass === $active) {
                continue;
            }

            if ($case = $enumClass::tryFrom($key)) {
                return $case;
            }
        }

        return null;
    }

    /** @return class-string<Store> */
    private static function activeEnum(): string
    {
        $region = config('lista.stores.region', 'pt');

        return self::REGIONS[$region] ?? StorePt::class;
    }
}
