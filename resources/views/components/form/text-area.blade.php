@props([
    'name' => null,
    'placeholder' => '',
    'rows' => 4,
])

@php
    $wireModel = $attributes->whereStartsWith('wire:model')->first();
    $fieldName = $wireModel ? str_replace(['wire:model=', 'wire:model.defer=', 'wire:model.live=', '"', "'"], '', $wireModel) : null;
@endphp

<div class="w-full">
    <textarea @if ($name) name="{{ $name }}" @endif 
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge([
            'class' =>
                'w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-university-red/20 focus:border-university-red transition-all outline-none resize-none',
        ]) }}
        @if($fieldName) @class(['border-red-300 focus:border-red-500 focus:ring-red-200' => $errors->has($fieldName)]) @endif>{{ $slot }}</textarea>

    @if($fieldName)
        @error($fieldName)
            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
        @enderror
    @endif
</div>