<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Blotter Entries</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage incident blotter records.</p>
        </div>
        @can('create', \App\Models\BlotterEntry::class)
            <x-ui.button href="{{ route('blotters.create') }}"
                icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>'>
                <span class="hidden sm:inline">New Entry</span>
            </x-ui.button>
        @endcan
    </div>

    <x-table.index>
        <x-table.header :searchable="true">
            <x-slot:filters>
                <select wire:model.live="statusFilter"
                    class="pl-3 pr-8 py-2.5 bg-gray-50 dark:bg-zinc-800 dark:border-zinc-700 dark:text-gray-300 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-600/20 focus:border-red-600 transition-all">
                    <option value="">All Statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->value }}">{{ ucfirst($status->value) }}</option>
                    @endforeach
                </select>
            </x-slot:filters>
        </x-table.header>

        <table class="w-full">
            <x-table.head>
                <x-table.cell header sortable sortField="blotter_number">Blotter #</x-table.cell>
                <x-table.cell header sortable sortField="incident_date" class="hidden md:table-cell">Incident Date</x-table.cell>
                <x-table.cell header sortable sortField="status" class="hidden sm:table-cell">Status</x-table.cell>
                <x-table.cell header class="text-center">Actions</x-table.cell>
            </x-table.head>

            <x-table.body>
                @forelse ($entries as $entry)
                    <x-table.row wire:key="entry-{{ $entry->id }}">
                        {{-- Blotter Number --}}
                        <x-table.cell class="align-middle">
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $entry->blotter_number }}</p>
                            <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">{{ $entry->created_at->format('M d, Y') }}</p>
                        </x-table.cell>

                        {{-- Incident Date --}}
                        <x-table.cell class="hidden md:table-cell align-middle text-gray-500 dark:text-zinc-400">
                            {{ $entry->incident_date->format('M d, Y') }}
                            @if ($entry->incident_time)
                                <span class="block text-xs">{{ \Carbon\Carbon::parse($entry->incident_time)->format('h:i A') }}</span>
                            @endif
                        </x-table.cell>

                        {{-- Status --}}
                        <x-table.cell class="hidden sm:table-cell align-middle">
                            <x-ui.badge variant="{{ match($entry->status) {
                                \App\Enums\BlotterStatus::Open     => 'info',
                                \App\Enums\BlotterStatus::Closed   => 'success',
                                \App\Enums\BlotterStatus::Referred => 'warning',
                            } }}">
                                {{ ucfirst($entry->status->value) }}
                            </x-ui.badge>
                        </x-table.cell>

                        {{-- Actions --}}
                        <x-table.cell class="text-center align-middle">
                            <div class="flex items-center justify-center gap-2">
                                <x-ui.view-button :href="route('blotters.view', $entry)" />

                                @can('update', $entry)
                                    <x-ui.edit-button :href="route('blotters.edit', $entry)" />
                                @endcan

                                @can('delete', $entry)
                                    <x-ui.delete-button :id="$entry->id" :name="$entry->blotter_number" resource="blotter entry" />
                                @endcan
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="4" class="py-12 text-center text-gray-400 dark:text-zinc-500">
                            <svg class="mx-auto mb-3 w-10 h-10 text-gray-200 dark:text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-sm font-medium">No blotter entries found</p>
                            <p class="text-xs mt-1">Try adjusting your search or filter.</p>
                        </x-table.cell>
                    </x-table.row>
                @endforelse
            </x-table.body>
        </table>

        <x-ui.pagination :paginator="$entries" />
    </x-table.index>
</div>