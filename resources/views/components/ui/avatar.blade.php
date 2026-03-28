@props([
    'name' => '',
    'variant' => 'primary',
])

@php
    $initials = collect(explode(' ', $name))
        ->map(fn($word) => strtoupper(substr($word, 0, 1)))
        ->take(2)
        ->join('');
    
    $variants = [
        'primary' => 'bg-red-50 text-university-red border-red-100',
        'secondary' => 'bg-gray-100 text-gray-600 border-gray-200',
    ];
    
    $classes = 'h-9 w-9 rounded-full flex items-center justify-center font-bold text-xs border ' . ($variants[$variant] ?? $variants['primary']);
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $initials }}
</div>