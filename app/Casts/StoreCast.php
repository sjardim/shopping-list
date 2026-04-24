<?php

namespace App\Casts;

use App\Contracts\Store;
use App\Support\Stores;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * Resolves the slug stored in the database (e.g. "lidl", "walmart") to whichever
 * regional Store enum recognises it.
 */
class StoreCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?Store
    {
        return is_string($value) ? Stores::tryFrom($value) : null;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            return $value;
        }

        if ($value instanceof Store) {
            return $value->value;
        }

        return null;
    }
}
