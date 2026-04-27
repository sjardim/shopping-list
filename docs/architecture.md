# Architecture

For contributors and curious readers. Tour of the stack, key components, and where to find what.

## Stack

* PHP 8.5 with Laravel 13.
* Livewire 4 for reactive components, Alpine.js (bundled with Flux) for client side touches.
* Tailwind CSS v4 with custom utilities (`tap`, `fade-in-up`, `check-pulse`, `list-text`, `list-text-sm`, `list-text-xs`, `heading-serif`, `no-scrollbar`).
* Flux UI v2 (Free edition only, no proprietary dependencies).
* Laravel Reverb v1 for WebSocket broadcasts (opt in).
* Laravel Fortify v1 for the single owner's auth.
* Pest 4 for tests (115 currently passing, 189 assertions).
* Phosphor Duotone icons (21 SVGs published as Flux icon stubs).

## Page level Livewire components

Three pages, three components, all under `app/Livewire/`:

| Component | Routes | Purpose |
|---|---|---|
| `ShoppingListPage` | `/`, `/list/{share_token}` | Active list (owner) and shared view (token). One component, two `$mode` values. |
| `AddItemsPage` | `/add` | Catalog grid, search, "Cook something" tab. |
| `ListHistoryPage` | `/history` | Completed trips, repeat, delete, export. |

