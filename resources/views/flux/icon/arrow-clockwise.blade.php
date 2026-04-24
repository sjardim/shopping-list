@blaze(fold: true)

{{-- Credit: Phosphor Icons (https://phosphoricons.com), duotone variant --}}

@props([
    'variant' => 'outline',
])

@php
$classes = Flux::classes('shrink-0')
    ->add(match($variant) {
        'outline', 'solid' => '[:where(&)]:size-6',
        'mini' => '[:where(&)]:size-5',
        'micro' => '[:where(&)]:size-4',
    });
@endphp

<svg
    {{ $attributes->class($classes) }}
    data-flux-icon
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 256 256"
    fill="currentColor"
    aria-hidden="true"
    data-slot="icon"
>
    <circle cx="128" cy="128" r="88" opacity="0.2"/><polyline points="184 104 232 104 232 56" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><path d="M188.4,192a88,88,0,1,1,1.83-126.23L232,104" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/>
</svg>
