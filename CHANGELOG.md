# Changelog

All notable changes to this project will be documented in this file. Format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## 1.1.0 — Around the World (2026-04-24)

This release is about getting Lista out of Portugal. New developers cloning the repo for a US, UK, or any other supermarket landscape now have a clean path that doesn't involve learning the difference between Continente and Mercadona.

### Added

- **Regional store enums.** The single `App\Enums\Store` is gone. In its place: a `App\Contracts\Store` interface and three implementing enums — `App\Enums\StorePt` (Lidl, Aldi, Continente, Mercadona), `App\Enums\StoreUs` (Walmart, Target, Trader Joe's, Whole Foods), and `App\Enums\StoreUk` (Tesco, Sainsbury's, Asda, Morrisons, Waitrose, Lidl, Aldi). Each region's brand colors, badge initials, and dark-text rules live with its enum.
- **`STORES_REGION` env var.** Picks which regional enum populates the store dropdown (`pt`, `us`, or `uk`). Defaults to `pt`. Other regions still resolve when slugs appear in the database — so swapping regions doesn't orphan historical badges.
- **`App\Support\Stores` helper** with `Stores::active()` and `Stores::tryFrom(slug)` for region-aware lookups.
- **`App\Casts\StoreCast`** transparently resolves the slug column to a Store enum instance, so `$list->store->color()` works exactly like before.
- **`CatalogItemSeederEn`** with ~80 common US grocery items, store hints pointing at Walmart, Target, Trader Joe's, and Whole Foods (plus Lidl/Aldi where they make sense). Run with `php artisan db:seed --class=CatalogItemSeederEn`.
- **`ShoppingHistorySeederEn`** mirrors the Portuguese history seeder but rotates US stores and uses English ad-hoc items (razor blades, AA batteries, birthday card, flowers, bag of ice).
- **Locale-aware meal bundles.** `MealBundles::all()` now branches on `app()->getLocale()`. With `APP_LOCALE=en` the "Cook something" tab serves "Roast Chicken", "Spaghetti Bolognese", "Mixed Salad", etc. — with ingredient names that match `CatalogItemSeederEn` so catalog linking still works.

### Changed

- **Browser-tab titles** for `/history` and `/add` are now translated. Previously hardcoded English; now use `__('app.history_title')` and `__('app.add_items_title')`.
- **Existing meal-bundle tests** explicitly pin `app()->setLocale('pt_PT')` so they no longer depend on the developer's `.env` having the right locale.

### Removed

- **`App\Enums\Store`.** Replaced by the three regional enums above. Anyone who was importing it directly needs to switch to `App\Enums\StorePt`/`StoreUs`/`StoreUk` or use `App\Support\Stores::tryFrom()`. Database values (the slug strings) are unchanged, so no data migration is needed.

### Notes for adopters

For a non-Portuguese setup, edit `database/seeders/DatabaseSeeder.php` to call the EN seeders instead of the PT ones, set `STORES_REGION=us` (or `uk`) in `.env`, and run `php artisan migrate:fresh --seed`. The README has the full step list under "Picking your region" and "Switching the seeded data".

### Tests

93 passing, 156 assertions (was 86/143 in 1.0.0). Five new tests cover the `Stores` helper region resolution and slug fallback behaviour; one covers the English meal bundle path; one covers the EN history seeder using only US stores.

## 1.0.0 — First Trip (2026-04-24)

The first public release of Lista. A self-hosted, two-person grocery shopping app built for a kitchen shared by an owner and a tag-along. One account, one share link, no friction.

### Headline features

#### The list itself
- Active list grouped by category, with category emoji headers (Fruta, Legumes, Lacticínios, Carne, etc.).
- Tap-to-check items slide off-screen left with a brief stone tint, a synthesised chirp, and a soft Android haptic.
- Quick-add bar with debounced catalog search ("ban..." suggests Bananas) plus voice dictation in your locale.
- Per-item price tags. Tap any tag to open a modal that shows the current price input plus the last 10 prices recorded for that item across every past trip, with the store badge and date. Helps you tell whether today's bacalhau is a deal.
- Running trip total ("€X.XX spent so far") on the green progress card.
- Per-trip free-text notes with debounced autosave and a soft fading "Saved" pill.

#### Finish a trip with style
- Confetti rains in your store's brand colors.
- Synthesised TADA plays automatically (drop your own `public/sounds/finish-trip.mp3` to override).
- 5-minute Undo window catches the panic-tap. Reverse-TADA plays when you undo.

#### Catalog and recipes
- ~80 pre-seeded Portuguese groceries with categories, default units/quantities, and store-preference hints.
- Auto-learning: buy an item at the same store more than three times, and the catalog silently updates the preferred-store hint.
- "Cook something" tab with built-in Portuguese meal bundles (Bacalhau à Braga, Caldo Verde, Churrasco, Feijoada...). One tap merges all ingredients into the current list.
- **Save as recipe**: turn the current list into your own reusable bundle, listed alongside the built-in ones with a "your recipe" badge.

#### Shared mode
- Each list has a UUID share token. The other person opens `/list/{token}` with no login.
- Shared view shows the same items, minus destructive actions and the bottom nav.
- Real-time sync via Laravel Reverb (optional): tick on one device, it ticks on the other within ~1s, with a soft notification chime.
- Shared user gets their own settings dropdown (text size, contrast, etc.) without any access to the owner's account.

