@props(['searchable' => false, 'filters' => null])

<div class="p-6 border-b border-gray-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
    @if($searchable)
        <div class="relative w-full md:w-96">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search"
                placeholder="Search by name or email..." 
                class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-university-red/20 focus:border-university-red transition-all"
            >
            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
    @endif
    
    @if($filters)
        <div class="flex items-center gap-2">
            {{ $filters }}
        </div>
    @endif
    
    {{ $slot }}
</div>