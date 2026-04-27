<?php

declare(strict_types=1);

use App\Models\CatalogItem;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\App;

uses(LazilyRefreshDatabase::class);

test('forLocale returns rows tagged with the given locale', function () {
    CatalogItem::factory()->create(['name' => 'Banana', 'locale' => 'en']);
    CatalogItem::factory()->create(['name' => 'Plátano', 'locale' => 'es']);

    $matches = CatalogItem::query()->forLocale('en')->get();

    expect($matches->pluck('name')->all())->toBe(['Banana']);
});

test('forLocale also returns rows without a locale tag (legacy fallback)', function () {
    CatalogItem::factory()->create(['name' => 'Apple', 'locale' => 'en']);
    CatalogItem::factory()->create(['name' => 'Plátano', 'locale' => 'es']);
    CatalogItem::factory()->create(['name' => 'Untagged', 'locale' => null]);

    $matches = CatalogItem::query()->forLocale('en')->orderBy('name')->get();

    expect($matches->pluck('name')->all())->toBe(['Apple', 'Untagged']);
});

test('search filters by current locale', function () {
    CatalogItem::factory()->create(['name' => 'Banana', 'locale' => 'en']);
    CatalogItem::factory()->create(['name' => 'Banana', 'locale' => 'pt_PT']);

    App::setLocale('pt_PT');

    $matches = CatalogItem::query()->search('ban')->get();

    expect($matches)->toHaveCount(1)
        ->and($matches->first()->locale)->toBe('pt_PT');
});

test('byCategory filters by current locale', function () {
    CatalogItem::factory()->create(['name' => 'Apple', 'category' => 'fruta', 'locale' => 'en']);
    CatalogItem::factory()->create(['name' => 'Maçã', 'category' => 'fruta', 'locale' => 'pt_PT']);

    App::setLocale('en');

    $matches = CatalogItem::query()->byCategory('fruta')->get();

    expect($matches->pluck('name')->all())->toBe(['Apple']);
});
