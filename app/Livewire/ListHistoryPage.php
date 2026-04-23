<?php

namespace App\Livewire;

use App\Models\ShoppingList;
use Flux\Flux;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ListHistoryPage extends Component
{
    #[Computed]
    public function completedLists(): Collection
    {
        return ShoppingList::where('user_id', Auth::id())
            ->completed()
            ->with('items')
            ->withCount(['items', 'items as bought_count' => fn ($q) => $q->where('is_bought', true)])
            ->get();
    }

    public function repeatList(int $id): void
    {
        $source = ShoppingList::where('user_id', Auth::id())->findOrFail($id);

        $userId = Auth::id();
        $newList = ShoppingList::where('user_id', $userId)
            ->active()
            ->latest()
            ->first()
            ?? ShoppingList::create(['user_id' => $userId]);

        $existingNames = $newList->items()->pluck('name')->all();

        foreach ($source->items as $item) {
            if (! in_array($item->name, $existingNames, true)) {
                $newList->items()->create([
                    'catalog_item_id' => $item->catalog_item_id,
                    'name' => $item->name,
                    'emoji' => $item->emoji,
                    'category' => $item->category,
                    'quantity' => $item->quantity,
                    'unit' => $item->unit,
                    'preferred_store' => $item->preferred_store,
                ]);
            }
        }

        Flux::toast('List items copied to your active list!');

        $this->redirect(route('home'));
    }

    public function deleteList(int $id): void
    {
        ShoppingList::where('user_id', Auth::id())->findOrFail($id)->delete();

        unset($this->completedLists);
    }

    public function render(): View
    {
        return view('livewire.list-history-page')
            ->layout('layouts.app', ['title' => 'History']);
    }
}
