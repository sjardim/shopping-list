@props(['store'])

@php
    /** @var \App\Enums\Store $store */
@endphp

<span
    class="inline-flex items-center justify-center size-6 rounded-full text-xs font-bold"
    style="background-color: {{ $store->color() }}; color: {{ $store->hasDarkText() ? '#1a1a1a' : '#ffffff' }};"
    title="{{ $store->label() }}"
>
    {{ $store->initial() }}
</span>
