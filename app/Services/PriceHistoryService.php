<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ShoppingListItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PriceHistoryService
{
    /**
     * Recent confirmed prices for the same catalog item across the user's
     * past lists, ordered most-recent first.
     *
     * @return Collection<int, \stdClass>
     */
    public function forItem(ShoppingListItem $item, int $userId, int $limit = 10): Collection
    {
        if ($item->catalog_item_id === null) {
            return collect();
        }

        return DB::table('shopping_list_items')
            ->select([
                'shopping_list_items.price',
                'shopping_list_items.bought_at',
                'shopping_list_items.updated_at',
                'shopping_lists.store',
                'shopping_lists.name as list_name',
            ])
            ->join('shopping_lists', 'shopping_lists.id', '=', 'shopping_list_items.shopping_list_id')
            ->where('shopping_lists.user_id', $userId)
            ->where('shopping_list_items.catalog_item_id', $item->catalog_item_id)
            ->whereNotNull('shopping_list_items.price')
            ->orderByDesc(DB::raw('COALESCE(shopping_list_items.bought_at, shopping_list_items.updated_at)'))
            ->limit($limit)
            ->get()
            ->map(fn ($row) => (object) [
                'store' => $row->store === null ? null : (string) $row->store,
                'price' => (float) $row->price,
                'bought_at' => Carbon::parse($row->bought_at ?? $row->updated_at),
                'list_name' => (string) $row->list_name,
            ]);
    }
}
