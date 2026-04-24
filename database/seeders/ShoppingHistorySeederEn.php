<?php

namespace Database\Seeders;

use App\Enums\ShoppingListStatus;
use App\Enums\Store;
use App\Models\CatalogItem;
use App\Models\ShoppingList;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * English/US version of the shopping history seeder. Mirrors
 * ShoppingHistorySeeder but rotates US stores and uses English-language
 * manual items. Pairs with CatalogItemSeederEn.
 *
 * Run with: php artisan db:seed --class=ShoppingHistorySeederEn
 */
class ShoppingHistorySeederEn extends Seeder
{
    private const OWNER_EMAIL = 'sergio@sergiojardim.com';

    private const ITEMS_PER_LIST_MIN = 8;

    private const ITEMS_PER_LIST_MAX = 16;

    /** Probability that an item on a completed list was actually bought. */
    private const BOUGHT_PROBABILITY = 0.9;

    /** Manual (non-catalog) items shoppers commonly add ad-hoc. */
    private const MANUAL_ITEMS = [
        ['name' => 'Razor blades', 'emoji' => '🪒', 'category' => 'higiene', 'unit' => 'un', 'quantity' => 1],
        ['name' => 'AA batteries', 'emoji' => '🔋', 'category' => 'casa', 'unit' => 'pacote', 'quantity' => 1],
        ['name' => 'Birthday card', 'emoji' => '🎂', 'category' => 'casa', 'unit' => 'un', 'quantity' => 1],
        ['name' => 'Flowers', 'emoji' => '💐', 'category' => 'casa', 'unit' => 'un', 'quantity' => 1],
        ['name' => 'Bag of ice', 'emoji' => '🧊', 'category' => 'bebidas', 'unit' => 'kg', 'quantity' => 2],
    ];

    public function run(): void
    {
        $owner = User::where('email', self::OWNER_EMAIL)->first();

        if ($owner === null) {
            $this->command->warn(sprintf('Owner user %s not found — run DatabaseSeeder first.', self::OWNER_EMAIL));

            return;
        }

        if ($this->ownerHasCompletedLists($owner)) {
            $this->command->info('Shopping history already present for owner — skipping.');

            return;
        }

        $catalog = CatalogItem::all();

        if ($catalog->isEmpty()) {
            $this->command->warn('Catalog is empty — run CatalogItemSeederEn first.');

            return;
        }

        $this->buildHistoryFor($owner, $catalog);
    }

    private function ownerHasCompletedLists(User $owner): bool
    {
        return ShoppingList::query()
            ->where('user_id', $owner->id)
            ->where('status', ShoppingListStatus::Completed)
            ->exists();
    }

    private function buildHistoryFor(User $owner, Collection $catalog): void
    {
        foreach ($this->trips() as [$store, $daysAgo]) {
            $completedAt = Carbon::now()->subDays($daysAgo)->setTime(18, mt_rand(0, 59));

            $list = ShoppingList::create([
                'user_id' => $owner->id,
                'name' => sprintf('%s · %s', $store->label(), $completedAt->format('d M')),
                'store' => $store->value,
                'status' => ShoppingListStatus::Completed->value,
                'share_token' => (string) Str::uuid(),
                'completed_at' => $completedAt,
            ]);

            $this->attachItems($list, $catalog, $store, $completedAt);
        }
    }

    /**
     * Trips spread across the past ~60 days, covering all four US stores.
     *
     * @return array<int, array{0: Store, 1: int}>
     */
    private function trips(): array
    {
        return [
            [Store::Walmart, 2],
            [Store::Target, 5],
            [Store::TraderJoes, 9],
            [Store::WholeFoods, 13],
            [Store::Walmart, 17],
            [Store::TraderJoes, 23],
            [Store::Target, 28],
            [Store::Walmart, 35],
            [Store::TraderJoes, 42],
            [Store::WholeFoods, 50],
            [Store::Walmart, 58],
        ];
    }

    private function attachItems(ShoppingList $list, Collection $catalog, Store $store, Carbon $completedAt): void
    {
        $picks = $this->pickCatalogItems($catalog, $store);

        foreach ($picks as $sortOrder => $catalogItem) {
            $isBought = mt_rand(1, 100) <= (int) (self::BOUGHT_PROBABILITY * 100);

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
        $count = mt_rand(self::ITEMS_PER_LIST_MIN, self::ITEMS_PER_LIST_MAX);

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
        $manual = self::MANUAL_ITEMS[array_rand(self::MANUAL_ITEMS)];
        $isBought = mt_rand(1, 100) <= (int) (self::BOUGHT_PROBABILITY * 100);

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
