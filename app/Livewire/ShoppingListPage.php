<?php

namespace App\Livewire;

use App\Concerns\BroadcastsToOthers;
use App\Enums\ShoppingListStatus;
use App\Enums\Store;
use App\Events\ItemAdded;
use App\Events\ItemRemoved;
use App\Models\CatalogItem;
use App\Models\ShoppingList;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class ShoppingListPage extends Component
{
    use BroadcastsToOthers;

    public ShoppingList $list;

    public string $mode = 'owner';

    public string $shareToken = '';

    public string $quickAddName = '';

    public float $quickAddQuantity = 1;

    public string $quickAddUnit = 'un';

    public string $locale = 'en';

    public string $notes = '';

    public function mount(?string $share_token = null): void
    {
        if ($share_token !== null) {
            $this->list = ShoppingList::where('share_token', $share_token)->firstOrFail();
            $this->mode = 'shared';
            $this->shareToken = $share_token;
            $this->notes = (string) $this->list->notes;

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
        $this->notes = (string) $this->list->notes;
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

    #[Computed]
    public function catalogSuggestions(): array
    {
        if (strlen($this->quickAddName) < 2) {
            return [];
        }

        $existingCatalogIds = $this->list->items()
            ->whereNotNull('catalog_item_id')
            ->pluck('catalog_item_id')
            ->all();

        return CatalogItem::search($this->quickAddName)
            ->whereNotIn('id', $existingCatalogIds)
            ->get()
            ->toArray();
    }

    public function updatedQuickAddName(): void
    {
        unset($this->catalogSuggestions);
    }

    public function selectCatalogSuggestion(int $catalogItemId): void
    {
        if ($this->mode !== 'owner') {
            return;
        }

        $catalogItem = CatalogItem::findOrFail($catalogItemId);

        $item = $this->list->items()->create([
            'catalog_item_id' => $catalogItem->id,
            'name' => $catalogItem->name,
            'emoji' => $catalogItem->emoji,
            'category' => $catalogItem->category,
            'quantity' => $catalogItem->default_quantity,
            'unit' => $catalogItem->default_unit,
            'preferred_store' => $catalogItem->preferred_store,
        ]);

        $this->broadcastToOthers(new ItemAdded($item));

        $this->reset('quickAddName');
        unset($this->catalogSuggestions, $this->itemsByCategory);
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
        $this->broadcastToOthers(new ItemRemoved($item));
        $item->delete();

        unset($this->itemsByCategory);
    }

    public function quickAdd(): void
    {
        if ($this->mode !== 'owner') {
            return;
        }

        try {
            $this->validate(['quickAddName' => 'required|string|max:100']);
        } catch (ValidationException $e) {
            $this->dispatch('validation-failed');
            throw $e;
        }

        $item = $this->list->items()->create([
            'name' => trim($this->quickAddName),
            'quantity' => $this->quickAddQuantity,
            'unit' => $this->quickAddUnit,
        ]);

        $this->broadcastToOthers(new ItemAdded($item));

        $this->reset('quickAddName');
        $this->quickAddQuantity = 1;
        $this->quickAddUnit = 'un';

        unset($this->itemsByCategory);
    }

    private const UNDO_WINDOW_MINUTES = 5;

    private const TOAST_DURATION_MS = 8000;

    public function finishTrip(): void
    {
        if ($this->mode !== 'owner') {
            return;
        }

        $this->list->markCompleted();

        $this->list = ShoppingList::create(['user_id' => Auth::id()]);
        $this->shareToken = $this->list->share_token;

        unset($this->itemsByCategory, $this->recentlyFinishedList);

        $this->dispatch('trip-finished');

        Flux::toast(__('app.trip_done'), duration: self::TOAST_DURATION_MS);
    }

    #[Computed]
    public function recentlyFinishedList(): ?ShoppingList
    {
        if ($this->mode !== 'owner') {
            return null;
        }

        return ShoppingList::query()
            ->where('user_id', Auth::id())
            ->where('status', ShoppingListStatus::Completed)
            ->where('completed_at', '>=', now()->subMinutes(self::UNDO_WINDOW_MINUTES))
            ->latest('completed_at')
            ->first();
    }

    public function undoFinishTrip(): void
    {
        if ($this->mode !== 'owner') {
            return;
        }

        $previous = $this->recentlyFinishedList;

        if ($previous === null) {
            return;
        }

        $previous->update([
            'status' => ShoppingListStatus::Active,
            'completed_at' => null,
        ]);

        if ($this->list->id !== $previous->id && $this->list->items()->count() === 0) {
            $this->list->delete();
        }

        $this->list = $previous;
        $this->shareToken = $previous->share_token;

        unset($this->itemsByCategory, $this->recentlyFinishedList);

        $this->dispatch('trip-restored');

        Flux::toast(__('app.trip_restored'), duration: self::TOAST_DURATION_MS);
    }

    public function updateNotes(): void
    {
        $this->list->update(['notes' => trim($this->notes) ?: null]);
        $this->list->refresh();
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
        $this->onRemoteListUpdate();
    }

    #[On('echo:shopping.{shareToken},ItemAdded')]
    public function onItemAdded(array $payload): void
    {
        $this->onRemoteListUpdate();
    }

    #[On('echo:shopping.{shareToken},ItemRemoved')]
    public function onItemRemoved(array $payload): void
    {
        $this->onRemoteListUpdate();
    }

    private function onRemoteListUpdate(): void
    {
        unset($this->itemsByCategory);
        $this->dispatch('list-updated-remotely');
        Flux::toast(__('app.list_updated'), duration: self::TOAST_DURATION_MS);
    }

    public function render(): View
    {
        return view('livewire.shopping-list-page')
            ->layout('layouts.app', ['title' => $this->list->name]);
    }
}
