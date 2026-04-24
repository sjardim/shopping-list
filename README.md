# Lista — Shared Grocery Shopping List

A small, opinionated grocery shopping app built for two people who share a kitchen. One person owns the list, the other tags along on a shared link. No accounts to create, no clutter, just a list, a checkbox, and a few quality-of-life touches that make the weekly trip feel less like a chore.

Built on Laravel 13, Livewire 4, Tailwind v4, and Flux UI (Free edition only).

## Features

### Owner experience
- Active shopping list grouped by category, with category emoji headers.
- Quick-add bar with debounced catalog search ("ban..." suggests Bananas) and voice dictation via the Web Speech API.
- Tap-to-check items slide off-screen left with a brief stone tint, a synthesised chirp, and a soft haptic on Android.
- Tap-to-edit price per item: opens a modal showing the current price plus the last 10 prices recorded for that item across any past trip, with the store badge and date.
- Running trip total ("€X.XX spent so far") on the progress card.
- Per-trip free-text notes with debounced autosave and a fading "Saved" pill.
- Store selector tinted to the brand color (Lidl yellow, Aldi blue, Continente red, Mercadona green).
- "Finish trip" archives the active list and creates a fresh one. Confetti and a synthesised TADA mark the moment, and a 5-minute Undo window lets you reverse it. Optionally, drop your own `public/sounds/finish-trip.mp3` and the JS plays that instead of the synth.

### Catalog and recipes
- Pre-seeded Portuguese-grocery catalog with categories, default units/quantities, and learned preferred-store hints (e.g. "usually Continente").
- Auto-learning rule: when an item is bought at the same store more than three times, the catalog's preferred store updates silently.
- "Cook something" tab bundles common Portuguese meals (Bacalhau à Braga, Caldo Verde, Churrasco, Feijoada, etc.) into one-tap add-to-list actions.
- Save-as-recipe: turn the current list into a reusable custom bundle that lives next to the built-in ones.

### Shared mode
- Each list has a UUID share token. The wife (or whoever) opens `/list/{token}` with no login.
- The shared view shows everything the owner sees, minus destructive actions and the bottom nav.
- Real-time sync via Laravel Reverb: ticking an item on one device updates the other within a second, with a soft notification chime and a toast.
- A small "share again" / settings dropdown gives the shared user access to text-size, contrast, and other preferences without exposing the owner's account.

### History
- Every finished trip lands on `/history`, ordered most-recent first, with item-count and skipped-count summaries.
- One-tap **Restore** merges a past list into your active one. Pending duplicates are skipped silently; bought duplicates are reset to pending. The toast reports the three counts ("added 5, restored 2, skipped 1").
- Each card has download (export JSON) and trash buttons.

### Accessibility (a11y)
A user-toggleable preferences cluster lives in the profile dropdown, persisted to `localStorage`:

- **Text size** — three-step scale (default / large / extra large) that re-roots the entire UI font-size, plus a separate "Larger list items" toggle for just the item rows.
- **High contrast** — darker muted text and stronger borders that lift WCAG contrast on the warm cream background.
- **Bigger buttons** — bumps every tappable element to a 44×44 minimum (WCAG 2.5.5 AAA).
- **Sound effects** — mute toggle for all synthesised cues (TADA, undo, error buzz, item check chirp, voice toggle chirps, real-time update ping).

System-level support:
- `prefers-reduced-motion` respected globally (animations and transitions disabled).
- `aria-label` on every icon-only button. `aria-current="page"` on the active bottom-nav tab. `aria-pressed` on the catalog selection grid. `role="alert"` on validation errors.
- `focus-visible` ring on every tappable element and form input for keyboard users.
- Light haptic feedback (`navigator.vibrate?.(8)`) on item check-off (Android only; iOS Safari ignores).

### Internationalisation
- Every string lives in `lang/en/app.php` and `lang/pt_PT/app.php`. The owner can switch between them from the profile dropdown.
- `pt-PT` is the default locale; `en` is the fallback.

### Export and printing
- `GET /list/{token}/export.json` downloads a pretty-printed JSON snapshot of the list (name, store, status, notes, timestamps, full items) with a sensible `Content-Disposition` filename. Available from the owner profile dropdown and as a download icon on every history card.
- `GET /list/{token}/print` opens a no-nav, A4-friendly black-on-white printable view that auto-fires `window.print()` on load. Nice when the wife prefers paper.

### Progressive Web App
- `manifest.json` + cream theme color + apple-touch-icon meta tags so the app installs to the home screen on iOS and Android with the right title, icon, and full-screen treatment.
- `prefers-reduced-motion` and `prefers-color-scheme` respected. Inline `<head>` script applies user font-size and contrast prefs before paint to avoid flash of unstyled content.

