<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CatalogItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'emoji',
        'category',
        'preferred_store',
        'locale',
        'default_unit',
        'default_quantity',
    ];

    protected function casts(): array
    {
        return [
            'default_quantity' => 'decimal:2',
        ];
    }

    /**
     * Case-insensitive name search scoped to the current app locale.
     * Uses ILIKE on PostgreSQL, LIKE on SQLite.
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        $operator = DB::getDriverName() === 'pgsql' ? 'ILIKE' : 'LIKE';

        return $query->where('name', $operator, "%{$term}%")
            ->forLocale(app()->getLocale())
            ->orderBy('name')
            ->limit(8);
    }

    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category)
            ->forLocale(app()->getLocale())
            ->orderBy('name');
    }

    public function scopeForLocale(Builder $query, string $locale): Builder
    {
        return $query->where('locale', $locale);
    }

    /**
     * Update preferred_store if this item has been bought at the given store
     * more than 3 times across all shopping lists.
     */
    public function syncPreferredStore(string $store): void
    {
        $count = ShoppingListItem::query()
            ->where('catalog_item_id', $this->id)
            ->where('is_bought', true)
            ->whereHas('list', fn (Builder $q) => $q->where('store', $store))
            ->count();

        if ($count > 3) {
            $this->update(['preferred_store' => $store]);
        }
    }
}
