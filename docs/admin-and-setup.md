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

## Reseeding after changing locale

The catalog table gets one locale's worth of rows from the install. If you switch locale and want the catalog in the new locale's strings:

```bash
php artisan db:seed --class=CatalogItemSeederEs   # (or En, Gb, Br)
php artisan db:seed --class=ShoppingHistorySeederEs
```

Existing items on past lists keep their stored names. New seeds are tagged with the matching locale and surface to the relevant locale's search.

## Database

Default is SQLite for portability. Postgres works too: switch `DB_CONNECTION=pgsql` in `.env` and run migrations. The catalog `search` scope automatically uses `ILIKE` on Postgres and `LIKE` (case insensitive for ASCII) on SQLite.
