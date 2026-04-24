<?php

use App\Models\ShoppingList;
use App\Models\ShoppingListItem;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('exports a list as JSON via the share token', function () {
    $user = User::factory()->create();
    $list = ShoppingList::factory()->for($user)->create([
        'name' => 'Mercadona · 24 Apr',
        'notes' => 'Bring the cooler bag',
    ]);
    ShoppingListItem::factory()->for($list, 'list')->create([
        'name' => 'Bacalhau',
        'quantity' => 1,
        'unit' => 'kg',
        'is_bought' => true,
    ]);

    $response = $this->get(route('list.export', $list->share_token));

    $response->assertOk()
        ->assertHeader('Content-Type', 'application/json')
        ->assertHeader('Content-Disposition', 'attachment; filename="mercadona-24-apr.json"')
        ->assertJsonPath('name', 'Mercadona · 24 Apr')
        ->assertJsonPath('notes', 'Bring the cooler bag')
        ->assertJsonPath('items.0.name', 'Bacalhau')
        ->assertJsonPath('items.0.is_bought', true);
});

test('export 404s for an unknown share token', function () {
    $this->get('/list/nope/export.json')->assertNotFound();
});
