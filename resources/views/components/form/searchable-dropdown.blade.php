<div x-data="{ open: false }" x-on:click.outside="open = false" class="relative w-full">
    {{-- Selected state --}}
    @if ($value && $displayValue)
        <div
            class="flex items-center justify-between w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 dark:border-zinc-700 border border-gray-200 rounded-xl text-sm">
            <div class="min-w-0">
                <p class="font-medium text-gray-900 dark:text-zinc-100 truncate">{{ $displayValue }}</p>
                @if ($subDisplayValue)
                    <p class="text-xs text-gray-500 dark:text-zinc-400 truncate mt-0.5">{{ $subDisplayValue }}</p>
                @endif
            </div>
            <button type="button" wire:click="clear"
                class="text-gray-400 hover:text-red-500 dark:text-zinc-500 dark:hover:text-red-400 transition-colors ml-2 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @else
        {{-- Search input --}}
        <div class="relative">
            <div
                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 dark:text-zinc-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="{{ $placeholder }}"
                x-on:focus="open = true" x-on:input="open = true"
                class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-200 dark:placeholder-zinc-500 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-600/20 focus:border-red-600 transition-all outline-none" />

            {{-- Loading indicator --}}
            <div wire:loading wire:target="search" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <svg class="animate-spin w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
            </div>
        </div>

        {{-- Dropdown results --}}
        <div x-show="open && $wire.results.length > 0" x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute z-50 w-full mt-1 bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl shadow-lg overflow-hidden">
            <ul class="max-h-60 overflow-y-auto divide-y divide-gray-50 dark:divide-zinc-800">
                @foreach ($results as $result)
                    <li>
                        <button type="button"
                            wire:click="select({{ $result['id'] }}, '{{ addslashes($result['label']) }}', '{{ addslashes($result['sub_label']) }}')"
                            x-on:click="open = false"
                            class="w-full text-left px-4 py-2.5 text-sm hover:bg-red-50 dark:hover:bg-zinc-800 transition-colors group">
                            <p
                                class="font-medium text-gray-700 dark:text-zinc-300 group-hover:text-red-600 dark:group-hover:text-red-400">
                                {{ $result['label'] }}
                            </p>
                            @if ($result['sub_label'])
                                <p
                                    class="text-xs text-gray-400 dark:text-zinc-500 group-hover:text-red-400/70 dark:group-hover:text-red-400/50 mt-0.5">
                                    {{ $result['sub_label'] }}
                                </p>
                            @endif
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- No results --}}
        <div x-show="open && $wire.search.length > 0 && $wire.results.length === 0"
            class="absolute z-50 w-full mt-1 bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl shadow-lg p-4 text-sm text-gray-400 dark:text-zinc-500 text-center">
            {{ $emptyMessage }}
        </div>
    @endif
</div>
