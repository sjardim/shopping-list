<?php

namespace App\Http\Controllers;

use App\Models\ShoppingList;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ListExportController extends Controller
{
    public function __invoke(string $share_token): JsonResponse
    {
        $list = ShoppingList::with('items')
            ->where('share_token', $share_token)
            ->firstOrFail();

        $payload = [
            'name' => $list->name,
            'store' => $list->store?->value,
            'status' => $list->status->value,
            'notes' => $list->notes,
            'created_at' => $list->created_at?->toIso8601String(),
            'completed_at' => $list->completed_at?->toIso8601String(),
            'items' => $list->items->map(fn ($item) => [
                'name' => $item->name,
                'emoji' => $item->emoji,
                'category' => $item->category,
                'quantity' => (float) $item->quantity,
                'unit' => $item->unit,
                'preferred_store' => $item->preferred_store,
                'is_bought' => (bool) $item->is_bought,
                'bought_at' => $item->bought_at?->toIso8601String(),
            ])->all(),
        ];

        return response()
            ->json($payload, options: JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            ->header('Content-Disposition', sprintf(
                'attachment; filename="%s.json"',
                Str::slug($list->name) ?: 'shopping-list'
            ));
    }
}
