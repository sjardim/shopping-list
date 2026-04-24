<?php

namespace App\Models;

use App\Concerns\BroadcastsToOthers;
use App\Events\ItemToggled;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $shopping_list_id
 * @property int|null $catalog_item_id
 * @property string $name
 * @property string|null $emoji
 * @property string|null $category
 * @property string $quantity
 * @property string $unit
 * @property string|null $preferred_store
 * @property string|null $price
 * @property bool $is_bought
 * @property Carbon|null $bought_at
 * @property int $sort_order
 * @property-read ShoppingList $list
 * @property-read CatalogItem|null $catalogItem
 */
class ShoppingListItem extends Model
{
    use BroadcastsToOthers;
    use HasFactory;

    protected $fillable = [
        'shopping_list_id',
        'catalog_item_id',
        'name',
        'emoji',
        'category',
        'quantity',
        'unit',
        'preferred_store',
        'price',
        'is_bought',
        'bought_at',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'price' => 'decimal:2',
            'is_bought' => 'boolean',
            'bought_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<ShoppingList, $this>
     */
    public function list(): BelongsTo
    {
        return $this->belongsTo(ShoppingList::class, 'shopping_list_id');
    }

    /**
     * @return BelongsTo<CatalogItem, $this>
     */
    public function catalogItem(): BelongsTo
    {
        return $this->belongsTo(CatalogItem::class);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('is_bought', false);
    }

    public function scopeBought(Builder $query): Builder
    {
        return $query->where('is_bought', true)->orderByDesc('bought_at');
    }

    public function toggleBought(): void
    {
        $wasBought = $this->is_bought;

        $this->update([
            'is_bought' => ! $wasBought,
            'bought_at' => $wasBought ? null : now(),
        ]);

        if (! $wasBought && $this->catalog_item_id !== null) {
            $store = $this->list->store;

            if ($store !== null) {
                $this->catalogItem->syncPreferredStore($store->value);
            }
        }

        $this->broadcastToOthers(new ItemToggled($this));
    }
}
