<?php

namespace Database\Seeders;

use App\Contracts\Store;
use App\Enums\ShoppingListStatus;
use App\Models\CatalogItem;
use App\Models\ShoppingList;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Shared logic for seeding a fake shopping history. Concrete subclasses
 * pick the regional store rotation and the locale-appropriate ad-hoc
 * (non-catalog) items.
 *
 * @see ShoppingHistorySeeder for the Portuguese variant.
 * @see ShoppingHistorySeederEn for the US variant.
 * @see ShoppingHistorySeederBr for the Brazilian variant.
 */
abstract class BaseShoppingHistorySeeder extends Seeder
{
    protected const ITEMS_PER_LIST_MIN = 8;

    protected const ITEMS_PER_LIST_MAX = 16;

    /** Probability that an item on a completed list was actually bought. */
    protected const BOUGHT_PROBABILITY = 0.9;

    /**
     * Trips spread across the past ~60 days, covering the region's stores.
     * Each entry is [Store $store, int $daysAgo].
     *
     * @return array<int, array{0: Store, 1: int}>
     */
    abstract protected function trips(): array;

    /**
     * Manual (non-catalog) items shoppers commonly add ad-hoc, in the
     * region's language.
     *
     * @return array<int, array{name: string, emoji: string, category: string, unit: string, quantity: float|int}>
     */
    abstract protected function manualItems(): array;

    public function run(): void
    {
        $admin = User::admins()->oldest('id')->first();

        if ($admin === null) {
            $this->command->warn('No admin user found — run AdminUserSeeder first, or `php artisan lista:install`.');

            return;
        }

        if ($this->adminHasCompletedLists($admin)) {
            $this->command->info('Shopping history already present for admin — skipping.');

            return;
        }

        $catalog = CatalogItem::all();

        if ($catalog->isEmpty()) {
            $this->command->warn('Catalog is empty — run a catalog seeder first.');

            return;
        }

        $this->buildHistoryFor($admin, $catalog);
    }

    private function adminHasCompletedLists(User $admin): bool
    {
        return ShoppingList::query()
            ->where('user_id', $admin->id)
            ->where('status', ShoppingListStatus::Completed)
            ->exists();
    }

    private function buildHistoryFor(User $admin, Collection $catalog): void
    {
        foreach ($this->trips() as [$store, $daysAgo]) {
            $completedAt = Carbon::now()->subDays($daysAgo)->setTime(18, mt_rand(0, 59));

            $list = ShoppingList::create([
                'user_id' => $admin->id,
                'name' => sprintf('%s · %s', $store->label(), $completedAt->format('d M')),
                'store' => $store->value,
                'status' => ShoppingListStatus::Completed->value,
                'share_token' => (string) Str::uuid(),
                'completed_at' => $completedAt,
            ]);

            $this->attachItems($list, $catalog, $store, $completedAt);
        }
    }

    private function attachItems(ShoppingList $list, Collection $catalog, Store $store, Carbon $completedAt): void
    {
        $picks = $this->pickCatalogItems($catalog, $store);

        foreach ($picks as $sortOrder => $catalogItem) {
            $isBought = mt_rand(1, 100) <= (int) (static::BOUGHT_PROBABILITY * 100);

            $list->items()->create([
                'catalog_item_id' => $catalogItem->id,
                'name' => $catalogItem->name,
                'emoji' => $catalogItem->emoji,
                'category' => $catalogItem->category,
                'quantity' => $catalogItem->default_quantity,
                'unit' => $catalogItem->default_unit,
                'preferred_store' => $catalogItem->preferred_store,
                'is_bought' => $isBought,
                'bought_at' => $isBought ? $completedAt : null,
                'sort_order' => $sortOrder,
            ]);
        }

        if (mt_rand(1, 100) <= 60) {
            $this->addManualItem($list, $completedAt, count($picks));
        }
    }

    /**
     * Pick a realistic basket: items that prefer this store first, then random fillers.
     */
    private function pickCatalogItems(Collection $catalog, Store $store): Collection
    {
        $count = mt_rand(static::ITEMS_PER_LIST_MIN, static::ITEMS_PER_LIST_MAX);

        $preferredHere = $catalog->where('preferred_store', $store->value);
        $others = $catalog->whereNotIn('id', $preferredHere->pluck('id'));

        return $preferredHere
            ->shuffle()
            ->take((int) ceil($count * 0.7))
            ->concat($others->shuffle()->take($count))
            ->take($count)
            ->values();
    }

    private function addManualItem(ShoppingList $list, Carbon $completedAt, int $sortOrder): void
    {
        $manuals = $this->manualItems();

        if ($manuals === []) {
            return;
        }

        $manual = $manuals[array_rand($manuals)];
        $isBought = mt_rand(1, 100) <= (int) (static::BOUGHT_PROBABILITY * 100);

        $list->items()->create([
            'catalog_item_id' => null,
            'name' => $manual['name'],
            'emoji' => $manual['emoji'],
            'category' => $manual['category'],
            'quantity' => $manual['quantity'],
            'unit' => $manual['unit'],
            'preferred_store' => null,
            'is_bought' => $isBought,
            'bought_at' => $isBought ? $completedAt : null,
            'sort_order' => $sortOrder,
        ]);
    }
}
