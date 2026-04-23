<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'emoji',
        'category',
        'preferred_store',
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
     * Search by name using case-insensitive matching (ILIKE for PostgreSQL).
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where('name', 'ILIKE', "%{$term}%")
            ->orderBy('name')
            ->limit(8);
    }

    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category)->orderBy('name');
    }
}