`ShoppingListPage` mounts in either `owner` mode (loads or creates the user's active list) or `shared` mode (looks up by share token, hides destructive actions).

## View decomposition

`ShoppingListPage` renders eight anonymous Blade components under `resources/views/components/shopping-list/`:

* `header.blade.php` (title, store selector, profile or settings dropdown)
* `progress-card.blade.php` (count, progress bar, finish, clear)
* `notes-input.blade.php` (textarea with autosave and voice)
* `item-row.blade.php` (pending row with stepper)
* `bought-row.blade.php` (struck through "in cart" row)
* `quick-add.blade.php` (bottom add bar with catalog suggestions)
* `price-modal.blade.php` (price input, history, preferred store picker)
* `save-recipe-modal.blade.php` (name and emoji form)

The page view itself is about 85 lines after extraction. Each component takes its data via `@props([...])` and reaches the parent component via `wire:click="..."` (Livewire scope cascades).

## Trait extraction

Quantity stepper actions live in `App\Livewire\Concerns\HandlesQuantity` instead of bloating the page component:

* `incrementQuantity(int $id)`
* `decrementQuantity(int $id)`
* `adjustQuantity(int $id, int $direction)` (private, unit aware step + clamp + broadcast)
* `quantityStep(string $unit): float`

The trait is `phpstan-require-extends ShoppingListPage`, so the static analyser knows it needs the page's `$mode`, `$list`, and `broadcastToOthers` trait.

## Services

`App\Services\PriceHistoryService::forItem(ShoppingListItem $item, int $userId, int $limit = 10)` returns the recent priced rows for a catalog item across the user's lists. Encapsulates the join and ordering logic, tested in isolation.

## Models

* `User`: standard Fortify user plus `is_admin` bool. Has an `admins()` query scope.
* `ShoppingList`: belongs to user, has many items. Auto generates `share_token` (UUID) and `name` ("Store · Date") in `booted()`. `markCompleted()`, `pendingCount()`, `totalCount()`. Casts: `status` enum, `store` via `StoreCast`, `completed_at` datetime.
* `ShoppingListItem`: belongs to list and (optionally) catalog item. `toggleBought()` flips the bought state and triggers the preferred store auto learner.
* `CatalogItem`: scopes `search($term)`, `byCategory($cat)`, `forLocale($locale)`. `syncPreferredStore($store)` flips the preferred store when the user has at least three priced or ticked off purchases at a given store.
* `MealRecipe`: user saved recipe. Belongs to user, has a name, emoji, and serialised items array.

## Enums

* `App\Contracts\Store`: interface for region store enums (`label`, `color`, `hasDarkText`).
* `App\Enums\StorePt`: Lidl, Aldi, Continente, Mercadona.
* `App\Enums\StoreUs`: Walmart, Target, Trader Joe's, Whole Foods, Lidl, Aldi.
* `App\Enums\StoreUk`, `StoreBr`, `StoreEs`: same shape.
* `App\Enums\Category`: 10 categories with emojis and labels.
* `App\Enums\ShoppingListStatus`: Active, Completed.

`App\Support\Stores` is the region helper. `Stores::active()` returns the cases for the configured region. `Stores::tryFrom($slug)` resolves any slug across all regions (so old lists keep their badges).

## Casts

* `App\Casts\StoreCast`: resolves `shopping_lists.store` slug into a `Store` enum instance via `Stores::tryFrom`.

## Events (broadcast)

Under `app/Events/`. All implement `ShouldBroadcast` and broadcast on a public channel `shopping.{share_token}`:

* `ItemAdded`
* `ItemRemoved`
* `ItemToggled`
* `ItemQuantityChanged`

Each carries a small payload (item id plus relevant fields).

## Concerns

`App\Concerns\BroadcastsToOthers`: helper trait that calls `broadcast($event)` and chains `toOthers()` if a valid `X-Socket-ID` header is present. Short circuits when `config('lista.reverb.enabled')` is false, so dev environments without Reverb running stay quiet.

## Static config

`App\Support\MealBundles::all()` returns ten meal bundles for the current locale via a `match($locale)` (English, Brazilian, Spanish, Portuguese branches). No DB table.

## Front end JS

`resources/js/app.js`:

* Echo + Pusher init, gated on `VITE_REVERB_ENABLED === 'true'`.
* `window.lista.sounds`: synthesised Web Audio cues with a localStorage backed mute toggle.
* `window.voiceInput(locale)`: Alpine helper for the Web Speech API.
* Confetti for finish trip.
* Listeners for the Livewire events `trip-finished`, `trip-restored`, `list-updated-remotely`, `validation-failed`.

## Front end CSS

`resources/css/app.css`:

* Tailwind v4 imports and `@theme` font setup.
* Custom utilities (the `tap`, `fade-in-up`, `list-text*`, etc.).
* High contrast and big targets attribute selectors.
* `prefers-reduced-motion` global override.
* Toast positioning fixes for full width mobile.

## Tests

Pest 4, mostly Feature tests under `tests/Feature/`. Shapes:

* Page level tests use `Livewire::actingAs($user)->test(Component::class)` to drive the component.
* Database tests use `LazilyRefreshDatabase` for speed.
* Broadcast tests use `Event::fake([Event::class])` plus `Event::assertDispatched(...)`.
* Model and service tests live alongside their Feature siblings (`PriceHistoryAfterRepeatTest`, `PreferredStoreUpdateTest`, `CatalogItemLocaleTest`, etc.).

Run all:

```bash
php artisan test --compact
```

Run one file:

```bash
php artisan test --compact --filter=ShoppingListPageTest
```

## Project layout summary

```
app/
  Casts/             StoreCast
  Concerns/          BroadcastsToOthers
  Console/Commands/  InstallCommand, MakeAdminCommand
  Contracts/         Store interface
  Enums/             StorePt, StoreUs, StoreUk, StoreBr, StoreEs, Category, ShoppingListStatus
  Events/            ItemAdded, ItemRemoved, ItemToggled, ItemQuantityChanged
  Http/Controllers/  ListExportController, ListPrintController
  Livewire/          ShoppingListPage, AddItemsPage, ListHistoryPage
    Concerns/        HandlesQuantity
  Models/            User, ShoppingList, ShoppingListItem, CatalogItem, MealRecipe
  Services/          PriceHistoryService
  Support/           MealBundles, Stores

config/
  lista.php          Region, currency, Reverb toggle (env driven)

database/
  migrations/        Standard Laravel migrations
  seeders/           AdminUserSeeder, BaseShoppingHistorySeeder, CatalogItemSeeder*, ShoppingHistorySeeder*
  factories/         For all models with tests

resources/
  css/app.css        Tailwind v4 + custom utilities + a11y CSS
  js/app.js          Echo setup, sounds module, voiceInput Alpine helper, confetti
  views/
    flux/icon/       21 Phosphor Duotone stubs
    livewire/        ShoppingListPage, AddItemsPage, ListHistoryPage
    components/
      shopping-list/ 8 anonymous Blade components
      bottom-nav, store-badge, text-size-controls, etc.
    layouts/app.blade.php
    list-print.blade.php
    list-export.blade.php

lang/{en,en_GB,pt_PT,pt_BR,es}/app.php   UI translations

tests/
  Feature/           Per component happy path + edge case tests
  Unit/              Currently empty (framework bound tests live in Feature)
```
