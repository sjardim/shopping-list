<?php

namespace App\Models;

use App\Events\ItemToggled;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShoppingListItem extends Model
{
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
        'is_bought',
        'bought_at',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'is_bought' => 'boolean',
            'bought_at' => 'datetime',
        ];
    }

    public function list(): BelongsTo
    {
        return $this->belongsTo(ShoppingList::class, 'shopping_list_id');
    }

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

        broadcast(new ItemToggled($this))->toOthers();
    }
}
