<?php

namespace App\Livewire;

use App\Events\ItemAdded;
use App\Events\ItemRemoved;
use App\Models\CatalogItem;
use App\Models\ShoppingList;
use App\Support\MealBundles;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

class AddItemsPage extends Component
{
    #[Url(as: 'q')]
    public string $searchQuery = '';

    public string $activeTab = 'suggested';

    /** @var array<int> */
    public array $selectedCatalogIds = [];

    public ShoppingList $activeList;

    public function mount(): void
    {
        $userId = Auth::id();

        $this->activeList = ShoppingList::where('user_id', $userId)
            ->active()
            ->latest()
            ->first()
            ?? ShoppingList::create(['user_id' => $userId]);

        // Pre-select items already on the list
        $this->selectedCatalogIds = $this->activeList
            ->items()
            ->whereNotNull('catalog_item_id')
            ->pluck('catalog_item_id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    #[Computed]
    public function groupedCatalogItems(): array
    {
        $query = CatalogItem::query()->orderBy('name');

        if ($this->searchQuery !== '') {
            $query->search($this->searchQuery);
        }

        return $query->get()
            ->groupBy('category')
            ->toArray();
    }

    #[Computed]
    public function mealBundles(): array
    {
        return MealBundles::all();
    }

    public function updatedSearchQuery(): void
    {
        unset($this->groupedCatalogItems);
    }

    public function toggleCatalogItem(int $id): void
    {
        if (in_array($id, $this->selectedCatalogIds, true)) {
            $listItem = $this->activeList->items()
                ->where('catalog_item_id', $id)
                ->first();

            if ($listItem) {
                broadcast(new ItemRemoved($listItem))->toOthers();
                $listItem->delete();
            }

            $this->selectedCatalogIds = array_values(
                array_filter($this->selectedCatalogIds, fn ($i) => $i !== $id)
            );
        } else {
            $catalogItem = CatalogItem::findOrFail($id);

            $item = $this->activeList->items()->create([
                'catalog_item_id' => $catalogItem->id,
                'name' => $catalogItem->name,
                'emoji' => $catalogItem->emoji,
                'category' => $catalogItem->category,
                'quantity' => $catalogItem->default_quantity,
                'unit' => $catalogItem->default_unit,
                'preferred_store' => $catalogItem->preferred_store,
            ]);

            broadcast(new ItemAdded($item))->toOthers();

            $this->selectedCatalogIds[] = $id;
        }
    }

    public function applyMealBundle(string $key): void
    {
        $bundle = MealBundles::get($key);

        if ($bundle === null) {
            return;
        }

        foreach ($bundle['items'] as $bundleItem) {
            $catalogItem = CatalogItem::where('name', 'ILIKE', $bundleItem['name'])->first();

            if ($catalogItem !== null) {
                if (! in_array($catalogItem->id, $this->selectedCatalogIds, true)) {
                    $this->selectedCatalogIds[] = $catalogItem->id;
                }
            } else {
                // Create the item directly on the list for non-catalog entries
                $item = $this->activeList->items()->create([
                    'name' => $bundleItem['name'],
                    'quantity' => $bundleItem['quantity'],
                    'unit' => $bundleItem['unit'],
                ]);

                broadcast(new ItemAdded($item))->toOthers();
            }
        }

        Flux::toast("Added {$bundle['name']} to your list!");
    }

    public function addToList(): void
    {
        $this->redirect(route('home'));
    }

    public function render(): View
    {
        return view('livewire.add-items-page')
            ->layout('layouts.app', ['title' => 'Add Items']);
    }
}
