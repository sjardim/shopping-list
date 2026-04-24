<?php

namespace App\Http\Controllers;

use App\Models\ShoppingList;
use Illuminate\View\View;

class ListPrintController extends Controller
{
    public function __invoke(string $share_token): View
    {
        $list = ShoppingList::with('items')
            ->where('share_token', $share_token)
            ->firstOrFail();

        $itemsByCategory = $list->items
            ->sortBy([['is_bought', 'asc'], ['category', 'asc'], ['name', 'asc']])
            ->groupBy('category');

        return view('list-print', [
            'list' => $list,
            'itemsByCategory' => $itemsByCategory,
        ]);
    }
}
