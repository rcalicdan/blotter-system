@props([
    'title' => null,
    'description' => null,
    'footer' => null,
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-700 overflow-hidden']) }}>
    @if ($title || $description)
        <div class="p-6 border-b border-gray-50 dark:border-zinc-700 bg-gray-50/30 dark:bg-zinc-800/30">
            @if (is_string($title))
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">{{ $title }}</h3>
                @if ($description)
                    <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">{{ $description }}</p>
                @endif
            @else
                {!! $title !!}
            @endif
        </div>
    @endif

    <div class="p-6 space-y-6">
        {{ $slot }}
    </div>

    @if ($footer)
        <div class="px-6 py-4 bg-gray-50/50 dark:bg-zinc-800/50 border-t border-gray-100 dark:border-zinc-700">
            {{ $footer }}
        </div>
    @endif
</div>