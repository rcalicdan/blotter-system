<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Officers</h1>
        </div>
        @can('create', \App\Models\Officer::class)
            <x-ui.button href="{{ route('officers.create') }}"
                icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>'>
                <span class="hidden sm:inline">Add Officer</span>
            </x-ui.button>
        @endcan
    </div>

    <x-table.index>
        <x-table.header :searchable="true" />
        <table class="w-full">
            <x-table.head>
                <x-table.cell header sortable sortField="first_name">Name</x-table.cell>
                <x-table.cell header sortable sortField="badge_number">Badge</x-table.cell>
                <x-table.cell header sortable sortField="status">Status</x-table.cell>
                <x-table.cell header class="text-center">Actions</x-table.cell>
            </x-table.head>

            <x-table.body>
                @forelse ($officers as $officer)
                    <x-table.row wire:key="officer-{{ $officer->id }}">
                        <x-table.cell class="align-middle">
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $officer->full_name }}</p>
                        </x-table.cell>
                        <x-table.cell class="align-middle text-gray-500 dark:text-zinc-400">
                            {{ $officer->badge_number ?? '—' }}
                        </x-table.cell>
                        <x-table.cell class="align-middle">
                            <x-ui.badge variant="{{ $officer->status === 'Active' ? 'success' : 'secondary' }}">
                                {{ $officer->status }}
                            </x-ui.badge>
                        </x-table.cell>
                        <x-table.cell class="text-center align-middle">
                            <div class="flex items-center justify-center gap-2">
                                @can('update', $officer)
                                    <x-ui.edit-button :href="route('officers.edit', $officer)" />
                                @endcan
                                @can('delete', $officer)
                                    <x-ui.delete-button :id="$officer->id" :name="$officer->full_name" resource="officer" />
                                @endcan
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="4" class="py-12 text-center text-gray-400 dark:text-zinc-500">
                            <p class="text-sm font-medium">No officers found.</p>
                        </x-table.cell>
                    </x-table.row>
                @endforelse
            </x-table.body>
        </table>
        <x-ui.pagination :paginator="$officers" />
    </x-table.index>
</div>