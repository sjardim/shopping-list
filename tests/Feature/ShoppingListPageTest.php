<?php

use App\Enums\StorePt;
use App\Livewire\ShoppingListPage;
use App\Models\CatalogItem;
use App\Models\MealRecipe;
use App\Models\ShoppingList;
use App\Models\ShoppingListItem;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;

uses(LazilyRefreshDatabase::class);

// — Owner mode —

test('owner mode loads the active list', function () {
    $user = User::factory()->create();
    $list = ShoppingList::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->assertSet('mode', 'owner')
        ->assertSet('list.id', $list->id);
});

test('creates a new active list if none exists', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->assertSet('mode', 'owner');

    expect(ShoppingList::where('user_id', $user->id)->count())->toBe(1);
});

test('owner can toggle an item to bought', function () {
    $user = User::factory()->create();
    $list = ShoppingList::factory()->for($user)->create();
    $item = ShoppingListItem::factory()->for($list, 'list')->create(['is_bought' => false]);

    Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->call('toggleItem', $item->id);

    expect($item->fresh()->is_bought)->toBeTrue();
});

test('owner can toggle a bought item back to pending', function () {
    $user = User::factory()->create();
    $list = ShoppingList::factory()->for($user)->create();
    $item = ShoppingListItem::factory()->for($list, 'list')->bought()->create();

    Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->call('toggleItem', $item->id);

    expect($item->fresh()->is_bought)->toBeFalse();
    expect($item->fresh()->bought_at)->toBeNull();
});

test('owner can remove an item', function () {
    $user = User::factory()->create();
    $list = ShoppingList::factory()->for($user)->create();
    $item = ShoppingListItem::factory()->for($list, 'list')->create();

    Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->call('removeItem', $item->id);

    $this->assertModelMissing($item);
});

test('owner can quick-add an item', function () {
    $user = User::factory()->create();
    ShoppingList::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->set('quickAddName', 'Bananas')
        ->set('quickAddQuantity', 2)
        ->set('quickAddUnit', 'kg')
        ->call('quickAdd');

    $item = ShoppingListItem::where('name', 'Bananas')->first();

    expect($item)->not->toBeNull()
        ->and($item->quantity)->toBe('2.00')
        ->and($item->unit)->toBe('kg');
});

test('quick-add requires a name', function () {
    $user = User::factory()->create();
    ShoppingList::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->set('quickAddName', '')
        ->call('quickAdd')
        ->assertHasErrors(['quickAddName' => 'required']);
});

test('owner can finish trip and create new list', function () {
    $user = User::factory()->create();
    $list = ShoppingList::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->call('finishTrip');

    expect($list->fresh()->status->value)->toBe('completed')
        ->and(ShoppingList::where('user_id', $user->id)->active()->count())->toBe(1);
});

test('finish trip dispatches a trip-finished browser event', function () {
    $user = User::factory()->create();
    ShoppingList::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->call('finishTrip')
        ->assertDispatched('trip-finished');
});

test('recently finished list is exposed when finished within the undo window', function () {
    $user = User::factory()->create();
    ShoppingList::factory()->for($user)->create();

    $component = Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->call('finishTrip');

    expect($component->instance()->recentlyFinishedList)->not->toBeNull();
});

test('recently finished list is null when last finish is older than the undo window', function () {
    $user = User::factory()->create();
    ShoppingList::factory()->for($user)->completed()->create([
        'completed_at' => now()->subMinutes(10),
    ]);

    $component = Livewire::actingAs($user)->test(ShoppingListPage::class);

    expect($component->instance()->recentlyFinishedList)->toBeNull();
});

test('undo finish trip restores the recently completed list as active', function () {
    $user = User::factory()->create();
    $previous = ShoppingList::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->call('finishTrip')
        ->call('undoFinishTrip');

    expect($previous->fresh()->status->value)->toBe('active');
});

