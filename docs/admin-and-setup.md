# Admin and Setup

Lista is a single owner app by design. No public registration, no password reset out of the box. The first admin is created by either the install wizard or the default seeder.

## First time setup

The fastest path:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan lista:install
```

`lista:install` is an interactive command that asks five short questions:

1. Language (English, English UK, Português, Português Brasil, Español).
2. Store region (Portugal, US, UK, Brazil, Spain). Defaults to a sensible pair for the chosen language.
3. Currency symbol (`€`, `$`, `£`, `R$`). Defaults match the region.
4. Admin email, name, and password.
5. Confirmation.

It writes `APP_LOCALE`, `STORES_REGION`, and `CURRENCY_SYMBOL` to `.env`, runs migrations, seeds the right catalog and history pair, and creates the admin user. Idempotent: rerunning is safe and preserves data.

## Non interactive setup

Want a default install without prompts?

```bash
php artisan migrate:fresh --seed
```

`DatabaseSeeder` reads `config('app.locale')` and `config('lista.stores.region')` to pick the right catalog and history seeder pair. The default admin is `admin@example.com` with password `password` (rotate immediately).

## Adding or promoting an admin

Use the dedicated command:

```bash
php artisan lista:make-admin user@example.com
```

If the user exists, this promotes them to admin. If not, the command prompts for a name and password and creates the account.

## Authentication

Login is handled by Laravel Fortify. Routes:

* `POST /login` (Fortify default). Renders the login form at `/login`.
* `POST /logout`.

Registration, password reset, two factor auth, and email verification are all disabled in `config/fortify.php`. Add them back if you want.

## Languages and regions

Already shipped:

| Locale code | Language | Default region | Currency |
|---|---|---|---|
| `en` | English | US | `$` |
| `en_GB` | English (UK) | UK | `£` |
| `pt_PT` | Português (Portugal) | PT | `€` |
| `pt_BR` | Português (Brasil) | BR | `R$` |
| `es` | Español | ES | `€` |

### Adding a new locale

1. Create `lang/<code>/app.php` (Laravel falls back to `en` for missing keys).
2. Add the locale to the `switchLocale` allow list in `App\Livewire\ShoppingListPage`.
3. Add an entry to the locale switcher dropdown in `resources/views/components/shopping-list/header.blade.php`.
4. Optional: add a catalog seeder (`CatalogItemSeeder<Code>`) and history seeder (`ShoppingHistorySeeder<Code>`). Tag every catalog row with the new locale code.

### Adding a new region

1. Create `App\Enums\Store<Code>` implementing `App\Contracts\Store` (label, color, hasDarkText, plus the cases for the chains).
2. Register it in `App\Support\Stores::REGIONS`.
3. Optional: catalog and history seeders for the region.

## Reverb (real time sync)

Optional, off by default. See [sharing-and-realtime.md](sharing-and-realtime.md#enabling-reverb) for the env block to add. The app works fine without Reverb (it falls back to standard Livewire updates).

## Configuration files

Two project specific config files plus the standard Laravel set:

* `config/lista.php`
   * `stores.region`: which `Store<Code>` enum populates the picker.
   * `currency.symbol`: shown next to every price.
   * `reverb.enabled`: master switch for the WebSocket layer.
* `config/fortify.php`: trims the auth feature set to login only.

All three are env driven, so you can change them per environment without touching code.

## Adding catalog items in production

The catalog table is meant to grow over time as you discover items your users actually buy. Three safe ways to add rows without touching user data, in increasing order of overhead.

### Option 1: append to an existing seeder

`CatalogItemSeeder*` uses `firstOrCreate(['name' => ..., 'locale' => ...], $row)`. The unique key is the (name, locale) pair, so rerunning a seeder skips rows that already exist and only inserts the new ones. No touching of user lists, prices, or history.

1. Edit the right seeder file (`CatalogItemSeeder.php` for `pt_PT`, `CatalogItemSeederBr.php` for `pt_BR`, `CatalogItemSeederEn.php` for `en`, `CatalogItemSeederGb.php` for `en_GB`, `CatalogItemSeederEs.php` for `es`).
2. Append the new rows to the `$items` array.
3. Run the seeder:
   ```bash
   php artisan db:seed --class=CatalogItemSeeder --force
   ```
   (`--force` is required in production.)

This is the right path for one off additions and small batches. Existing items are unaffected.

### Option 2: dedicated "extend" seeder

For larger batches or when you want a clean audit trail, create a new seeder per release:

```bash
php artisan make:seeder CatalogExtension20260601
```

Then in the seeder:

```php
public function run(): void
{
    $rows = [
        ['name' => 'Tofu', 'emoji' => '🟫', 'category' => 'lacticinios', 'preferred_store' => null, 'default_unit' => 'g', 'default_quantity' => 200],
        ['name' => 'Quinoa', 'emoji' => '🌾', 'category' => 'despensa', 'preferred_store' => null, 'default_unit' => 'g', 'default_quantity' => 500],
    ];

    foreach ($rows as $row) {
        $row['locale'] = 'pt_PT';
        \App\Models\CatalogItem::firstOrCreate(
            ['name' => $row['name'], 'locale' => $row['locale']],
            $row,
        );
    }
}
```

Run on production:

```bash
php artisan db:seed --class=CatalogExtension20260601 --force
```

The seeder file goes into version control, so the next clone gets the same catalog when it reseeds from `DatabaseSeeder` (add the new seeder to the `call([...])` chain if you want a fresh install to pick it up automatically).

### Option 3: data migration

For changes that mix schema and data (rare for catalog growth), use a regular Laravel migration:

```bash
php artisan make:migration add_quinoa_and_tofu_to_catalog
```

In `up()`, call the model directly (`firstOrCreate` again so reruns are safe). In `down()`, remove just those rows by name + locale. This path runs automatically on `php artisan migrate` in CI/CD, which is convenient if your deploy pipeline does not run seeders.

### What to know either way

* **Always include `locale`.** The column is NOT NULL and the search scope filters strictly by current locale. A row tagged with the wrong locale will never surface.
* **Adding to one locale does not add to others.** You add to one catalog at a time. Decide whether the new item belongs in every locale and copy across, or whether it is region specific.
* **Existing user lists are not touched.** Rows in `shopping_list_items` keep their stored name and emoji. New catalog items only show up in the Add Items grid and in the quick add bar's autocomplete.
* **Price history attaches by `catalog_item_id`.** A user who has been quick adding "Tofu" as free text will not see those past prices on the new catalog row. There is no automatic backfill. If you want to wire history up, write a one off script that updates `shopping_list_items.catalog_item_id` where the name matches.

### Removing a catalog item

`firstOrCreate` does not delete; you have to remove explicitly. Be careful: rows in `shopping_list_items` reference catalog items via `catalog_item_id`. The migration declares the column nullable on delete, so removing a catalog row leaves orphan items pointing at a missing id, which renders fine but loses the catalog link (no more preferred store hints, no price history grouping).

To remove one cleanly:

```bash
php artisan tinker
>>> \App\Models\CatalogItem::where('name', 'Old item')->where('locale', 'pt_PT')->delete();
```

Or write a migration if you want it tracked. Either way, double check the orphan items first:

```bash
>>> \App\Models\ShoppingListItem::whereHas('catalogItem', fn($q) => $q->where('name', 'Old item'))->count();
```

## Reseeding after changing locale

The catalog table gets one locale's worth of rows from the install. If you switch locale and want the catalog in the new locale's strings:

```bash
php artisan db:seed --class=CatalogItemSeederEs   # (or En, Gb, Br)
php artisan db:seed --class=ShoppingHistorySeederEs
```

Existing items on past lists keep their stored names. New seeds are tagged with the matching locale and surface to the relevant locale's search.

## Database

Default is SQLite for portability. Postgres works too: switch `DB_CONNECTION=pgsql` in `.env` and run migrations. The catalog `search` scope automatically uses `ILIKE` on Postgres and `LIKE` (case insensitive for ASCII) on SQLite.