#### History
- Every finished trip lands on `/history`, ordered most-recent first, with item-count and skipped-count summaries.
- One-tap **Restore** intelligently merges a past list into your active one: pending duplicates are skipped, bought duplicates are reset to pending, and the toast reports the three counts.
- Per-card download (JSON) and trash actions.

#### Print & export
- `GET /list/{token}/print` opens a no-nav, A4-friendly black-on-white printable view that auto-fires `window.print()`. Nice when you'd rather have paper.
- `GET /list/{token}/export.json` downloads a pretty-printed JSON snapshot of the list (name, store, status, notes, timestamps, full items) with a sensible filename.

### Accessibility (first-class)

A user-toggleable settings cluster lives in the profile dropdown, persisted to `localStorage`:

- **Text size** — three-step UI scale (default / large / extra large) that re-roots the entire `<html>` font-size, plus a separate "Larger list items" toggle for just the item rows.
- **High contrast** — darker muted text and stronger borders that lift WCAG contrast on the warm cream background.
- **Bigger buttons** — bumps every tappable element to a 44×44 minimum (WCAG 2.5.5 AAA).
- **Sound effects** — global mute for all synthesised cues.

System-level support:
- `prefers-reduced-motion` respected globally (animations and transitions disabled).
- `aria-label` on every icon-only button. `aria-current="page"` on the active bottom-nav tab. `aria-pressed` on the catalog selection grid. `role="alert"` on validation errors.
- `focus-visible` ring on every tappable element and form input for keyboard users.
- Light haptic feedback on item check-off (Android only).

### Internationalisation

- Every string lives in `lang/en/app.php` and `lang/pt_PT/app.php`. The owner can switch from the profile dropdown.
- `pt-PT` is the default locale; `en` is the fallback.
- Voice recognition adapts to the active locale.

### Progressive Web App

- `manifest.json` + theme color + apple-touch-icon meta tags so the app installs to the home screen on iOS and Android.
- Inline `<head>` script applies user font-size and contrast prefs before paint to avoid flash of unstyled content.
- Cream brand color (`#f1ebd9`) propagates through the manifest, browser chrome, and PWA splash.

### Audio cues

All synthesised live (no audio binaries shipped). Each respects the Sound effects mute toggle:

| Cue | When |
|---|---|
| TADA | Finish a trip |
| Reverse TADA | Undo finish trip |
| Two-note ascending chirp | Start voice dictation |
| Two-note descending chirp | Stop voice dictation |
| Quick high chirp | Tick / un-tick an item |
| Soft single ping | Other user changed the list (real-time) |
| Square-wave buzz | Validation error |

### Visual identity

- Warm cream `#f1ebd9` background, white cards with soft shadows, store-tinted action chips (Lidl yellow, Aldi blue, Continente red, Mercadona green).
- Serif page headings (Georgia), sans body, generous touch targets.
- **Phosphor Duotone** icons throughout (21 SVGs published in-repo as Flux icon stubs). Two-tone effect uses `currentColor` so a tap on a green button gives a green icon with a faint green back-fill.
- Animated mount/unmount of item rows. Tap-press scale on every button. Animated check-off slide. No motion if the user opted out at the OS level.

### Tech foundation

- **PHP 8.5**, **Laravel 13**, **Livewire 4**, **Tailwind CSS v4**, **Alpine.js** (bundled with Flux).
- **Flux UI v2 — Free edition only.** No proprietary dependencies. `composer install` works for any clone.
- **Phosphor Icons (Duotone variant)** for all icons.
- **Laravel Reverb v1** for optional real-time WebSockets.
- **Laravel Fortify v1** for the owner's auth (login only, no registration — single-user app by design).
- **Pest 4** for tests: 86 currently passing, 143 assertions, all run against in-memory SQLite.

### Deployment

Two supported topologies, both documented in [DEPLOYMENT.md](DEPLOYMENT.md):

1. **Laravel Cloud + PostgreSQL + optional Reverb** — managed, auto-TLS, recommended for production.
2. **Self-hosted VPS + SQLite + no Reverb** — single file backup, minimal ops, perfect for personal use.

### Install

```bash
git clone <repo-url> shopping-list && cd shopping-list
composer install && npm install
cp .env.example .env && php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
composer run dev
```

Visit your local URL, log in with the seeded credentials, change the password, share the link.

### Not in 1.0.0 (deliberate scope)

- **Charts / spending analytics over time.** Would need `flux:chart` (currently Pro-only); the price-history modal and per-trip total cover the immediate need.
- **Multi-owner support.** Single account by design; the share-link model handles the second person.
- **Native iOS/Android shells.** PWA install is the path.
- **Recipe templates marketplace.** "Cook something" + save-as-recipe is enough for a personal kitchen.

### License

MIT.

### Acknowledgements

- [Flux UI](https://fluxui.dev) by Caleb Porzio
- [Phosphor Icons](https://phosphoricons.com) (Duotone variant)
- [Laravel](https://laravel.com), [Livewire](https://livewire.laravel.com), [Tailwind CSS](https://tailwindcss.com)
