<?php

use App\Livewire\AddItemsPage;
use App\Models\CatalogItem;
use App\Models\ShoppingList;
use App\Models\ShoppingListItem;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;

uses(LazilyRefreshDatabase::class);

test('page loads for authenticated user', function () {
    $user = User::factory()->create();
    ShoppingList::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(AddItemsPage::class)
        ->assertOk();
});

test('catalog items are grouped by category', function () {
    $user = User::factory()->create();
    ShoppingList::factory()->for($user)->create();

    CatalogItem::factory()->create(['name' => 'Apple', 'category' => 'fruta']);
    CatalogItem::factory()->create(['name' => 'Milk', 'category' => 'lacticinios']);

    $component = Livewire::actingAs($user)->test(AddItemsPage::class);

    expect($component->instance()->groupedCatalogItems)->toHaveKeys(['fruta', 'lacticinios']);
});

test('selecting a catalog item adds it to selected ids', function () {
    $user = User::factory()->create();
    ShoppingList::factory()->for($user)->create();
    $item = CatalogItem::factory()->create();

    Livewire::actingAs($user)
        ->test(AddItemsPage::class)
        ->call('toggleCatalogItem', $item->id)
        ->assertSet('selectedCatalogIds', [$item->id]);
});

test('deselecting a catalog item removes it from selected ids', function () {
    $user = User::factory()->create();
    ShoppingList::factory()->for($user)->create();
    $item = CatalogItem::factory()->create();

    Livewire::actingAs($user)
        ->test(AddItemsPage::class)
        ->call('toggleCatalogItem', $item->id)
        ->call('toggleCatalogItem', $item->id)
        ->assertSet('selectedCatalogIds', []);
});

test('items already on the list are pre-selected', function () {
    $user = User::factory()->create();
    $list = ShoppingList::factory()->for($user)->create();
    $catalogItem = CatalogItem::factory()->create();
    ShoppingListItem::factory()->for($list, 'list')->create(['catalog_item_id' => $catalogItem->id]);

    $component = Livewire::actingAs($user)->test(AddItemsPage::class);

    expect($component->get('selectedCatalogIds'))->toContain($catalogItem->id);
});

test('toggling a catalog item immediately adds it to the list', function () {
    $user = User::factory()->create();
    $list = ShoppingList::factory()->for($user)->create();
    $catalogItem = CatalogItem::factory()->create([
        'name' => 'Cheese',
        'category' => 'lacticinios',
        'default_quantity' => 1,
        'default_unit' => 'un',
    ]);

    Livewire::actingAs($user)
        ->test(AddItemsPage::class)
        ->call('toggleCatalogItem', $catalogItem->id);

    expect($list->items()->where('catalog_item_id', $catalogItem->id)->exists())->toBeTrue();
});

test('deselecting an existing item immediately removes it from the list', function () {
    $user = User::factory()->create();
    $list = ShoppingList::factory()->for($user)->create();
    $catalogItem = CatalogItem::factory()->create();
    $listItem = ShoppingListItem::factory()
        ->for($list, 'list')
        ->create(['catalog_item_id' => $catalogItem->id]);

    Livewire::actingAs($user)
        ->test(AddItemsPage::class)
        ->call('toggleCatalogItem', $catalogItem->id); // deselect

    $this->assertModelMissing($listItem);
});

test('redirects to home after adding to list', function () {
    $user = User::factory()->create();
    ShoppingList::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(AddItemsPage::class)
        ->call('addToList')
        ->assertRedirect(route('home'));
});

test('meal bundle tab is available', function () {
    $user = User::factory()->create();
    ShoppingList::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(AddItemsPage::class)
        ->assertSet('activeTab', 'suggested');
});
