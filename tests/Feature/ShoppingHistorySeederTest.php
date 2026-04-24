<?php

use App\Enums\ShoppingListStatus;
use App\Enums\StoreUs;
use App\Models\CatalogItem;
use App\Models\ShoppingList;
use App\Models\User;
use Database\Seeders\ShoppingHistorySeeder;
use Database\Seeders\ShoppingHistorySeederEn;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

const OWNER_EMAIL = 'sergio@sergiojardim.com';

test('seeds completed lists for the owner when none exist', function () {
    User::factory()->create(['email' => OWNER_EMAIL]);
    CatalogItem::factory()->count(20)->create();

    $this->seed(ShoppingHistorySeeder::class);

    $lists = ShoppingList::where('status', ShoppingListStatus::Completed)->get();

    expect($lists)->not->toBeEmpty();
    expect($lists->every(fn ($list) => $list->completed_at !== null))->toBeTrue();
    expect($lists->every(fn ($list) => $list->items()->count() > 0))->toBeTrue();
});

test('is idempotent when run twice', function () {
    User::factory()->create(['email' => OWNER_EMAIL]);
    CatalogItem::factory()->count(20)->create();

    $this->seed(ShoppingHistorySeeder::class);
    $countAfterFirst = ShoppingList::completed()->count();

    $this->seed(ShoppingHistorySeeder::class);
    $countAfterSecond = ShoppingList::completed()->count();

    expect($countAfterSecond)->toBe($countAfterFirst);
});

test('does nothing when the owner user is missing', function () {
    CatalogItem::factory()->count(5)->create();

    $this->seed(ShoppingHistorySeeder::class);

    expect(ShoppingList::count())->toBe(0);
});

test('does nothing when the catalog is empty', function () {
    User::factory()->create(['email' => OWNER_EMAIL]);

    $this->seed(ShoppingHistorySeeder::class);

    expect(ShoppingList::count())->toBe(0);
});

test('English seeder uses US stores and creates completed lists', function () {
    User::factory()->create(['email' => OWNER_EMAIL]);
    CatalogItem::factory()->count(20)->create();

    $this->seed(ShoppingHistorySeederEn::class);

    $lists = ShoppingList::where('status', ShoppingListStatus::Completed)->get();

    expect($lists)->not->toBeEmpty();

    $usStores = [StoreUs::Walmart->value, StoreUs::Target->value, StoreUs::TraderJoes->value, StoreUs::WholeFoods->value];
    expect($lists->every(fn ($list) => in_array($list->store->value, $usStores, true)))->toBeTrue();
});
