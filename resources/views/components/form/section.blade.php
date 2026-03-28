@props([
    'title' => null,
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'pt-6 border-t border-gray-50 dark:border-zinc-700']) }}>
    @if($title || $description)
        <div class="mb-6">
            @if($title)
                <h4 class="text-sm font-bold text-gray-700 dark:text-zinc-300">{{ $title }}</h4>
            @endif
            @if($description)
                <p class="text-xs text-gray-500 dark:text-zinc-500 mt-1">{{ $description }}</p>
            @endif
        </div>
    @endif

    <div class="space-y-6">
        {{ $slot }}
    </div>
</div>