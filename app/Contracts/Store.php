<?php

declare(strict_types=1);

namespace App\Contracts;

/**
 * Common shape every regional store enum (StorePt, StoreUs, StoreUk, …) implements.
 *
 * Backed enums automatically expose ->value (the slug stored in the database)
 * and the static cases()/from()/tryFrom() helpers. The PHPDoc below documents
 * those for static analysis since interfaces can't declare them directly.
 *
 * @property string $value
 *
 * @method static static[] cases()
 * @method static static from(string $value)
 * @method static static|null tryFrom(string $value)
 */
interface Store
{
    public function label(): string;

    public function color(): string;

    public function initial(): string;

    public function hasDarkText(): bool;
}
