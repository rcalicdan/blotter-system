<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Judges</h1>
        </div>
        @can('create', \App\Models\Judge::class)
            <x-ui.button href="{{ route('judges.create') }}"
                icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>'>
                <span class="hidden sm:inline">Add Judge</span>
            </x-ui.button>
        @endcan
    </div>

    <x-table.index>
        <x-table.header :searchable="true" />
        <table class="w-full">
            <x-table.head>
                <x-table.cell header sortable sortField="first_name">Name</x-table.cell>
                <x-table.cell header sortable sortField="court_branch">Branch</x-table.cell>
                <x-table.cell header sortable sortField="status">Status</x-table.cell>
                <x-table.cell header class="text-center">Actions</x-table.cell>
            </x-table.head>

            <x-table.body>
                @forelse ($judges as $judge)
                    <x-table.row wire:key="judge-{{ $judge->id }}">
                        <x-table.cell class="align-middle">
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $judge->full_name }}</p>
                        </x-table.cell>
                        <x-table.cell class="align-middle text-gray-500 dark:text-zinc-400">
                            {{ $judge->court_branch ?? '—' }}
                        </x-table.cell>
                        <x-table.cell class="align-middle">
                            <x-ui.badge variant="{{ $judge->status === 'Active' ? 'success' : 'secondary' }}">
                                {{ $judge->status }}
                            </x-ui.badge>
                        </x-table.cell>
                        <x-table.cell class="text-center align-middle">
                            <div class="flex items-center justify-center gap-2">
                                @can('update', $judge)
                                    <x-ui.edit-button :href="route('judges.edit', $judge)" />
                                @endcan
                                @can('delete', $judge)
                                    <x-ui.delete-button :id="$judge->id" :name="$judge->full_name" resource="judge" />
                                @endcan
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="4" class="py-12 text-center text-gray-400 dark:text-zinc-500">
                            <p class="text-sm font-medium">No judges found.</p>
                        </x-table.cell>
                    </x-table.row>
                @endforelse
            </x-table.body>
        </table>
        <x-ui.pagination :paginator="$judges" />
    </x-table.index>
</div>
