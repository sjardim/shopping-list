@props([
    'item',
    'mode' => 'owner',
])

<div
    wire:key="bought-{{ $item['id'] }}"
    x-data="{ sliding: false }"
    x-bind:class="sliding && '-translate-x-full opacity-0 bg-stone-100'"
    class="flex items-center gap-3 px-4 py-3.5 fade-in-up transition-all duration-200 ease-out"
>
    <button
        x-on:click="navigator.vibrate?.(8); window.lista?.sounds?.toggle(); sliding = true; setTimeout(() => $wire.toggleItem({{ $item['id'] }}), 200)"
        class="shrink-0 size-6 rounded-sm bg-[#2f7d4f] flex items-center justify-center hover:bg-[#256b41] transition-colors tap"
        aria-label="{{ __('app.mark_unbought', ['name' => $item['name']]) }}"
    >
        <flux:icon name="check" class="size-3.5 text-white" />
    </button>

    <div class="shrink-0 size-10 rounded-md bg-[#f4f0e8]/60 flex items-center justify-center text-lg opacity-50">
        {{ $item['emoji'] ?: '🛒' }}
    </div>

    <p class="flex-1 font-semibold text-[#9b9080] line-through list-text">{{ $item['name'] }}</p>

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
    @endif
</div>
