# Lista — Features

A reference for everything Lista does. The [README](README.md) has the elevator pitch and install steps; this file has the depth.

## Owner experience

- **Categorised list** — active items grouped by category (Fruta, Legumes, Lacticínios, Carne…) with category emoji headers.
- **Quick-add bar** — type any name. Debounced catalog search ("ban…" suggests Bananas). Submit creates a free-text item if no catalog match.
- **Voice dictation** — microphone button on the quick-add bar and the notes field uses the Web Speech API in the active locale. Brief two-note chirp on start and stop.
- **Tap-to-check animation** — checking an item slides the row off-screen left with a stone tint and plays a quick chirp before Livewire re-renders the item into the bought section. Soft Android haptic on tap.
- **Per-item price tag** — every owner-mode item shows a `+€` (or current price) tag next to the remove ×. Tapping opens a modal with:
  - Current price input (decimal, accepts comma or period).
  - Last 10 prices for that catalog item across all your past lists, with the store badge and date. Helps spot a deal.
- **Running trip total** — "€X.XX spent so far" on the green progress card whenever any bought item has a price.
- **Per-trip notes** — free-text textarea above the items list with debounced autosave; a "Saved" pill fades in for 2 seconds after each save.
- **Store selector** — tinted to the brand colour of the active store. Click to choose from the active region's stores or "No store".
- **Finish trip** — archives the active list, creates a fresh one, rains brand-coloured confetti, plays a synthesised TADA chord, and starts a 5-minute Undo window. Drop your own `public/sounds/finish-trip.mp3` to override the synth with a real applause clip.
- **Undo finish trip** — a 5-minute window after finishing where the empty list shows an Undo button. Clicking it re-activates the just-archived list and discards the new empty one (preserved if you've already added items to it).

## Catalog and recipes

- **Pre-seeded catalog** — ~80 grocery items per region (PT, US, UK, BR, ES), each with category, default unit, default quantity, emoji, and a preferred-store hint.
- **Auto-learning preferred store** — when you buy a catalog item at the same store more than three times, the catalog updates the item's preferred store silently. Future lists show "usually at <store>" hints.
- **Cook something tab** — built-in meal bundles (10 per locale: Frango Assado / Bacalhau à Braga / Caldo Verde / Churrasco / Massa Bolonhesa / etc.) merge all ingredients into the active list with one tap, skipping items already on the list and matching catalog items so your store hints stick.
- **Save as recipe** — turn the current list into a custom bundle with a name and emoji. Saved recipes appear in the Cook tab next to the built-ins, each with a green "your recipe" badge and a delete action. Bundle keys are stable across locale switches.

## Shared mode (no auth)

- **Share token URLs** — each list has a UUID. The other person opens `/list/{token}` with no login, no account, no setup.
- **Same view, fewer actions** — shared mode shows everything the owner sees minus destructive actions (no Finish trip, no Clear, no Remove ×, no Save as recipe) and minus the bottom nav.
- **Real-time sync** — Laravel Reverb broadcasts toggle/add/remove events on the list's channel. The other device updates within ~1 second, with a soft notification chime and a "List updated" toast. Optional — works fine without Reverb.
- **Settings dropdown** — a small gear icon next to the share button gives shared users access to text-size, contrast, big-buttons, and sound-effects preferences without exposing the owner's account.

## History

- **Completed trips** — every finished trip lands on `/history`, ordered most-recent first. Each card shows item count, skipped count (if any), and a preview of the first six items.
- **Restore (intelligent merge)** — one-tap merge of a past list into your active one:
  - Brand-new items → added.
  - Pending duplicates (same name, not yet bought) → left untouched.
  - Bought duplicates (same name, already ticked off) → reset to pending so they reappear on the active list.
  - Toast reports the three counts: "Added 5, restored 2, skipped 1."
- **Per-list export and delete** — download (JSON) and trash actions on every history card.
- **Seeded history** — `Shopping­History­Seeder*` ships 11 fake completed trips spread across ~60 days, rotating across the region's stores, so a fresh install has something to look at.

## Accessibility (a11y)

A user-toggleable preferences cluster lives in the profile dropdown, persisted to `localStorage` (per-device, works for both the owner and the shared user):

- **Text size** — three-step UI scale (default / large / extra large) that re-roots the entire `<html>` font-size, plus a separate "Larger list items" toggle for just the item rows.
- **High contrast** — darker muted text and stronger borders that lift WCAG contrast on the warm cream background.
- **Bigger buttons** — bumps every tappable element to a 44 × 44 minimum (WCAG 2.5.5 AAA).
- **Sound effects** — global mute for all synthesised cues (TADA, undo, error buzz, item check chirp, voice toggle chirps, real-time update ping).

System-level support:

- `prefers-reduced-motion` respected globally — animations and transitions are disabled at the CSS level.
- `aria-label` on every icon-only button. `aria-current="page"` on the active bottom-nav tab. `aria-pressed` on the catalog selection grid. `role="alert"` on validation errors.
- `focus-visible` ring on every tappable element and form input for keyboard users.
- Light haptic feedback (`navigator.vibrate?.(8)`) on item check-off (Android only; iOS Safari ignores).

Inline `<head>` script applies the user's font-size, contrast, and big-button preferences before paint to avoid flash of unstyled content.

## Audio cues

All synthesised live with the Web Audio API (no audio binaries shipped). Each respects the **Sound effects** mute toggle:

| Cue | When |
|---|---|
| TADA | Finishing a trip |
| Reverse TADA | Undoing a finish trip |
| Two-note ascending chirp | Starting voice dictation |
| Two-note descending chirp | Stopping voice dictation |
| Quick high chirp | Ticking / un-ticking an item |
| Soft single ping | Other user changed the list (real-time) |
| Square-wave buzz | Validation error |

To swap the TADA for a real applause clip, drop `public/sounds/finish-trip.mp3` (anything else is unchanged).

## Internationalisation

- **Five locales shipped**: `en`, `en_GB`, `pt_PT`, `pt_BR`, `es`. Switchable from the profile dropdown.
- **Currencies expected**: `€` (PT and ES default), `$` (US), `£` (UK), `R$` (BR). Set via `CURRENCY_SYMBOL` env var.
- **Five store regions**: `pt`, `us`, `uk`, `br`, `es`. Set via `STORES_REGION`. Picker only shows the active region's stores; other regions stay loaded as fallbacks so older lists keep their badges.
- **Locale-aware meal bundles** — `MealBundles::all()` branches on `app()->getLocale()`:
  - `en`, `en_GB` → English bundles ("Roast Chicken", "Spaghetti Bolognese", "Mixed Salad")
  - `pt_BR` → Brazilian bundles ("Frango Assado", "Macarrão à Bolonhesa", "Feijoada Brasileira")
  - `es` → Spanish bundles ("Pollo Asado", "Bacalao a la Vizcaína", "Fabada Asturiana")
  - any other (default) → European Portuguese bundles ("Frango Assado", "Bacalhau à Braga", "Caldo Verde")
- Bundle keys are stable across locales so user-saved recipes keep working when you switch.

### Adding a new region or locale

- **New region** — drop `App\Enums\StoreFr` (etc.) implementing `App\Contracts\Store`, add it to `App\Support\Stores::REGIONS`. Optionally add a `CatalogItemSeederFr` and `ShoppingHistorySeederFr` mirroring the existing pairs.
- **New locale** — create `lang/<code>/app.php` (Laravel falls back to `en` for missing keys). Add it to the locale switcher in `shopping-list-page.blade.php` and to the `switchLocale()` allow-list in `ShoppingListPage`.

## Export and printing

- **JSON export** — `GET /list/{token}/export.json` returns a pretty-printed JSON snapshot of the list (name, store, status, notes, timestamps, full items) with a `Content-Disposition: attachment` filename based on the list slug. Available from the owner profile dropdown and as a download icon on every history card.
- **Print view** — `GET /list/{token}/print` opens a no-nav, A4-friendly black-on-white printable view that auto-fires `window.print()` on load. Categorised, with big tickbox-style checkboxes. Nice when you'd rather have paper.

## Progressive Web App

- `public/manifest.json` + cream theme color + apple-touch-icon meta tags so the app installs to the home screen on iOS and Android with the right title, icon, and full-screen treatment.
- Inline `<head>` script applies user font-size and contrast preferences before paint to avoid flash of unstyled content.
- `viewport-fit=cover` so the install renders edge-to-edge on iPhone.
- Dropping your own `public/icons/icon.svg` (or PNG variants) is enough to replace the default.

## Auth and admin

- **Single-user app by design** — no registration, no password reset (use tinker or `lista:make-admin`). Login only via Laravel Fortify.
- **`is_admin` flag** on the users table is the single source of truth for owner identity. Seeders look up the admin via `User::admins()->oldest('id')->first()`. The migration backfills `is_admin = true` for any pre-existing user.
- **Two ways to create the first admin**:
  - `php artisan lista:install` — interactive: prompts for locale, region, currency, then admin email/name/password, then writes env + creates the user + seeds catalog/history.
  - `php artisan db:seed` — non-interactive: creates `admin@example.com` / `password` (rotate immediately).
- **Promoting an existing user** — `php artisan lista:make-admin <email>` either creates a new admin or promotes an existing user, prompting for missing details.

## Tech stack

- PHP 8.5 + Laravel 13
- Livewire 4 for reactive components, Alpine.js (bundled with Flux) for client-side touches
- Tailwind CSS v4 with custom utilities (`tap`, `fade-in-up`, `check-pulse`, `list-text`)
- Flux UI v2 (Free edition only — no proprietary dependencies)
- Laravel Reverb v1 for WebSocket broadcasts (optional)
- Laravel Fortify v1 for the single owner's auth
- Pest 4 for tests (96 currently passing, ~163 assertions)
- Phosphor Duotone icons (21 SVGs published as Flux icon stubs)

### Flux UI components used

Every component this app uses is in the Free edition. `composer install` works for any clone without a Flux Pro license.

| Component | Purpose |
|---|---|
| `flux:button` | primary, ghost, with icon, with `href` |
| `flux:dropdown` + `flux:menu` + `flux:menu.item` + `flux:menu.separator` | profile menu, store picker, settings |
| `flux:modal` + `flux:modal.trigger` + `flux:modal.close` | save-as-recipe, edit-price |
| `flux:icon` | renders the 21 Phosphor Duotone stubs |
| `flux:input`, `flux:label`, `flux:field`, `flux:error` | recipe form, price form, search |
| `flux:heading`, `flux:subheading` | modal headings |
| `flux:toast` | global toast container, top-center, full-width on mobile |

## Project layout

```
app/
  Casts/         StoreCast — resolves shopping_lists.store slug → Store instance
  Concerns/      BroadcastsToOthers — guards toOthers() against undefined socket IDs
  Console/Commands/  InstallCommand, MakeAdminCommand
  Contracts/     Store interface
  Enums/         StorePt, StoreUs, StoreUk, StoreBr, StoreEs, Category, ShoppingListStatus
  Events/        ItemAdded, ItemRemoved, ItemToggled (broadcast events)
  Http/Controllers/  ListExportController, ListPrintController
  Livewire/      ShoppingListPage, AddItemsPage, ListHistoryPage
  Models/        User, ShoppingList, ShoppingListItem, CatalogItem, MealRecipe
  Support/       MealBundles (locale-aware bundles), Stores (region helper)

database/
  migrations/    Standard Laravel migrations
  seeders/       AdminUserSeeder, BaseShoppingHistorySeeder,
                 CatalogItemSeeder*, ShoppingHistorySeeder* (one per region)
  factories/     For all models with tests

resources/
  css/app.css    Tailwind v4 + custom utilities + a11y CSS
  js/app.js      Echo setup, sounds module, voiceInput Alpine helper, confetti
  views/
    flux/icon/   21 Phosphor Duotone stubs
    livewire/    ShoppingListPage, AddItemsPage, ListHistoryPage
    components/  bottom-nav, store-badge, text-size-controls
    layouts/app.blade.php
    list-print.blade.php

config/lista.php  Stores region + currency symbol (env-driven)
lang/{en,en_GB,pt_PT,pt_BR}/app.php  Translations

tests/
  Feature/       Per-component happy-path + edge-case tests
  Unit/          (currently empty; framework-bound tests live in Feature)
```
