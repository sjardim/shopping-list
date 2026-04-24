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
    <circle cx="176" cy="200" r="32" opacity="0.2"/><circle cx="176" cy="56" r="32" opacity="0.2"/><line x1="149.09" y1="73.3" x2="90.91" y2="110.7" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><line x1="90.91" y1="145.3" x2="149.09" y2="182.7" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><circle cx="64" cy="128" r="32" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><circle cx="176" cy="200" r="32" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><circle cx="176" cy="56" r="32" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/>
</svg>
