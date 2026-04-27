<?php

declare(strict_types=1);

use App\Livewire\ListHistoryPage;
use App\Livewire\ShoppingListPage;
use App\Models\CatalogItem;
use App\Models\ShoppingList;
use App\Models\ShoppingListItem;
use App\Models\User;
use App\Services\PriceHistoryService;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;

uses(LazilyRefreshDatabase::class);

test('undo finish trip leaves the items findable in price history', function () {
    $user = User::factory()->create();
    $catalog = CatalogItem::factory()->create(['name' => 'Apples', 'locale' => 'pt_PT']);

    $list = ShoppingList::factory()->for($user)->create();
    $item = ShoppingListItem::factory()->for($list, 'list')->create([
        'catalog_item_id' => $catalog->id,
        'name' => 'Apples',
        'price' => 1.99,
        'is_bought' => true,
        'bought_at' => now()->subHour(),
    ]);

    $component = Livewire::actingAs($user)->test(ShoppingListPage::class);
    $component->call('finishTrip');
    $component->call('undoFinishTrip');

    $restored = $list->fresh();
    expect($restored->status->value)->toBe('active');

    // After undo, the same item is back in the active list. The history modal
    // should show its own past purchase rather than nothing.
    $history = app(PriceHistoryService::class)->forItem($item->fresh(), $user->id);

    expect($history)->not->toBeEmpty('Undo restored item must show its own previous price');
});

test('repeated list shows price history from the source trip', function () {
    $user = User::factory()->create();
    $catalog = CatalogItem::factory()->create(['name' => 'Bananas', 'locale' => 'pt_PT']);

    // First trip: buy bananas at €2.50, finish.
    $firstList = ShoppingList::factory()->for($user)->create();
    $firstItem = ShoppingListItem::factory()->for($firstList, 'list')->create([
        'catalog_item_id' => $catalog->id,
        'name' => 'Bananas',
        'price' => 2.50,
        'is_bought' => true,
        'bought_at' => now()->subHour(),
    ]);

    Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->call('finishTrip');

    expect($firstList->fresh()->status->value)->toBe('completed');

    // Repeat the list from history.
    Livewire::actingAs($user)
        ->test(ListHistoryPage::class)
        ->call('repeatList', $firstList->id);

    $repeated = ShoppingListItem::query()
        ->where('catalog_item_id', $catalog->id)
        ->where('id', '!=', $firstItem->id)
        ->firstOrFail();

    // The price modal query should surface the original purchase.
    $history = app(PriceHistoryService::class)->forItem($repeated, $user->id);

    expect($history)->toHaveCount(1)
        ->and($history->first()->price)->toBe(2.50);
});
