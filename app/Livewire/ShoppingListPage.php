<?php

namespace App\Livewire;

use App\Concerns\BroadcastsToOthers;
use App\Contracts\Store;
use App\Enums\ShoppingListStatus;
use App\Events\ItemAdded;
use App\Events\ItemRemoved;
use App\Models\CatalogItem;
use App\Models\MealRecipe;
use App\Models\ShoppingList;
use App\Models\ShoppingListItem;
use App\Support\Stores;
use Flux\Flux;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * @property-read array{pending: array<string, array<int, array<string, mixed>>>, bought: array<int, array<string, mixed>>} $itemsByCategory
 * @property-read array<int, array<string, mixed>> $catalogSuggestions
 * @property-read ShoppingListItem|null $editingItem
 * @property-read Collection<int, object> $priceHistory
 * @property-read float $totalSpent
 * @property-read ShoppingList|null $recentlyFinishedList
 */
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

    public string $newRecipeName = '';

    public string $newRecipeEmoji = '🍽️';

    public ?int $editingItemId = null;

    public string $editingPrice = '';

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

        return ['pending' => $pending, 'bought' => $bought];
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

    public function setItemPrice(int $id, ?float $price): void
    {
        if ($this->mode !== 'owner') {
            return;
        }

        $item = $this->list->items()->findOrFail($id);
        $item->update(['price' => $price !== null && $price > 0 ? $price : null]);

        unset($this->itemsByCategory, $this->totalSpent);
    }

    public function openPriceEditor(int $id): void
    {
        if ($this->mode !== 'owner') {
            return;
        }

        $item = $this->list->items()->findOrFail($id);

        $this->editingItemId = $item->id;
        $this->editingPrice = $item->price !== null ? (string) $item->price : '';

        unset($this->editingItem, $this->priceHistory);

        Flux::modal('edit-price')->show();
    }

    public function submitPrice(): void
    {
        if ($this->mode !== 'owner' || $this->editingItemId === null) {
            return;
        }

        $normalised = trim(str_replace(',', '.', $this->editingPrice));
        $price = $normalised === '' ? null : (float) $normalised;

        $this->setItemPrice($this->editingItemId, $price);

        $this->reset('editingItemId', 'editingPrice');

        Flux::modal('edit-price')->close();
    }

    #[Computed]
    public function editingItem(): ?ShoppingListItem
    {
        if ($this->editingItemId === null) {
            return null;
        }

        return $this->list->items()->find($this->editingItemId);
    }

    /**
     * @return Collection<int, \stdClass>
     */
    #[Computed]
    public function priceHistory(): Collection
    {
        $item = $this->editingItem;

        if ($item === null || $item->catalog_item_id === null) {
            return collect();
        }

        return DB::table('shopping_list_items')
            ->select([
                'shopping_list_items.price',
                'shopping_list_items.bought_at',
                'shopping_lists.store',
                'shopping_lists.name as list_name',
            ])
            ->join('shopping_lists', 'shopping_lists.id', '=', 'shopping_list_items.shopping_list_id')
            ->where('shopping_lists.user_id', Auth::id())
            ->where('shopping_list_items.catalog_item_id', $item->catalog_item_id)
            ->where('shopping_list_items.id', '!=', $item->id)
            ->whereNotNull('shopping_list_items.price')
            ->where('shopping_list_items.is_bought', true)
            ->orderByDesc('shopping_list_items.bought_at')
            ->limit(10)
            ->get()
            ->map(fn ($row) => (object) [
                'store' => $row->store === null ? null : (string) $row->store,
                'price' => (float) $row->price,
                'bought_at' => Carbon::parse($row->bought_at),
                'list_name' => (string) $row->list_name,
            ]);
    }

    #[Computed]
    public function totalSpent(): float
    {
        return (float) $this->list->items()
            ->where('is_bought', true)
            ->whereNotNull('price')
            ->sum('price');
    }

    public function quickAdd(): void
    {
        if ($this->mode !== 'owner') {
            return;
        }

        try {
            $this->validate(['quickAddName' => 'required|string|max:100']);
        } catch (ValidationException $validationException) {
            $this->dispatch('validation-failed');
            throw $validationException;
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

    private const int UNDO_WINDOW_MINUTES = 5;

    private const int TOAST_DURATION_MS = 8000;

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

        $this->dispatch('notes-saved');
    }

    public function saveAsRecipe(): void
    {
        if ($this->mode !== 'owner') {
            return;
        }

        $this->validate([
            'newRecipeName' => 'required|string|max:60',
            'newRecipeEmoji' => 'required|string|max:8',
        ]);

        $items = $this->list->items()
            ->get(['name', 'quantity', 'unit'])
            ->map(fn ($item): array => [
                'name' => $item->name,
                'quantity' => (float) $item->quantity,
                'unit' => $item->unit,
            ])
            ->values()
            ->all();

        if (count($items) === 0) {
            return;
        }

        MealRecipe::create([
            'user_id' => Auth::id(),
            'name' => trim($this->newRecipeName),
            'emoji' => $this->newRecipeEmoji,
            'items' => $items,
        ]);

        $this->reset('newRecipeName', 'newRecipeEmoji');
        $this->newRecipeEmoji = '🍽️';

        Flux::modal('save-recipe')->close();
        Flux::toast(__('app.recipe_saved'), duration: 6000);
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
        if (! in_array($locale, ['en', 'en_GB', 'pt_PT', 'pt_BR'], strict: true)) {
            return;
        }

        session(['locale' => $locale]);
        session()->save();
        App::setLocale($locale);
        $this->locale = $locale;

        $this->redirect(route('home'));
    }

    public function updateStore(string $store): void
    {
        if ($this->mode !== 'owner') {
            return;
        }

        $storeEnum = Stores::tryFrom($store);
        $date = now()->format('d M');

        $this->list->update([
            'store' => $storeEnum,
            'name' => $storeEnum instanceof Store ? "{$storeEnum->label()} · {$date}" : "Shopping · {$date}",
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
