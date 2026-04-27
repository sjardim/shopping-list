@props([
    'editing' => null,
    'priceHistory',
    'listStore' => null,
])

<flux:modal name="edit-price" class="md:w-96">
    @if($editing)
        @php $editingStore = $editing->preferred_store ? \App\Support\Stores::tryFrom($editing->preferred_store) : null; @endphp
        <form wire:submit="submitPrice" class="space-y-5">
            <div>
                <flux:heading size="lg" class="flex items-center gap-2">
                    <span>{{ $editing->emoji ?: '🛒' }}</span>
                    <span>{{ $editing->name }}</span>
                </flux:heading>
                @if($editingStore)
                    <span class="flex items-center gap-1 text-[#6b6055] list-text-sm min-w-0">
                        <span class="size-2 rounded-full shrink-0" style="background-color: {{ $editingStore->color() }}"></span>
                        <span class="truncate">{{ __('app.usually', ['store' => $editingStore->label()]) }}</span>
                    </span>
                @endif
                <flux:subheading>{{ __('app.set_price_prompt', ['name' => $editing->name, 'currency' => config('lista.currency.symbol')]) }}</flux:subheading>
            </div>

            <flux:field>
                <flux:label>{{ __('app.price_label', ['currency' => config('lista.currency.symbol')]) }}</flux:label>
                <flux:input wire:model="editingPrice" type="text" inputmode="decimal" autofocus placeholder="0.00" />
            </flux:field>

            @if($listStore && $editing->catalog_item_id !== null && $editing->preferred_store !== $listStore->value)
                <button
                    type="button"
                    wire:click="markPreferredStore({{ $editing->id }})"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-md border border-[#ede8df] bg-[#f7f3ec] px-3 py-2 text-sm font-medium text-[#1a1a1a] hover:bg-[#ede8df] tap"
                >
                    <span class="size-2.5 rounded-full" style="background-color: {{ $listStore->color() }}"></span>
                    {{ __('app.mark_preferred_store', ['store' => $listStore->label()]) }}
                </button>
            @endif

            @if($priceHistory->isNotEmpty())
                <div class="space-y-2">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-[#6b6055]">{{ __('app.price_history') }}</p>
                    <div class="rounded-md border border-[#ede8df] divide-y divide-[#f4f0e8] overflow-hidden">
                        @foreach($priceHistory as $entry)
                            @php $entryStore = $entry->store ? \App\Support\Stores::tryFrom($entry->store) : null; @endphp
                            <div class="flex items-center justify-between gap-3 px-3 py-2 text-sm">
                                <span class="flex items-center gap-2 min-w-0">
                                    @if($entryStore)
                                        <x-store-badge :store="$entryStore" />
                                    @else
                                        <span class="size-5"></span>
                                    @endif
                                    <span class="text-[#1a1a1a] truncate">{{ $entryStore?->label() ?? __('app.no_store') }}</span>
                                </span>
                                <span class="flex items-center gap-3 shrink-0">
                                    <span class="text-xs text-[#6b6055]">{{ $entry->bought_at->format('d M Y') }}</span>
                                    <span class="font-semibold text-[#2f7d4f]">{{ config('lista.currency.symbol') }}{{ number_format($entry->price, 2) }}</span>
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @elseif($editing->catalog_item_id !== null)
                <p class="text-xs text-[#6b6055] italic">{{ __('app.no_price_history') }}</p>
            @endif

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">{{ __('app.cancel') }}</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary">{{ __('app.save') }}</flux:button>
            </div>
        </form>
    @endif
</flux:modal>
