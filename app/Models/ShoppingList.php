<?php

namespace App\Models;

use App\Casts\StoreCast;
use App\Contracts\Store;
use App\Enums\ShoppingListStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property Store|null $store
 * @property string|null $notes
 * @property ShoppingListStatus $status
 * @property string $share_token
 * @property Carbon|null $completed_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $user
 * @property-read Collection<int, ShoppingListItem> $items
 */
class ShoppingList extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'store',
        'notes',
        'status',
        'share_token',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => ShoppingListStatus::class,
            'store' => StoreCast::class,
            'completed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ShoppingList $list): void {
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

        if ($store instanceof Store) {
            return "{$store->label()} · {$date}";
        }

        return "Shopping · {$date}";
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<ShoppingListItem, $this>
     */
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
