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
    <circle cx="128" cy="128" r="96" opacity="0.2"/><circle cx="128" cy="128" r="96" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><polyline points="128 72 128 128 184 128" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/>
</svg>
