<?php

namespace App\Models;

use App\Enums\ShoppingListStatus;
use App\Enums\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ShoppingList extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'store',
        'status',
        'share_token',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => ShoppingListStatus::class,
            'store' => Store::class,
            'completed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ShoppingList $list) {
            if (empty($list->share_token)) {
                $list->share_token = (string) Str::uuid();
            }

            if (empty($list->name)) {
                $list->name = self::generateName($list->store);
            }
        });
    }

    private static function generateName(?Store $store): string
    {
        $date = now()->format('d M');

        if ($store !== null) {
            return "{$store->label()} · {$date}";
        }

        return "Shopping · {$date}";
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ShoppingListItem::class)
            ->orderBy('sort_order')
            ->orderBy('name');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', ShoppingListStatus::Active);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', ShoppingListStatus::Completed)
            ->orderByDesc('completed_at');
    }

    public function markCompleted(): void
    {
        $this->update([
            'status' => ShoppingListStatus::Completed,
            'completed_at' => now(),
        ]);
    }

    public function pendingCount(): int
    {
        return $this->items()->where('is_bought', false)->count();
    }

    public function totalCount(): int
    {
        return $this->items()->count();
    }
}