test('undo finish trip dispatches a trip-restored event', function () {
    $user = User::factory()->create();
    ShoppingList::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->call('finishTrip')
        ->call('undoFinishTrip')
        ->assertDispatched('trip-restored');
});

test('quick add dispatches validation-failed when the name is empty', function () {
    $user = User::factory()->create();
    ShoppingList::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->set('quickAddName', '')
        ->call('quickAdd')
        ->assertDispatched('validation-failed')
        ->assertHasErrors(['quickAddName']);
});

test('undo finish trip discards the empty new active list', function () {
    $user = User::factory()->create();
    ShoppingList::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->call('finishTrip')
        ->call('undoFinishTrip');

    expect(ShoppingList::where('user_id', $user->id)->active()->count())->toBe(1);
});

test('undo finish trip preserves the new active list if it has items', function () {
    $user = User::factory()->create();
    ShoppingList::factory()->for($user)->create();

    $component = Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->call('finishTrip');

    $newList = ShoppingList::where('user_id', $user->id)->active()->latest()->first();
    ShoppingListItem::factory()->for($newList, 'list')->create();

    $component->call('undoFinishTrip');

    expect(ShoppingList::where('user_id', $user->id)->active()->count())->toBe(2);
});

test('undo finish trip does nothing when no recently finished list exists', function () {
    $user = User::factory()->create();
    ShoppingList::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->call('undoFinishTrip');

    expect(ShoppingList::where('user_id', $user->id)->active()->count())->toBe(1);
});

test('shared mode cannot undo finish trip', function () {
    $owner = User::factory()->create();
    $list = ShoppingList::factory()->for($owner)->completed()->create([
        'completed_at' => now()->subMinute(),
    ]);

    Livewire::test(ShoppingListPage::class, ['share_token' => $list->share_token])
        ->call('undoFinishTrip');

    expect($list->fresh()->status->value)->toBe('completed');
});

test('owner mode renders the voice-input button', function () {
    $user = User::factory()->create();
    ShoppingList::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->assertSeeHtml('data-voice-toggle');
});

test('opening the price editor loads current price into editing state', function () {
    $user = User::factory()->create();
    $list = ShoppingList::factory()->for($user)->create();
    $item = ShoppingListItem::factory()->for($list, 'list')->create(['price' => 2.45]);

    Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->call('openPriceEditor', $item->id)
        ->assertSet('editingItemId', $item->id)
        ->assertSet('editingPrice', '2.45');
});

test('submitting the price editor persists the value and resets state', function () {
    $user = User::factory()->create();
    $list = ShoppingList::factory()->for($user)->create();
    $item = ShoppingListItem::factory()->for($list, 'list')->create(['price' => null]);

    Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->call('openPriceEditor', $item->id)
        ->set('editingPrice', '3,75')
        ->call('submitPrice')
        ->assertSet('editingItemId', null);

    expect((float) $item->fresh()->price)->toBe(3.75);
});

test('price history returns prior bought entries for the same catalog item across stores', function () {
    $user = User::factory()->create();
    $catalogItem = CatalogItem::factory()->create();

    $oldList = ShoppingList::factory()->for($user)->completed()->create([
        'store' => 'lidl',
        'completed_at' => now()->subDays(10),
    ]);
    ShoppingListItem::factory()->for($oldList, 'list')->bought()->create([
        'catalog_item_id' => $catalogItem->id,
        'price' => 2.50,
        'bought_at' => now()->subDays(10),
    ]);

    $recentList = ShoppingList::factory()->for($user)->completed()->create([
        'store' => 'continente',
        'completed_at' => now()->subDays(2),
    ]);
    ShoppingListItem::factory()->for($recentList, 'list')->bought()->create([
        'catalog_item_id' => $catalogItem->id,
        'price' => 2.95,
        'bought_at' => now()->subDays(2),
    ]);

    $activeList = ShoppingList::factory()->for($user)->create();
    $current = ShoppingListItem::factory()->for($activeList, 'list')->create([
        'catalog_item_id' => $catalogItem->id,
        'price' => null,
    ]);

    $component = Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->call('openPriceEditor', $current->id);

    $history = $component->instance()->priceHistory;

    expect($history)->toHaveCount(2);
    expect($history->first()->store)->toBe('continente');
    expect($history->first()->price)->toBe(2.95);
    expect($history->last()->store)->toBe('lidl');
});

