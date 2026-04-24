<?php

namespace Database\Seeders;

use App\Contracts\Store;
use App\Enums\ShoppingListStatus;
use App\Enums\StorePt;
use App\Models\CatalogItem;
use App\Models\ShoppingList;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ShoppingHistorySeeder extends Seeder
{
    private const ITEMS_PER_LIST_MIN = 8;

    private const ITEMS_PER_LIST_MAX = 16;

    /** Probability that an item on a completed list was actually bought. */
    private const BOUGHT_PROBABILITY = 0.9;

    /** Manual (non-catalog) items shoppers commonly add ad-hoc. */
    private const MANUAL_ITEMS = [
        ['name' => 'Lâminas de barbear', 'emoji' => '🪒', 'category' => 'higiene', 'unit' => 'un', 'quantity' => 1],
        ['name' => 'Pilhas AA', 'emoji' => '🔋', 'category' => 'casa', 'unit' => 'pacote', 'quantity' => 1],
        ['name' => 'Cartão de aniversário', 'emoji' => '🎂', 'category' => 'casa', 'unit' => 'un', 'quantity' => 1],
        ['name' => 'Flores', 'emoji' => '💐', 'category' => 'casa', 'unit' => 'un', 'quantity' => 1],
        ['name' => 'Gelo', 'emoji' => '🧊', 'category' => 'bebidas', 'unit' => 'kg', 'quantity' => 2],
    ];

    public function run(): void
    {
        $email = config('lista.owner.email');
        $owner = User::where('email', $email)->first();

        if ($owner === null) {
            $this->command->warn(sprintf('Owner user %s not found — run DatabaseSeeder first.', $email));

            return;
        }

        if ($this->ownerHasCompletedLists($owner)) {
            $this->command->info('Shopping history already present for owner — skipping.');

            return;
        }

        $catalog = CatalogItem::all();

        if ($catalog->isEmpty()) {
            $this->command->warn('Catalog is empty — run CatalogItemSeeder first.');

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
     * Trips spread across the past ~60 days, covering all four stores.
     *
     * @return array<int, array{0: Store, 1: int}>
     */
    private function trips(): array
    {
        return [
            [StorePt::Continente, 2],
            [StorePt::Lidl, 5],
            [StorePt::Mercadona, 9],
            [StorePt::Aldi, 13],
            [StorePt::Continente, 17],
            [StorePt::Mercadona, 23],
            [StorePt::Lidl, 28],
            [StorePt::Continente, 35],
            [StorePt::Mercadona, 42],
            [StorePt::Aldi, 50],
            [StorePt::Continente, 58],
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
