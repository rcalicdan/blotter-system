@props([
    'type' => 'text',
    'name' => null,
    'placeholder' => '',
    'icon' => null,
    'toggleIcon' => null,
])

@php
    $wireModel = $attributes->whereStartsWith('wire:model')->first();
    $fieldName = $wireModel ? str_replace(['wire:model=', 'wire:model.defer=', 'wire:model.live=', '"', "'"], '', $wireModel) : null;
    $paddingClass = ($icon ? 'pl-10 ' : 'pl-4 ') . ($toggleIcon ? 'pr-10' : 'pr-4');
@endphp

<div class="w-full" {{ $attributes->only('x-data') }}>
    <div class="relative">
        @if ($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 z-10">
                {!! $icon !!}
            </div>
        @endif

        <input 
            :type="typeof show !== 'undefined' ? (show ? 'text' : '{{ $type }}') : '{{ $type }}'" 
            @if ($name) name="{{ $name }}" @endif
            placeholder="{{ $placeholder }}"
            {{ $attributes->except(['x-data'])->merge([
                'class' => "w-full py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-university-red/20 focus:border-university-red transition-all outline-none $paddingClass"
            ]) }}
            @if($fieldName) @class(['border-red-400 focus:border-red-500 focus:ring-red-100' => $errors->has($fieldName)]) @endif
        >

        @if ($toggleIcon)
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center z-10">
                {!! $toggleIcon !!}
            </div>
        @endif
    </div>

    @if($fieldName)
        @error($fieldName)
            <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
        @enderror
    @endif
</div>