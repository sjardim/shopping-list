# Sharing and Real Time Sync

Lista is a single owner app, but the active list can be shared with one collaborator (typically a partner) via a public URL. They never see your account.

## Share URL

Every list has a UUID `share_token`. The full URL looks like:

```
https://your-domain/list/{share_token}
```

You can open the share menu in three ways:

* Profile menu (avatar, top right) > Share list. Copies the URL to the clipboard and shows a toast.
* On the public page itself, the gear icon (top right) has the same Share entry.
* The token never changes for the active list, so the URL stays bookmarkable.

## Shared mode (the partner's view)

The shared page shows the same items, categories, progress card, and notes. What is hidden:

* No "Finish trip" or "Clear" buttons.
* No "Remove" x on items. (Toggle bought, yes. Delete, no.)
* No quick add bar at the bottom. The shared user cannot create items, only check them off.
* No bottom navigation. The page is a single focused list.
* No price chips, no save as recipe, no profile menu. Just the gear (text size, contrast, big buttons, sound) and the share button.

The owner stays the only one who can add, remove, set prices, finish, or clear.

## Real time sync (Reverb)

Reverb is opt in. By default the app syncs via standard Livewire HTTP responses (each action saves and the next render reflects it). Switching to Reverb upgrades the shared session to true push: tick an item on one device, the other updates within about a second with a soft chime.

### Enabling Reverb

Add to your `.env`:

```
REVERB_ENABLED=true
VITE_REVERB_ENABLED="${REVERB_ENABLED}"
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST="localhost"
REVERB_PORT=8080
REVERB_SCHEME=http
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

Then rebuild assets and start the WebSocket server:

```bash
npm run build
php artisan reverb:start
```

In dev, `composer run dev` starts Vite, the queue worker, Pail, and Reverb together via concurrently.

### What gets broadcast

Four events ride the channel `shopping.{share_token}`:

* `ItemAdded` (owner adds an item)
* `ItemRemoved` (owner removes an item)
* `ItemToggled` (either side ticks)
* `ItemQuantityChanged` (owner uses the stepper)

Each carries a small payload (item id, name, quantity, etc.). The Livewire components listen via `#[On('echo:shopping.{shareToken},...')]` and force a partial refresh, then play a "list updated" toast and a soft ping.

### When Reverb is off

The app stays usable. The shared user sees changes on next render (after their own action, or after a manual refresh). Toggling, adding, and removing all still work via standard Livewire round trips.
