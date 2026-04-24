<?php

namespace App\Models;

use Database\Factories\MealRecipeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $emoji
 * @property array<int, array{name: string, quantity: float|int, unit: string}> $items
 * @property-read User $user
 */
class MealRecipe extends Model
{
    /** @use HasFactory<MealRecipeFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'emoji',
        'items',
    ];

    protected function casts(): array
    {
        return [
            'items' => 'array',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
