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
        $activeList = ShoppingList::where('user_id', $userId)
            ->active()
            ->latest()
            ->first()
            ?? ShoppingList::create(['user_id' => $userId]);

        $existingByName = $activeList->items()->get()->keyBy('name');

        $added = 0;
        $restored = 0;
        $skipped = 0;

        foreach ($source->items as $item) {
            $existing = $existingByName->get($item->name);

            if ($existing === null) {
                $activeList->items()->create([
                    'catalog_item_id' => $item->catalog_item_id,
                    'name' => $item->name,
                    'emoji' => $item->emoji,
                    'category' => $item->category,
                    'quantity' => $item->quantity,
                    'unit' => $item->unit,
                    'preferred_store' => $item->preferred_store,
                ]);
                $added++;

                continue;
            }

            if ($existing->is_bought) {
                $existing->update(['is_bought' => false, 'bought_at' => null]);
                $restored++;

                continue;
            }

            $skipped++;
        }

        Flux::toast(__('app.repeat_summary', [
            'added' => $added,
            'restored' => $restored,
            'skipped' => $skipped,
        ]), duration: 8000);

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
