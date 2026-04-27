<flux:modal name="save-recipe" class="md:w-96">
    <form wire:submit="saveAsRecipe" class="space-y-5">
        <div>
            <flux:heading size="lg">{{ __('app.save_as_recipe') }}</flux:heading>
            <flux:subheading>{{ __('app.save_as_recipe_hint') }}</flux:subheading>
        </div>

        <flux:field>
            <flux:label>{{ __('app.recipe_name') }}</flux:label>
            <flux:input wire:model="newRecipeName" autofocus />
            <flux:error name="newRecipeName" />
        </flux:field>

        <flux:field>
            <flux:label>{{ __('app.recipe_emoji') }}</flux:label>
            <flux:input wire:model="newRecipeEmoji" maxlength="8" class="!text-2xl !text-center" />
            <flux:error name="newRecipeEmoji" />
        </flux:field>

        <div class="flex justify-end gap-2">
            <flux:modal.close>
                <flux:button variant="ghost">{{ __('app.cancel') }}</flux:button>
            </flux:modal.close>
            <flux:button type="submit" variant="primary">{{ __('app.save') }}</flux:button>
        </div>
    </form>
</flux:modal>
