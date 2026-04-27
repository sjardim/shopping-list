# Accessibility and Preferences

A small preferences cluster lives in the profile dropdown (avatar, top right) and the gear menu (shared mode). Settings persist in `localStorage` per device for both the owner and the shared user.

## Text size

Three buttons labelled `A`, `A`, `A` (small, medium, large). Behind the scenes:

* Small (default): UI scale 1.00.
* Medium: UI scale 1.12.
* Large: UI scale 1.25, plus list scale bumps to 1.18.

UI scale changes the root `<html>` font size, so every rem based label scales with it. List scale bumps the dedicated `list-text` and `list-text-sm` utilities used for item rows.

## Larger list items toggle

Below the A buttons. A separate switch that bumps just the item rows (`list-text` and `list-text-sm`) without touching the rest of the UI. Useful when you want big list rows without making everything bigger.

## High contrast

Toggle. Darker muted text and stronger borders against the warm cream background, lifting WCAG contrast. Implemented via a `data-contrast="high"` attribute on `<html>` and a small CSS rule set targeting the muted greys and borders.

## Bigger buttons

Toggle. Every tappable element grows to a 44 by 44 pixel minimum (WCAG 2.5.5 AAA). Achieved via a `data-targets="big"` attribute on `<html>` and a `[data-targets="big"] .tap` rule that sets minimum width and height.

## Hide item emoji at max text size

Automatic. When the user picks the largest text size, the layout sets `data-text-size="max"` on `<html>`. A CSS rule hides the emoji container in shopping list rows so the name and stepper claim the freed space.

## Sound effects

Master mute for every synthesised cue:

| Cue | When |
|---|---|
| TADA | Finishing a trip |
| Reverse TADA | Undoing a finish trip |
| Two note ascending chirp | Starting voice dictation |
| Two note descending chirp | Stopping voice dictation |
| Quick high chirp | Ticking or unticking an item |
| Soft single ping | Other user changed the list (Reverb) |
| Square wave buzz | Validation error |

All synthesised live with the Web Audio API. Drop `public/sounds/finish-trip.mp3` to override the synthesised TADA with a real applause clip.

## Voice dictation

Two places in the UI use Web Speech:

* Quick add bar at the bottom of the home page.
* Notes field above the items list.

Tap the microphone, speak in your active locale, the text fills in. iOS Safari supports it; some browsers do not (the button hides itself if the API is not available).

## System level support

Beyond the toggleable cluster:

* `prefers-reduced-motion` is respected globally. Animations and transitions disable at the CSS level when the user has motion preference set.
* `aria-label` on every icon only button. `aria-current="page"` on the active bottom nav tab. `aria-pressed` on the catalog selection grid. `role="alert"` on validation errors.
* `focus-visible` ring on every tappable element and form input for keyboard users.
* Light haptic feedback (`navigator.vibrate?.(8)`) on item check off (Android only; iOS Safari ignores).

The inline `<head>` script applies font size, contrast, and big button preferences before paint, avoiding flash of unstyled content.

## Locale switching

Below the accessibility cluster in the profile dropdown:

* English (US flag emoji)
* English (UK flag)
* Português (Portugal flag)
* Português (Brazil flag)
* Español (Spain flag)

Tap any option to switch. Lista persists the choice in the session and applies it at runtime via `App::setLocale`. UI strings update immediately. Catalog and history names stay frozen at seed time (see [admin-and-setup.md](admin-and-setup.md)).
