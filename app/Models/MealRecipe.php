<?php

namespace App\Models;

use Database\Factories\MealRecipeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
