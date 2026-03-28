@props([
    'variant' => 'primary', 
    'size' => 'md', 
    'icon' => null,
    'iconPosition' => 'left', 
    'href' => null,
    'type' => 'button',
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-semibold transition rounded-lg active:scale-95';
    
    $variants = [
        'primary' => 'bg-university-red text-white hover:bg-red-700 shadow-sm',
        'success' => 'bg-green-50 text-green-700 border border-green-200 hover:bg-green-100',
        'danger' => 'bg-red-50 text-red-700 border border-red-200 hover:bg-red-100',
        'secondary' => 'bg-gray-50 text-gray-700 border border-gray-200 hover:bg-gray-100',
    ];
    
    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];
    
    $iconSizes = [
        'sm' => 'w-3.5 h-3.5',
        'md' => 'w-4 h-4',
        'lg' => 'w-5 h-5',
    ];
    
    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($href)
    <a href="{{ $href }}" wire:navigate {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon && $iconPosition === 'left')
            <span class="{{ $iconSizes[$size] }} {{ $slot->isEmpty() ? '' : 'mr-2' }}">
                {!! $icon !!}
            </span>
        @endif
        
        {{ $slot }}
        
        @if($icon && $iconPosition === 'right')
            <span class="{{ $iconSizes[$size] }} {{ $slot->isEmpty() ? '' : 'ml-2' }}">
                {!! $icon !!}
            </span>
        @endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon && $iconPosition === 'left')
            <span class="{{ $iconSizes[$size] }} {{ $slot->isEmpty() ? '' : 'mr-2' }}">
                {!! $icon !!}
            </span>
        @endif
        
        {{ $slot }}
        
        @if($icon && $iconPosition === 'right')
            <span class="{{ $iconSizes[$size] }} {{ $slot->isEmpty() ? '' : 'ml-2' }}">
                {!! $icon !!}
            </span>
        @endif
    </button>
@endif