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
    <path d="M64,176H24V96c0-8.84,7.76-16,17.33-16H214.67C224.24,80,232,87.16,232,96v80H192V152H64Z" opacity="0.2"/><polyline points="64 80 64 40 192 40 192 80" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><rect x="64" y="152" width="128" height="64" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><path d="M64,176H24V96c0-8.84,7.76-16,17.33-16H214.67C224.24,80,232,87.16,232,96v80H192" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><circle cx="188" cy="116" r="12"/>
</svg>
