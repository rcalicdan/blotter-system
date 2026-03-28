@props([
    'variant' => 'primary', 
])

@php
    $variants = [
        'primary' => 'bg-red-50 text-university-red border-red-100',
        'success' => 'bg-green-50 text-green-700 border-green-100',
        'info' => 'bg-blue-50 text-blue-700 border-blue-100',
        'warning' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
        'danger' => 'bg-red-50 text-red-700 border-red-100',
    ];
    
    $classes = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border ' . ($variants[$variant] ?? $variants['info']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>