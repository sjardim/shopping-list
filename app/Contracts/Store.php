<?php

namespace App\Contracts;

/**
 * Common shape every regional store enum (StorePt, StoreUs, StoreUk, …) implements.
 *
 * Backed enums automatically expose ->value (the slug stored in the database),
 * so this contract only covers the display methods used by views and badges.
 */
interface Store
{
    public function label(): string;

    public function color(): string;

    public function initial(): string;

    public function hasDarkText(): bool;
}
