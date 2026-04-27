# Catalog and Recipes

Lista ships a catalog of about 80 grocery items per region and ten meal bundles per locale. The Add Items page (`/add`) is where you browse and pick from them.

## Catalog grid

* Items are grouped by category (Fruit, Vegetables, Dairy, Meat, Pantry, Bakery, Drinks, Cleaning, Hygiene, Other).
* Each tile shows the item emoji and name. Selected tiles get a green check badge and a tinted background.
* Selecting items from the grid does not add them right away. Tap "Done" in the header to add every selected tile to your active list in one batch.

## Search

Top of the page. Debounced, locale aware. As you type the grid filters to matching items in the active locale's catalog (Spanish catalog when locale is `es`, Portuguese for `pt_PT`, etc.).

If no items match, you see an empty state with the searched term.

## Voice dictation

The microphone button on the search field opens Web Speech in your active locale. Speak the item name, the search bar fills, the grid filters.

## "Cook something" tab

Switch tabs in the header. Two sections show.

### Your saved recipes

Your own saved recipes appear first, each with a green "your recipe" badge.

* Tap the plus to merge every ingredient into the active list. Items already on the list are skipped. Catalog matches are linked so the auto learner picks them up.
* Tap the trash icon to delete the recipe.

### Built in meal bundles

Below your recipes, ten meal bundles per locale (Portuguese: Frango Assado, Bacalhau à Braga, Caldo Verde, Churrasco, Massa Bolonhesa, Arroz de Frango, Sopa de Legumes, Feijoada, Ovos Mexidos, Salada Mista. English, Brazilian, and Spanish bundles ship in the same shape.)

* Tap the plus to merge the bundle's ingredients into the active list, same skip logic as recipes.

### Saving a recipe

From the home screen, open the profile menu and tap "Save as recipe". Type a name and pick an emoji. The current list's items become a custom bundle that appears in the Cook tab next to the built ins.

Bundle keys are stable across locale switches, so a recipe you saved while using `pt_PT` continues to work when you flip to `en`.

## Catalog locale tagging

Every catalog row is tagged with the locale that seeded it (`en`, `en_GB`, `pt_PT`, `pt_BR`, `es`). Search and category filters respect the current app locale strictly. Switching locale to one without a seeded catalog is a no op until you reseed for that locale (see [admin-and-setup.md](admin-and-setup.md)).
