<!-- resources/views/components/table/cell.blade.php -->
@props([
    'header' => false,
    'sortable' => false,
    'sortField' => null,
])

@if ($header)
    <th {{ $attributes->merge(['class' => 'px-6 py-4']) }}>
        @if($sortable && $sortField)
            <button 
                wire:click="sortBy('{{ $sortField }}')"
                class="flex items-center gap-2 group hover:text-university-red transition-colors font-bold uppercase tracking-widest text-[11px]"
            >
                <span>{{ $slot }}</span>
                
                @php
                    $icon = '';
                    if (method_exists($this, 'getSortIcon')) {
                        $iconType = $this->getSortIcon($sortField);
                    } else {
                        $iconType = 'sort';
                    }
                @endphp

                @if($iconType === 'sort-asc')
                    <svg class="w-4 h-4 text-university-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                    </svg>
                @elseif($iconType === 'sort-desc')
                    <svg class="w-4 h-4 text-university-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                @else
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-university-red/50 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                    </svg>
                @endif
            </button>
        @else
            {{ $slot }}
        @endif
    </th>
@else
    <td {{ $attributes->merge(['class' => 'px-6 py-4']) }}>
        {{ $slot }}
    </td>
@endif