test('owner can set a price on a bought item', function () {
    $user = User::factory()->create();
    $list = ShoppingList::factory()->for($user)->create();
    $item = ShoppingListItem::factory()->for($list, 'list')->bought()->create();

    Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->call('setItemPrice', $item->id, 4.5);

    expect((float) $item->fresh()->price)->toBe(4.5);
});

test('total spent sums the prices of bought items only', function () {
    $user = User::factory()->create();
    $list = ShoppingList::factory()->for($user)->create();
    ShoppingListItem::factory()->for($list, 'list')->bought()->create(['price' => 3.20]);
    ShoppingListItem::factory()->for($list, 'list')->bought()->create(['price' => 1.80]);
    ShoppingListItem::factory()->for($list, 'list')->create(['price' => 99.99, 'is_bought' => false]);

    $component = Livewire::actingAs($user)->test(ShoppingListPage::class);

    expect($component->instance()->totalSpent)->toBe(5.0);
});

test('owner can save the current list as a recipe', function () {
    $user = User::factory()->create();
    $list = ShoppingList::factory()->for($user)->create();
    ShoppingListItem::factory()->for($list, 'list')->create([
        'name' => 'Bacalhau',
        'quantity' => 1,
        'unit' => 'kg',
    ]);
    ShoppingListItem::factory()->for($list, 'list')->create([
        'name' => 'Batata',
        'quantity' => 2,
        'unit' => 'kg',
    ]);

    Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->set('newRecipeName', 'Bacalhau de Domingo')
        ->set('newRecipeEmoji', '🐟')
        ->call('saveAsRecipe');

    $recipe = MealRecipe::where('user_id', $user->id)->first();
    expect($recipe)->not->toBeNull();
    expect($recipe->name)->toBe('Bacalhau de Domingo');
    expect($recipe->emoji)->toBe('🐟');
    expect($recipe->items)->toHaveCount(2);
    expect($recipe->items[0]['name'])->toBe('Bacalhau');
});

test('owner can save notes on the list', function () {
    $user = User::factory()->create();
    $list = ShoppingList::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->set('notes', 'Buy from butcher counter, not aisle')
        ->call('updateNotes')
        ->assertDispatched('notes-saved');

    expect($list->fresh()->notes)->toBe('Buy from butcher counter, not aisle');
});

test('owner can clear all items from list', function () {
    $user = User::factory()->create();
    $list = ShoppingList::factory()->for($user)->create();
    ShoppingListItem::factory()->count(3)->for($list, 'list')->create();

    Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->call('clearList');

    expect($list->fresh()->items()->count())->toBe(0);
});

test('owner can update store', function () {
    $user = User::factory()->create();
    ShoppingList::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->call('updateStore', 'lidl');

    $list = ShoppingList::where('user_id', $user->id)->active()->first();

    expect($list->store)->toBe(StorePt::Lidl);
});

test('owner can clear store', function () {
    $user = User::factory()->create();
    ShoppingList::factory()->for($user)->forStore(StorePt::Continente)->create();

    Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->call('updateStore', '');

    $list = ShoppingList::where('user_id', $user->id)->active()->first();

    expect($list->store)->toBeNull();
});

// — Shared mode —

test('shared mode loads list by share token', function () {
    $user = User::factory()->create();
    $list = ShoppingList::factory()->for($user)->create();

    Livewire::test(ShoppingListPage::class, ['share_token' => $list->share_token])
        ->assertSet('mode', 'shared')
        ->assertSet('list.id', $list->id);
});

