@props([
    'name' => null,
    'placeholder' => 'Select an option',
    'options' => [],
])

@php
    $wireModel = $attributes->whereStartsWith('wire:model')->first();
    $fieldName = $wireModel ? str_replace(['wire:model=', 'wire:model.defer=', 'wire:model.live=', '"', "'"], '', $wireModel) : null;
@endphp

<div class="w-full">
    <select @if ($name) name="{{ $name }}" @endif
        {{ $attributes->merge([
            'class' => 'w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-200 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-600/20 focus:border-red-600 outline-none transition-all',
        ]) }}
        @if($fieldName) @class(['border-red-300 focus:border-red-500 focus:ring-red-200' => $errors->has($fieldName)]) @endif>
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        {{ $slot }}
    </select>

    @if($fieldName)
        @error($fieldName)
            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
        @enderror
    @endif
</div>