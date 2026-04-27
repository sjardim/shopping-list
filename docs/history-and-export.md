# History, Repeat, Export, Print

The History tab (`/history`) collects every finished trip. Eleven seeded trips ship with the install for shape, plus everything you finish over time.

## History list

Most recent first. Each card shows:

* Store badge (if the list had a store) plus the auto generated name (`Mercadona · 27 Apr`).
* Completion date and a count of items, with a "skipped" subcount in amber when some items were left unticked.
* A six item preview row (emoji plus name pills, struck through if unticked).
* Three icon actions: Export, Repeat, Delete.

## Repeat a list

Tap the circular arrow icon. Repeats the source list into the current active list with smart merging:

* Brand new item (not on active list): added.
* Pending duplicate (same name, not yet bought): left untouched.
* Bought duplicate (same name, already ticked off): reset to pending so it reappears on the active list.

A toast summarises the result: "Added 5, restored 2, skipped 1."

Tip: this is the right way to "redo" yesterday's shop.

## Export

Two ways to pull data out:

### Per list export

Each history card has a download icon. The owner profile menu also has "Export as JSON" for the active list.

URL pattern:
```
GET /list/{share_token}/export.json
```

Response is a pretty printed JSON snapshot:

```json
{
  "name": "Mercadona · 27 Apr",
  "store": "mercadona",
  "status": "completed",
  "completed_at": "2026-04-27T10:00:00Z",
  "notes": "...",
  "items": [
    {
      "name": "Bananas",
      "emoji": "🍌",
      "category": "fruta",
      "quantity": 1.5,
      "unit": "kg",
      "preferred_store": "lidl",
      "price": 1.39,
      "is_bought": true,
      "bought_at": "..."
    }
  ]
}
```

`Content-Disposition: attachment` triggers download with a slug based filename.

### Print view

Owner profile menu > Print, or copy this URL pattern:
```
GET /list/{share_token}/print
```

Renders a no nav, A4 friendly black on white printable view. Categorised, with big tickbox style checkboxes. The browser print dialog fires automatically on load. Falls back to the browser menu if it does not.

## Delete

Trash icon on the history card. Confirms before destroying. Deleting a completed list also removes its items and any associated price history they carried.

## Undo finish trip (different from repeat)

A five minute window after finishing a trip lets you undo from the home page (an "Undo finish trip" button on the empty new list). Restores the archived list as your active one and discards the empty replacement (kept if you already added items to it).

After five minutes the only way back is Repeat from the history tab.