## Tech stack

- **PHP 8.5** + **Laravel 13**
- **Livewire 4** for reactive components, **Alpine.js** (bundled with Flux) for client-side touches
- **Tailwind CSS v4** with custom utilities (`tap`, `fade-in-up`, `check-pulse`, `list-text`)
- **Flux UI v2** (Free edition only — see component list below)
- **Laravel Reverb v1** for WebSocket broadcasts (optional)
- **Laravel Fortify v1** for the owner's auth (no registration, login only)
- **Pest 4** for tests (86 currently passing)
- **Phosphor Duotone** icons (21 SVGs published as Flux icon stubs)

## Flux UI components used

Every component this app uses is in the **Free edition** of Flux UI. `composer install` works for any clone of the repo without a Flux Pro license.

| Component | Variant(s) used |
|---|---|
| `flux:button` | primary, ghost, with icon, with `href` |
| `flux:dropdown` + `flux:menu` + `flux:menu.item` + `flux:menu.separator` | profile menu, store picker, settings |
| `flux:modal` + `flux:modal.trigger` + `flux:modal.close` | save-as-recipe, edit-price |
| `flux:icon` | for all 21 Phosphor Duotone stubs |
| `flux:input`, `flux:label`, `flux:field`, `flux:error` | recipe form, price form, search |
| `flux:checkbox` | (not actively used in views; available) |
| `flux:heading`, `flux:subheading` | modal headings |
| `flux:card` | (not actively used; available) |
| `flux:toast` | global toast container, top-center, full-width on mobile |

## Quick start

```bash
git clone <your-fork-url> shopping-list
cd shopping-list
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite     # default SQLite path
php artisan migrate --seed
composer run dev
```

Visit `http://shopping-list.test` (Laravel Herd) or `http://127.0.0.1:8000` (artisan serve) and log in with `sergio@sergiojardim.com` / `secret`. Change the password immediately via tinker (see [DEPLOYMENT.md](DEPLOYMENT.md#changing-the-owner-password)).

`composer run dev` starts Vite, Reverb, the queue worker, and Pail in parallel.

## Tests

```bash
php artisan test --compact
```

86 tests, ~140 assertions. Tests run against an in-memory SQLite database, so no setup needed.

## Deployment

See [DEPLOYMENT.md](DEPLOYMENT.md) for the full guide. Three supported topologies:

1. **Laravel Cloud + PostgreSQL + Reverb** — the recommended production setup, covered in detail.
2. **Self-hosted VPS + SQLite + no Reverb** — the simplest possible deploy. Real-time sync is disabled, but everything else (history, recipes, prices, prints, exports) works. SQLite is the project's local default and the test environment, so nothing app-side needs to change.
3. **Anything in between** — Laravel Forge, custom Docker, etc. The app has no special infrastructure requirements beyond a PHP runtime, a database supported by Laravel, and (optionally) a WebSocket server.

## Project layout

```
app/
  Concerns/      BroadcastsToOthers trait (guards toOthers() against undefined socket IDs)
  Enums/         Store, Category, ShoppingListStatus
  Events/        ItemAdded, ItemRemoved, ItemToggled (broadcast events)
  Http/Controllers/  ListExportController, ListPrintController
  Livewire/      ShoppingListPage, AddItemsPage, ListHistoryPage
  Models/        User, ShoppingList, ShoppingListItem, CatalogItem, MealRecipe
  Support/       MealBundles (static config for built-in recipes)

database/
  migrations/    Standard Laravel migrations
  seeders/       CatalogItemSeeder (~80 Portuguese groceries),
                 ShoppingHistorySeeder (11 fake completed trips for testing)
  factories/     For all models with tests

resources/
  css/app.css    Tailwind v4 + custom utilities + a11y CSS
  js/app.js      Echo setup, Phosphor icon stubs, sounds module, voiceInput Alpine helper
  views/
    flux/icon/    21 Phosphor Duotone stubs
    livewire/     ShoppingListPage, AddItemsPage, ListHistoryPage
    components/   bottom-nav, store-badge, text-size-controls
    layouts/app.blade.php
    list-print.blade.php

tests/Feature/   Per-component happy-path + edge-case tests
```

## License

Open-source under the [MIT License](LICENSE) when you publish it. Add a `LICENSE` file before tagging a public release.

## Acknowledgements

- [Flux UI](https://fluxui.dev) by Caleb Porzio
- [Phosphor Icons](https://phosphoricons.com) (Duotone variant)
- [Laravel](https://laravel.com), [Livewire](https://livewire.laravel.com), [Tailwind CSS](https://tailwindcss.com)
