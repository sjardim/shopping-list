<?php

use App\Models\ShoppingList;
use App\Models\ShoppingListItem;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('renders a printable view of the list', function () {
    $user = User::factory()->create();
    $list = ShoppingList::factory()->for($user)->create([
        'name' => 'Lidl · 24 Apr',
        'notes' => 'Use cooler bag',
    ]);
    ShoppingListItem::factory()->for($list, 'list')->create([
        'name' => 'Bacalhau',
        'category' => 'peixe',
    ]);

    $this->get(route('list.print', $list->share_token))
        ->assertOk()
        ->assertSee('Lidl · 24 Apr')
        ->assertSee('Use cooler bag')
        ->assertSee('Bacalhau')
        ->assertSee('window.print()', escape: false);
});
