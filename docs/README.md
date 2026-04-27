# Lista User Guide

Lista is a personal grocery shopping list for you and one other person (typically a partner). Active list, real time sharing, price tracking, history, and a small accessibility cluster. Built on Laravel 13, Livewire 4, Flux UI, and Tailwind v4.

## Quick start

1. Install dependencies and run the interactive setup. Picks language, region, currency, and admin credentials in one shot.
   ```bash
   composer install && npm install
   php artisan lista:install
   ```
2. Start the dev stack (Vite, queue, Pail, optional Reverb).
   ```bash
   composer run dev
   ```
3. Open the app at `https://shopping-list.test` (Herd) or your configured URL. Log in with the credentials you typed during install.

## What you can do, in 60 seconds

* Type into the bar at the bottom of the home screen to add an item. The bar suggests catalog matches as you type.
* Tap an item to mark it bought. The row slides off and reappears in the "in cart" section.
* Tap the price chip on any item to record what you paid. The modal also shows the last ten prices for that catalog item across all your trips.
* Tap "Finish trip" on the green progress card when you are done. The list archives to History and a fresh one starts. You have five minutes to undo.
* Send the list URL (profile menu > Share list) to your partner. They open it without an account and can tick items off as they go.

## Where to read more

| Topic | File |
|---|---|
| The active list, prices, quantity stepper, finish trip | [shopping-list.md](shopping-list.md) |
| Catalog search, voice input, recipes, meal bundles | [catalog-and-recipes.md](catalog-and-recipes.md) |
| Share URLs, shared mode, real time sync via Reverb | [sharing-and-realtime.md](sharing-and-realtime.md) |
| History tab, repeat, JSON export, print | [history-and-export.md](history-and-export.md) |
| Text size, contrast, big buttons, sound, locale switch | [accessibility.md](accessibility.md) |
| Setup wizard, admin user, languages and regions, Reverb | [admin-and-setup.md](admin-and-setup.md) |
| Stack, file layout, conventions for contributors | [architecture.md](architecture.md) |

## Need a one page reference?

Read [`/FEATURES.md`](../FEATURES.md) at the project root for the complete feature index, including audio cues and PWA install notes.
