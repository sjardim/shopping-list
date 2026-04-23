<?php

use App\Enums\Store;
use App\Livewire\ShoppingListPage;
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

    expect($list->store)->toBe(Store::Lidl);
});

test('owner can clear store', function () {
    $user = User::factory()->create();
    ShoppingList::factory()->for($user)->forStore(Store::Continente)->create();

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

test('shared mode cannot quick-add items', function () {
    $user = User::factory()->create();
    $list = ShoppingList::factory()->for($user)->create();

    Livewire::test(ShoppingListPage::class, ['share_token' => $list->share_token])
        ->set('quickAddName', 'Sneaky item')
        ->call('quickAdd');

    expect($list->fresh()->items()->count())->toBe(0);
});
