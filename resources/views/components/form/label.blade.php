@props([
    'for' => null,
    'required' => false,
])

<label @if ($for) for="{{ $for }}" @endif
    {{ $attributes->merge(['class' => 'block text-[11px] uppercase tracking-widest font-bold text-gray-400 mb-2']) }}>
    {{ $slot }}
    @if ($required)
        <span class="text-red-500 ml-0.5">*</span>
    @endif
</label>
