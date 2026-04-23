<?php

namespace App\Livewire;

use App\Enums\Store;
use App\Events\ItemAdded;
use App\Events\ItemRemoved;
use App\Models\ShoppingList;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class ShoppingListPage extends Component
{
    public ShoppingList $list;

    public string $mode = 'owner';

    public string $shareToken = '';

    public string $quickAddName = '';

    public float $quickAddQuantity = 1;

    public string $quickAddUnit = 'un';

    public string $locale = 'en';

    public function mount(?string $share_token = null): void
    {
        if ($share_token !== null) {
            $this->list = ShoppingList::where('share_token', $share_token)->firstOrFail();
            $this->mode = 'shared';
            $this->shareToken = $share_token;

            return;
        }

        $this->locale = session('locale', config('app.locale'));

        $userId = Auth::id();

        $this->list = ShoppingList::where('user_id', $userId)
            ->active()
            ->latest()
            ->first()
            ?? ShoppingList::create(['user_id' => $userId]);

        $this->shareToken = $this->list->share_token;
    }

    #[Computed]
    public function itemsByCategory(): array
    {
        $items = $this->list->items()->get();

        $pending = $items
            ->where('is_bought', false)
            ->groupBy('category')
            ->toArray();

        $bought = $items
            ->where('is_bought', true)
            ->sortByDesc('bought_at')
            ->values()
            ->toArray();

        return compact('pending', 'bought');
    }

    public function toggleItem(int $id): void
    {
        $item = $this->list->items()->findOrFail($id);
        $item->toggleBought();

        unset($this->itemsByCategory);
    }

    public function removeItem(int $id): void
    {
        if ($this->mode !== 'owner') {
            return;
        }

        $item = $this->list->items()->findOrFail($id);
        broadcast(new ItemRemoved($item))->toOthers();
        $item->delete();

        unset($this->itemsByCategory);
    }

    public function quickAdd(): void
    {
        if ($this->mode !== 'owner') {
            return;
        }

        $this->validate(['quickAddName' => 'required|string|max:100']);

        $item = $this->list->items()->create([
            'name' => trim($this->quickAddName),
            'quantity' => $this->quickAddQuantity,
            'unit' => $this->quickAddUnit,
        ]);

        broadcast(new ItemAdded($item))->toOthers();

        $this->reset('quickAddName');
        $this->quickAddQuantity = 1;
        $this->quickAddUnit = 'un';

        unset($this->itemsByCategory);
    }

    public function finishTrip(): void
    {
        if ($this->mode !== 'owner') {
            return;
        }

        $this->list->markCompleted();

        $this->list = ShoppingList::create(['user_id' => Auth::id()]);
        $this->shareToken = $this->list->share_token;

        unset($this->itemsByCategory);

        Flux::toast(__('app.trip_done'));
    }

    public function clearList(): void
    {
        if ($this->mode !== 'owner') {
            return;
        }

        $this->list->items()->delete();

        unset($this->itemsByCategory);
    }

    public function switchLocale(string $locale): void
    {
        if (! in_array($locale, ['en', 'pt_PT'], strict: true)) {
            return;
        }

        session(['locale' => $locale]);
        $this->locale = $locale;

        $this->redirect(route('home'));
    }

    public function updateStore(string $store): void
    {
        if ($this->mode !== 'owner') {
            return;
        }

        $storeEnum = $store !== '' ? Store::from($store) : null;
        $date = now()->format('d M');

        $this->list->update([
            'store' => $storeEnum,
            'name' => $storeEnum !== null ? "{$storeEnum->label()} · {$date}" : "Shopping · {$date}",
        ]);

        $this->list->refresh();
    }

    #[On('echo:shopping.{shareToken},ItemToggled')]
    public function onItemToggled(array $payload): void
    {
        unset($this->itemsByCategory);
    }

    #[On('echo:shopping.{shareToken},ItemAdded')]
    public function onItemAdded(array $payload): void
    {
        unset($this->itemsByCategory);
    }

    #[On('echo:shopping.{shareToken},ItemRemoved')]
    public function onItemRemoved(array $payload): void
    {
        unset($this->itemsByCategory);
    }

    public function render(): View
    {
        return view('livewire.shopping-list-page')
            ->layout('layouts.app', ['title' => $this->list->name]);
    }
}