test('shared mode returns 404 for unknown token', function () {
    $this->get(route('list.shared', 'non-existent-token'))
        ->assertNotFound();
});

test('shared mode can toggle items', function () {
    $user = User::factory()->create();
    $list = ShoppingList::factory()->for($user)->create();
    $item = ShoppingListItem::factory()->for($list, 'list')->create(['is_bought' => false]);

    Livewire::test(ShoppingListPage::class, ['share_token' => $list->share_token])
        ->call('toggleItem', $item->id);

    expect($item->fresh()->is_bought)->toBeTrue();
});

test('shared mode cannot remove items', function () {
    $user = User::factory()->create();
    $list = ShoppingList::factory()->for($user)->create();
    $item = ShoppingListItem::factory()->for($list, 'list')->create();

    Livewire::test(ShoppingListPage::class, ['share_token' => $list->share_token])
        ->call('removeItem', $item->id);

    $this->assertModelExists($item);
});

test('shared mode cannot finish trip', function () {
    $user = User::factory()->create();
    $list = ShoppingList::factory()->for($user)->create();

    Livewire::test(ShoppingListPage::class, ['share_token' => $list->share_token])
        ->call('finishTrip');

    expect($list->fresh()->status->value)->toBe('active');
});

test('catalog suggestions returns matches for query of 2+ chars', function () {
    $user = User::factory()->create();
    ShoppingList::factory()->for($user)->create();

    CatalogItem::factory()->create(['name' => 'Banana']);
    CatalogItem::factory()->create(['name' => 'Bacalhau']);
    CatalogItem::factory()->create(['name' => 'Leite']);

    $component = Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->set('quickAddName', 'ba');

    expect($component->instance()->catalogSuggestions)
        ->toHaveCount(2)
        ->each->toHaveKey('name');
});

test('catalog suggestions returns nothing for query under 2 chars', function () {
    $user = User::factory()->create();
    ShoppingList::factory()->for($user)->create();
    CatalogItem::factory()->create(['name' => 'Banana']);

    $component = Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->set('quickAddName', 'b');

    expect($component->instance()->catalogSuggestions)->toBeEmpty();
});

test('catalog suggestions excludes items already on the list', function () {
    $user = User::factory()->create();
    $list = ShoppingList::factory()->for($user)->create();
    $banana = CatalogItem::factory()->create(['name' => 'Banana']);
    ShoppingListItem::factory()->for($list, 'list')->create(['catalog_item_id' => $banana->id]);

    $component = Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->set('quickAddName', 'ban');

    expect($component->instance()->catalogSuggestions)->toBeEmpty();
});

test('selecting a suggestion adds the catalog item to the list', function () {
    $user = User::factory()->create();
    $list = ShoppingList::factory()->for($user)->create();
    $catalogItem = CatalogItem::factory()->create(['name' => 'Banana']);

    Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->call('selectCatalogSuggestion', $catalogItem->id);

    expect($list->items()->where('catalog_item_id', $catalogItem->id)->exists())->toBeTrue();
});

test('selecting a suggestion clears the quick-add input', function () {
    $user = User::factory()->create();
    ShoppingList::factory()->for($user)->create();
    $catalogItem = CatalogItem::factory()->create(['name' => 'Banana']);

    Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->set('quickAddName', 'ban')
        ->call('selectCatalogSuggestion', $catalogItem->id)
        ->assertSet('quickAddName', '');
});

test('shared mode cannot quick-add items', function () {
    $user = User::factory()->create();
    $list = ShoppingList::factory()->for($user)->create();

    Livewire::test(ShoppingListPage::class, ['share_token' => $list->share_token])
        ->set('quickAddName', 'Sneaky item')
        ->call('quickAdd');

    expect($list->fresh()->items()->count())->toBe(0);
});
