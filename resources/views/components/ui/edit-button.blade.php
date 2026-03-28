@props([
    'href' => '#',
    'size' => 'sm',
])

@php
    $sizeClasses = [
        'xs' => 'px-2 py-1 text-xs',
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-5 py-2.5 text-base',
    ];

    $classes = $sizeClasses[$size] ?? $sizeClasses['sm'];
@endphp

<a wire:navigate href="{{ $href }}"
    {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 bg-green-50 text-green-700 font-medium rounded-lg hover:bg-green-100 transition-colors {$classes}"]) }}>
    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
    </svg>
    {{ $slot->isEmpty() ? 'Edit' : $slot }}
</a>
