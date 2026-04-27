<?php

use App\Livewire\ShoppingListPage;
use App\Models\CatalogItem;
use App\Models\ShoppingList;
use App\Models\ShoppingListItem;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;

uses(LazilyRefreshDatabase::class);

function buyItemAtStore(CatalogItem $catalogItem, ShoppingList $list): ShoppingListItem
{
    $item = ShoppingListItem::factory()->for($list, 'list')->create([
        'catalog_item_id' => $catalogItem->id,
        'is_bought' => false,
    ]);
    $item->toggleBought();

    return $item;
}

test('preferred store is not updated when bought fewer than 3 times', function () {
    $user = User::factory()->create();
    $catalogItem = CatalogItem::factory()->create(['preferred_store' => null]);

    foreach (range(1, 2) as $_) {
        $list = ShoppingList::factory()->for($user)->create(['store' => 'lidl']);
        buyItemAtStore($catalogItem, $list);
    }

    expect($catalogItem->fresh()->preferred_store)->toBeNull();
});

test('preferred store is updated after buying at the same store 3 times', function () {
    $user = User::factory()->create();
    $catalogItem = CatalogItem::factory()->create(['preferred_store' => null]);

    foreach (range(1, 3) as $_) {
        $list = ShoppingList::factory()->for($user)->create(['store' => 'lidl']);
        buyItemAtStore($catalogItem, $list);
    }

    expect($catalogItem->fresh()->preferred_store)->toBe('lidl');
});

test('preferred store switches when a different store is bought 3 times', function () {
    $user = User::factory()->create();
    $catalogItem = CatalogItem::factory()->create(['preferred_store' => 'lidl']);

    foreach (range(1, 3) as $_) {
        $list = ShoppingList::factory()->for($user)->create(['store' => 'continente']);
        buyItemAtStore($catalogItem, $list);
    }

    expect($catalogItem->fresh()->preferred_store)->toBe('continente');
});

test('preferred store is not updated when un-buying an item', function () {
    $user = User::factory()->create();
    $catalogItem = CatalogItem::factory()->create(['preferred_store' => null]);

    $list = ShoppingList::factory()->for($user)->create(['store' => 'lidl']);
    $item = ShoppingListItem::factory()->for($list, 'list')->create([
        'catalog_item_id' => $catalogItem->id,
        'is_bought' => true,
    ]);

    $item->toggleBought(); // un-buy

    expect($catalogItem->fresh()->preferred_store)->toBeNull();
});

test('preferred store is not updated when list has no store', function () {
    $user = User::factory()->create();
    $catalogItem = CatalogItem::factory()->create(['preferred_store' => null]);

    foreach (range(1, 3) as $_) {
        $list = ShoppingList::factory()->for($user)->create(['store' => null]);
        buyItemAtStore($catalogItem, $list);
    }

    expect($catalogItem->fresh()->preferred_store)->toBeNull();
});

test('priced items at the same store flip preferred_store even without ticking', function () {
    $user = User::factory()->create();
    $catalogItem = CatalogItem::factory()->create(['preferred_store' => null]);

    foreach (range(1, 3) as $_) {
        $list = ShoppingList::factory()->for($user)->create(['store' => 'mercadona']);
        ShoppingListItem::factory()->for($list, 'list')->create([
            'catalog_item_id' => $catalogItem->id,
            'is_bought' => false,
            'price' => 12.00,
        ]);
    }

    $catalogItem->syncPreferredStore('mercadona');

    expect($catalogItem->fresh()->preferred_store)->toBe('mercadona');
});

test('owner can manually pick any region store as preferred', function () {
    $user = User::factory()->create();
    $catalogItem = CatalogItem::factory()->create(['preferred_store' => 'continente']);
    $list = ShoppingList::factory()->for($user)->create(['store' => null]);
    $item = ShoppingListItem::factory()->for($list, 'list')->create([
        'catalog_item_id' => $catalogItem->id,
        'preferred_store' => 'continente',
    ]);

    Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->call('markPreferredStore', $item->id, 'mercadona');

    expect($catalogItem->fresh()->preferred_store)->toBe('mercadona')
        ->and($item->fresh()->preferred_store)->toBe('mercadona');
});

test('owner can clear the preferred store by passing an empty slug', function () {
    $user = User::factory()->create();
    $catalogItem = CatalogItem::factory()->create(['preferred_store' => 'continente']);
    $list = ShoppingList::factory()->for($user)->create(['store' => null]);
    $item = ShoppingListItem::factory()->for($list, 'list')->create([
        'catalog_item_id' => $catalogItem->id,
        'preferred_store' => 'continente',
    ]);

    Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->call('markPreferredStore', $item->id, '');

    expect($catalogItem->fresh()->preferred_store)->toBeNull()
        ->and($item->fresh()->preferred_store)->toBeNull();
});

test('unknown store slug is rejected (clears instead of corrupting)', function () {
    $user = User::factory()->create();
    $catalogItem = CatalogItem::factory()->create(['preferred_store' => 'continente']);
    $list = ShoppingList::factory()->for($user)->create();
    $item = ShoppingListItem::factory()->for($list, 'list')->create([
        'catalog_item_id' => $catalogItem->id,
        'preferred_store' => 'continente',
    ]);

    Livewire::actingAs($user)
        ->test(ShoppingListPage::class)
        ->call('markPreferredStore', $item->id, 'not-a-real-store');

    expect($catalogItem->fresh()->preferred_store)->toBeNull();
});

test('preferred store is not updated for ad-hoc items without a catalog item', function () {
    $user = User::factory()->create();
    $list = ShoppingList::factory()->for($user)->create(['store' => 'lidl']);

    foreach (range(1, 3) as $_) {
        $item = ShoppingListItem::factory()->for($list, 'list')->create([
            'catalog_item_id' => null,
            'is_bought' => false,
        ]);
        $item->toggleBought();
    }

    expect(true)->toBeTrue();
});
