<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use App\Events\ItemQuantityChanged;
use App\Livewire\ShoppingListPage;

/**
 * Quantity stepper actions for shopping list items. Increment and decrement
 * adjust by a unit-aware step (0.1 for kg/l, 50 for g/ml, 1 for everything
 * else). Decrement clamps at the step so a row can never reach zero.
 *
 * @phpstan-require-extends ShoppingListPage
 */
trait HandlesQuantity
{
    public function incrementQuantity(int $id): void
    {
        $this->adjustQuantity($id, 1);
    }

    public function decrementQuantity(int $id): void
    {
        $this->adjustQuantity($id, -1);
    }

    private function adjustQuantity(int $id, int $direction): void
    {
        if ($this->mode !== 'owner') {
            return;
        }

        $item = $this->list->items()->findOrFail($id);
        $step = $this->quantityStep((string) $item->unit);
        $next = round((float) $item->quantity + ($direction * $step), 2);

        if ($next < $step) {
            return;
        }

        $item->update(['quantity' => $next]);

        $this->broadcastToOthers(new ItemQuantityChanged($item));

        unset($this->itemsByCategory);
    }

    private function quantityStep(string $unit): float
    {
        return match (strtolower($unit)) {
            'kg', 'l' => 0.1,
            'g', 'ml' => 50,
            default => 1,
        };
    }
}
