@props([
    'cols' => 1, // 1, 2, 3, 4
    'gap' => 6, // 4, 6, 8
])

@php
    $colsClass = match($cols) {
        1 => 'grid-cols-1',
        2 => 'grid-cols-1 md:grid-cols-2',
        3 => 'grid-cols-1 md:grid-cols-3',
        4 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4',
        default => 'grid-cols-1 md:grid-cols-2',
    };
    
    $gapClass = "gap-{$gap}";
    
    $classes = "grid {$colsClass} {$gapClass}";
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>