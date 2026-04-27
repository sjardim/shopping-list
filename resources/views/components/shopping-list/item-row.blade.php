@props([
    'item',
    'mode' => 'owner',
])

@php
    $storeEnum = $item['preferred_store'] ? \App\Support\Stores::tryFrom($item['preferred_store']) : null;
    $quantityLabel = rtrim(rtrim(number_format((float) $item['quantity'], 2, '.', ''), '0'), '.') . ' ' . $item['unit'];
@endphp

<div
    wire:key="item-{{ $item['id'] }}"
    x-data="{ sliding: false }"
    x-bind:class="sliding && '-translate-x-full opacity-0 bg-stone-100'"
    class="flex items-center gap-3 px-4 py-3.5 fade-in-up transition-all duration-200 ease-out"
>
    <button
        x-on:click="navigator.vibrate?.(8); window.lista?.sounds?.toggle(); sliding = true; setTimeout(() => $wire.toggleItem({{ $item['id'] }}), 200)"
        class="shrink-0 size-6 rounded-sm border-2 border-[#d5cdbc] hover:border-[#2f7d4f] transition-colors tap"
        aria-label="{{ __('app.mark_bought', ['name' => $item['name']]) }}"
    ></button>

    <div class="item-emoji shrink-0 size-10 rounded-md bg-[#f4f0e8] flex items-center justify-center text-lg">
        {{ $item['emoji'] ?: '🛒' }}
    </div>

    <article class="flex-1 min-w-0">
        <header class="flex items-center justify-between gap-2 flex-wrap">
            <p class="font-semibold text-[#1a1a1a] list-text">{{ $item['name'] }}</p>
        </header>
        <footer class="flex items-center gap-2 mt-1 flex-wrap">
            @if($mode === 'owner')
                <div class="flex items-center gap-1">
                    <button
                        type="button"
                        wire:click="decrementQuantity({{ $item['id'] }})"
                        class="size-6 rounded-full bg-[#f4f0e8] hover:bg-[#e0d9cc] flex items-center justify-center text-[#1a1a1a] tap"
                        aria-label="{{ __('app.decrease_quantity', ['name' => $item['name']]) }}"
                    >
                        <flux:icon name="minus" class="size-3" />
                    </button>
                    <span class="list-text-sm font-semibold text-[#1a1a1a] min-w-[2.75rem] text-center tabular-nums">
                        {{ $quantityLabel }}
                    </span>
                    <button
                        type="button"
                        wire:click="incrementQuantity({{ $item['id'] }})"
                        class="size-6 rounded-full bg-[#f4f0e8] hover:bg-[#e0d9cc] flex items-center justify-center text-[#1a1a1a] tap"
                        aria-label="{{ __('app.increase_quantity', ['name' => $item['name']]) }}"
                    >
                        <flux:icon name="plus" class="size-3" />
                    </button>
                </div>
            @else
                <span class="text-[#9b9080] list-text-sm">{{ $quantityLabel }}</span>
            @endif
            @if($storeEnum)
                <span class="flex items-center gap-1 text-[#6b6055] list-text-sm min-w-0">
                    <span class="size-2 rounded-full shrink-0" style="background-color: {{ $storeEnum->color() }}"></span>
                    <span class="truncate">{{ __('app.usually', ['store' => $storeEnum->label()]) }}</span>
                </span>
            @endif
        </footer>
    </article>

    @if($mode === 'owner')
        <button
            type="button"
            wire:click="openPriceEditor({{ $item['id'] }})"
            class="shrink-0 text-xs font-semibold tap rounded-full px-2.5 py-1 transition-colors {{ $item['price'] ? 'bg-[#e3ede7] text-[#2f7d4f]' : 'bg-[#f4f0e8] text-[#9b9080]' }}"
            aria-label="{{ __('app.set_price', ['name' => $item['name']]) }}"
        >
            @if($item['price'])
                {{ config('lista.currency.symbol') }}{{ number_format((float) $item['price'], 2) }}
            @else
                +{{ config('lista.currency.symbol') }}
            @endif
        </button>
        <button
            wire:click="removeItem({{ $item['id'] }})"
            wire:confirm="{{ __('app.remove_confirm', ['name' => $item['name']]) }}"
            class="shrink-0 size-7 rounded-full flex items-center justify-center text-[#c5bdb0] hover:text-[#e53935] hover:bg-red-50 transition-colors tap"
            aria-label="{{ __('app.remove_item', ['name' => $item['name']]) }}"
        >
            <flux:icon name="x" class="size-3.5" />
        </button>
    @endif
</div>
