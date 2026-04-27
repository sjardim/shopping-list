# Lista — Shared Grocery Shopping List

A small, opinionated grocery shopping app built for two people who share a kitchen. One person owns the list, the other tags along on a shared link. No accounts to create, no clutter, just a list, a checkbox, and a few quality-of-life touches that make the weekly trip feel less like a chore.

Built on Laravel 13, Livewire 4, Tailwind v4, and Flux UI (Free edition only).

## At a glance

- **Categorised list** with tap-to-check and a running price total.
- **Share link, no login** — the second user opens `/list/{token}` and sees the same list.
- **Real-time sync** via Laravel Reverb (optional).
- **Voice dictation** on the quick-add bar and notes field.
- **Price history** per item per store — every bought item feeds the history.
- **Saved recipes** alongside built-in meal bundles like "Bacalhau à Braga" or "Spaghetti Bolognese".
- **Confetti and a TADA chord** when you finish a trip. **Undo** for 5 minutes.
- **Full accessibility cluster**: text scaling (UI + list-only), high contrast, 44×44 tap targets, sound mute, `prefers-reduced-motion`.
- **Export JSON** and **print A4** for any list.
- **Five locales** (`en`, `en_GB`, `pt_PT`, `pt_BR`, `es`) and **five store regions** (`pt`, `us`, `uk`, `br`, `es`), each with a pre-seeded catalog and fake history.
- **PWA-ready** — installs to the home screen.
- **96 Pest tests**, runs on SQLite in memory.

Full feature list with all the details in [FEATURES.md](FEATURES.md). User guide split by topic in [docs/](docs/README.md).

## Install

### Fresh install (interactive)

```bash
git clone <your-fork-url> shopping-list
cd shopping-list
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite        # default SQLite path
npm run build
php artisan lista:install             # interactive setup
composer run dev
```

`php artisan lista:install` walks you through four prompts — language, store region, currency symbol, admin email/name/password — then writes env, runs migrations, and seeds the matching catalog/history pair. Idempotent, safe to re-run.

Defaults pair sensibly: pick `English (UK)` and it proposes UK region + `£`; pick `Português (Brasil)` and it proposes BR region + `R$`; pick `Español` and it proposes ES region + `€`. You can override any default.

### Non-interactive install (dev defaults)

If you'd rather just poke around without the setup wizard:

```bash
php artisan migrate --seed
```

This creates `admin@example.com` / `password` (rotate immediately) and seeds the PT catalog + history. Edit `database/seeders/DatabaseSeeder.php` if you want a different pair.

### Admin management

- Create a second admin or promote an existing user: `php artisan lista:make-admin <email>` (prompts for anything missing).
- Rotate an admin password: `php artisan tinker --execute 'App\Models\User::admins()->first()->update(["password" => bcrypt("new-password")])'`.

### Requirements

- PHP 8.3+ (8.5 recommended)
- Node 20+
- SQLite (default, zero-config) or PostgreSQL — see [DEPLOYMENT.md](DEPLOYMENT.md) for trade-offs
- Laravel Reverb is optional; the app works fully without real-time sync

## Running locally

```bash
composer run dev
```

Starts the HTTP server, Vite, Reverb, the queue worker, and Pail (log viewer) in parallel.

Visit [http://shopping-list.test](http://shopping-list.test) (Laravel Herd) or [http://127.0.0.1:8000](http://127.0.0.1:8000) (artisan serve) and log in with the credentials you set during install.

## Testing

```bash
php artisan test --compact
```

96 tests, ~163 assertions, all against in-memory SQLite. No setup required.

## Deployment

Two supported topologies, both covered in detail in [DEPLOYMENT.md](DEPLOYMENT.md):

1. **Laravel Cloud + PostgreSQL + optional Reverb** — managed, auto-TLS, recommended for production.
2. **Self-hosted VPS + SQLite + no Reverb** — single file backup, minimal ops, perfect for personal use.

## License

[MIT](LICENSE). Add a `LICENSE` file before tagging a public release.

## Acknowledgements

- [Flux UI](https://fluxui.dev) by Caleb Porzio
- [Phosphor Icons](https://phosphoricons.com) (Duotone variant)
- [Laravel](https://laravel.com), [Livewire](https://livewire.laravel.com), [Tailwind CSS](https://tailwindcss.com)
