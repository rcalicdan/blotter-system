@props([
    'headers' => [],
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-700 overflow-hidden']) }}>
    {{ $slot }}
</div>