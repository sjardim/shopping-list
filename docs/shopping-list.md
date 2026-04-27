# The Active Shopping List

The home screen is the active list. Everything you do on a trip happens here.

## Header

* Title says "Shopping list".
* Below it sits the store selector. Tap it to pick the store you are shopping at (Lidl, Aldi, Continente, Mercadona, plus your region's chains). Tap again to change. The button takes the brand colour of the chosen store.
* The avatar on the right opens the profile menu (language, accessibility, share, export, print, save as recipe, sign out).

## Progress card

Visible whenever the list has items.

* Big number in the centre is "items left to buy".
* Bar fills as you tick items off.
* "Spent so far" appears once any bought item has a recorded price.
* "Finish trip" archives the active list and starts a fresh empty one. Confetti and a TADA chord on success.
* "Clear" empties the active list without archiving (use sparingly).
* If you have not picked a store yet, "Finish trip" prompts you to pick one first. This keeps preferred store hints accurate.

## Notes field

Above the items list. Free text, autosaves with a debounce. A small "Saved" pill fades in for two seconds after each save. The microphone button beside the field uses Web Speech for dictation in your active locale.

## Items list

Rows are grouped by category (Fruit, Vegetables, Dairy, etc.) with category emojis. Each row shows:

* A square checkbox. Tap to mark bought. The row slides off and reappears under "in cart".
* The item emoji (hidden at the largest text size to save space).
* The item name.
* A quantity stepper. Tap minus or plus to adjust. Steps are unit aware: 0.1 for kg/l, 50 for g/ml, 1 for everything else (units, packs, dozens). The minimum is the step size, so you cannot tap below it.
* A price chip. Empty shows `+€` (or your configured symbol). Filled shows the price you set.
* An x button to remove the item, with a confirm prompt.

## Setting prices

Tap the price chip on any item to open the price modal.

* Decimal input accepts both comma and period.
* The "Recent prices" list shows the last ten purchases of this catalog item across all your trips, with the store badge and date.
* The "Preferred store" picker shows every store in your active region plus a "No store" option. Tap any store to set it as the catalog item's preferred store. The current preference is highlighted in the brand colour. The same picker lets you clear the preference.

The auto learner also flips the preferred store after three priced or ticked off purchases at the same store.

## Adding items

The quick add bar at the bottom is always visible (owner only).

* Type any name. As you type two or more characters, catalog suggestions appear above the bar with their emoji and preferred store dot. Tap a suggestion to add it (faster than typing the rest).
* Press enter or tap the green plus to add the typed name as a free text item. Free text items have no catalog link, so they will not show price history or preferred store hints.
* The microphone button opens voice dictation in your active locale.

## Finishing a trip

* Tap "Finish trip" on the progress card.
* Confirms with the store name (or warns if no store is set).
* On success: list archives to History, a fresh active list is created, confetti rains, the TADA plays.
* You have a five minute undo window. The empty new list shows an "Undo finish trip" button until then. Undo restores the archived list as your active one and discards the empty replacement (preserved if you already added items to it).

## Bought items section

Once you tick items off they collect in an "in cart" section at the bottom of the list, struck through and dimmed. You can still tap to untick, or tap the price chip to record what you paid.
