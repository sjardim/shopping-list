# Lista — Roadmap (Six Hats, post-1.2.0)

A "six thinking hats" pass on the current feature surface, with prioritised follow-ups.

## White (facts)

- 5 locales (`en`, `en_GB`, `pt_PT`, `pt_BR`, `es`) and 5 store regions (`pt`, `us`, `uk`, `br`, `es`).
- ~80 catalog items per region. 11 seeded history trips per region.
- 102 tests / 169 assertions passing (Pest 4).
- Stack: PHP 8.5, Laravel 13, Livewire 4, Flux Free, Tailwind v4, Reverb v1, Fortify v1.
- Single-owner auth (`is_admin` flag). Public share via UUID token.
- Per-item fields: name, emoji, qty (decimal 8,2), unit, preferred_store, price, is_bought.
- Real-time via Reverb. No polling fallback.
- Persisted prefs (localStorage): ui-scale, list-scale, contrast, big-targets, sound.
- `ShoppingListPage`: ~530 LOC component, ~600 LOC view (over the 300-line target).

## Red (gut)

- Feels overbuilt for "personal grocery list with wife".
- Polish ratio high: confetti, TADA, voice, haptics, PWA, print, JSON export.
- Every new locale balloons surface area (catalog, history, bundles, lang).
- Item row now noisy on a 390px viewport (checkbox, emoji, name, stepper, store chip, price, ×).
- Locale-vs-seeded-data mismatch (deferred) is going to bite again.

## Black (risks)

- **Data sprawl per locale**: 5 catalog seeders ≈ 400 hand-curated rows. Diverges fast. Already saw PT unit strings leaking into the EN seeder.
- **Stored-string i18n debt**: catalog name, item name, list name are all frozen at write time. No `locale` column, no normalised key. Locale switch silently shows wrong language for any seeded or persisted text.
- **Stepper has no ceiling, no batch input**: tap +50 on a g unit needs 50 taps to add 2.5kg. Long-press not wired.
- **Stepper edits broadcast nothing**: shared user does not see qty changes in real time. Toggle, add, remove broadcast. Quantity does not.
- **`ShoppingListPage` exceeds the 300-line policy**. Quality rule violated. Bug surface widening.
- **`switchLocale` redirects with `navigate: false`** plus a full reload. Loses scroll. Optimistic state is dropped. Acceptable, scrappy.
- **Reverb is "optional"** but no degraded-mode poll fallback. Shared mode silently stale if Reverb is down.
- **Price modal pulls top-10 historical prices via raw `DB::table` join** on every open. No pagination, no caching. Cheap now, painful at thousands of rows.
- **"Auto-learning preferred store"** mutates the catalog after 3 buys. No audit, no undo. Surprise behaviour.
- **`is_admin` boolean** has no role gradation. Schema cannot express two cooks.

## Yellow (benefits)

- Six-hats sequencing already paid off: clean separation of owner vs shared via `$mode`.
- Stepper plus per-locale units = right granularity for actual grocery shopping (½ kg cheese, 200 g spinach).
- Region/locale split was the right call. Flag emojis in installer and switcher convey intent without docs.
- `BaseShoppingHistorySeeder` template plus per-region subclass scales linearly. Adding FR is under a day.
- Test coverage on Livewire components (102 feature tests) means refactors are safe.
- Accessibility cluster (text-size, contrast, big targets, sound mute) is genuine WCAG-AAA work, rare in personal apps.
- PWA + print + JSON export = data portability. No lock-in.

## Green (alternatives, next moves)

- **Catalog locale column**: `catalog_items.locale` + a stable `name_key`. Render via current-locale lookup, fall back to stored. Solves the silently-deferred history problem properly.
- **Long-press stepper**: `wire:click.long` or Alpine `x-on:pointerdown` with `setInterval`. Accelerated +/− for weight units.
- **Quantity broadcast**: add `ItemQuantityChanged` event mirroring `ItemToggled`. Shared mode listens.
- **Split ShoppingListPage**: extract `QuickAddBar`, `ItemRow`, `PriceModal`, `RecipeModal` as child Livewire components or Blade `@props`. Shrink parent below 300 lines.
- **Numeric input on stepper tap-and-hold**: long-press opens a numeric keypad modal, tap-to-set instead of stepping.
- **Catalog-per-locale lookup table** instead of separate seeders: one `catalog_items` table plus `catalog_item_translations(locale, name)`. Adding a locale = translating, not re-curating.
- **Recipe sharing**: the same `share_token` model already exists. Expose user recipes via URL.
- **Bulk-add from text paste**: paste a multi-line list, parse into items. Frequent use case.
- **Barcode scan via WebRTC**: open camera, decode EAN, look up in catalog. Heavy but a wow feature.
- **Dark mode**: the cream background means light only. `prefers-color-scheme` is never wired.

## Blue (process, verdict)

**State**: feature-complete for v1 personal use. Ships. Nothing missing for the original brief (you and your wife, Lidl, Continente, Mercadona run).

**Top 3 follow-ups, ordered by ROI**:

1. **Quantity broadcast event** (~1h). Fixes a real bug introduced in this session. Shared user sees stepper changes.
2. **Component decomposition for `ShoppingListPage`** (~½ day). Pays off every future change. Pre-condition for any further feature.
3. **Catalog locale column + lookup** (~1 day). Unblocks the deferred locale-switch issue. Stops the seeder-divergence bleed.

**Defer**: dark mode, barcode, bulk-paste, long-press stepper. None block use.

**Architectural debt to name**: stored-text-as-source-of-truth. Every "switch locale" failure traces back here. Fix at the schema, not at the seeder.
