<?php

use App\Livewire\ListHistoryPage;
use App\Models\ShoppingList;
use App\Models\ShoppingListItem;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;

uses(LazilyRefreshDatabase::class);

test('page loads for authenticated user', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(ListHistoryPage::class)
        ->assertOk();
});

test('shows completed lists for the current user', function () {
    $user = User::factory()->create();
    $completedList = ShoppingList::factory()->for($user)->completed()->create(['name' => 'Lidl · 01 Apr']);

    $otherUser = User::factory()->create();
    ShoppingList::factory()->for($otherUser)->completed()->create();

    $component = Livewire::actingAs($user)->test(ListHistoryPage::class);

    expect($component->instance()->completedLists)
        ->toHaveCount(1)
        ->first()->id->toBe($completedList->id);
});

test('does not show active lists in history', function () {
    $user = User::factory()->create();
    ShoppingList::factory()->for($user)->create(); // active

    $component = Livewire::actingAs($user)->test(ListHistoryPage::class);

    expect($component->instance()->completedLists)->toBeEmpty();
});

test('can delete a completed list', function () {
    $user = User::factory()->create();
    $list = ShoppingList::factory()->for($user)->completed()->create();

    Livewire::actingAs($user)
        ->test(ListHistoryPage::class)
        ->call('deleteList', $list->id);

    $this->assertModelMissing($list);
});

test('cannot delete another user\'s list', function () {
    $owner = User::factory()->create();
    $list = ShoppingList::factory()->for($owner)->completed()->create();

    $attacker = User::factory()->create();

    $this->expectException(ModelNotFoundException::class);

    Livewire::actingAs($attacker)
        ->test(ListHistoryPage::class)
        ->call('deleteList', $list->id);
});

test('repeat list copies items to active list', function () {
    $user = User::factory()->create();
    $activeList = ShoppingList::factory()->for($user)->create();

    $completedList = ShoppingList::factory()->for($user)->completed()->create();
    $item = ShoppingListItem::factory()->for($completedList, 'list')->create([
        'name' => 'Oranges',
        'emoji' => '🍊',
        'category' => 'fruta',
        'quantity' => 1,
        'unit' => 'kg',
    ]);

    Livewire::actingAs($user)
        ->test(ListHistoryPage::class)
        ->call('repeatList', $completedList->id);

    expect($activeList->fresh()->items()->where('name', 'Oranges')->exists())->toBeTrue();
});

test('repeat list does not duplicate already existing pending items', function () {
    $user = User::factory()->create();
    $activeList = ShoppingList::factory()->for($user)->create();
    $existing = ShoppingListItem::factory()->for($activeList, 'list')->create([
        'name' => 'Oranges',
        'quantity' => 2,
        'is_bought' => false,
    ]);

    $completedList = ShoppingList::factory()->for($user)->completed()->create();
    ShoppingListItem::factory()->for($completedList, 'list')->create([
        'name' => 'Oranges',
        'quantity' => 5,
    ]);

    Livewire::actingAs($user)
        ->test(ListHistoryPage::class)
        ->call('repeatList', $completedList->id);

    $items = $activeList->fresh()->items()->where('name', 'Oranges')->get();
    expect($items)->toHaveCount(1);
    expect((float) $items->first()->quantity)->toBe(2.0);
    expect($items->first()->id)->toBe($existing->id);
});

test('repeat list restores bought duplicates back to pending', function () {
    $user = User::factory()->create();
    $activeList = ShoppingList::factory()->for($user)->create();
    $bought = ShoppingListItem::factory()->for($activeList, 'list')->create([
        'name' => 'Oranges',
        'is_bought' => true,
        'bought_at' => now()->subHour(),
    ]);

    $completedList = ShoppingList::factory()->for($user)->completed()->create();
    ShoppingListItem::factory()->for($completedList, 'list')->create(['name' => 'Oranges']);

    Livewire::actingAs($user)
        ->test(ListHistoryPage::class)
        ->call('repeatList', $completedList->id);

    $bought->refresh();
    expect($bought->is_bought)->toBeFalse();
    expect($bought->bought_at)->toBeNull();
    expect($activeList->fresh()->items()->where('name', 'Oranges')->count())->toBe(1);
});

test('repeat list redirects to home', function () {
    $user = User::factory()->create();
    ShoppingList::factory()->for($user)->create(); // active

    $completedList = ShoppingList::factory()->for($user)->completed()->create();
    ShoppingListItem::factory()->for($completedList, 'list')->create();

    Livewire::actingAs($user)
        ->test(ListHistoryPage::class)
        ->call('repeatList', $completedList->id)
        ->assertRedirect(route('home'));
});
