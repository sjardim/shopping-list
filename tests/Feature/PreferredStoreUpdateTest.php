<?php

use App\Models\CatalogItem;
use App\Models\ShoppingList;
use App\Models\ShoppingListItem;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

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

test('preferred store is not updated when bought fewer than 4 times', function () {
    $user = User::factory()->create();
    $catalogItem = CatalogItem::factory()->create(['preferred_store' => null]);

    foreach (range(1, 3) as $_) {
        $list = ShoppingList::factory()->for($user)->create(['store' => 'lidl']);
        buyItemAtStore($catalogItem, $list);
    }

    expect($catalogItem->fresh()->preferred_store)->toBeNull();
});

test('preferred store is updated after buying at the same store 4 times', function () {
    $user = User::factory()->create();
    $catalogItem = CatalogItem::factory()->create(['preferred_store' => null]);

    foreach (range(1, 4) as $_) {
        $list = ShoppingList::factory()->for($user)->create(['store' => 'lidl']);
        buyItemAtStore($catalogItem, $list);
    }

    expect($catalogItem->fresh()->preferred_store)->toBe('lidl');
});

test('preferred store switches when a different store is bought more than 3 times', function () {
    $user = User::factory()->create();
    $catalogItem = CatalogItem::factory()->create(['preferred_store' => 'lidl']);

    foreach (range(1, 4) as $_) {
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

    foreach (range(1, 4) as $_) {
        $list = ShoppingList::factory()->for($user)->create(['store' => null]);
        buyItemAtStore($catalogItem, $list);
    }

    expect($catalogItem->fresh()->preferred_store)->toBeNull();
});

test('preferred store is not updated for ad-hoc items without a catalog item', function () {
    $user = User::factory()->create();
    $list = ShoppingList::factory()->for($user)->create(['store' => 'lidl']);

    foreach (range(1, 4) as $_) {
        $item = ShoppingListItem::factory()->for($list, 'list')->create([
            'catalog_item_id' => null,
            'is_bought' => false,
        ]);
        $item->toggleBought();
    }

    // No assertions needed — just confirm it doesn't throw
    expect(true)->toBeTrue();
});
