<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Disputes</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage dispute records.</p>
        </div>
        @can('create', \App\Models\Dispute::class)
            <x-ui.button href="{{ route('disputes.create') }}"
                icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>'>
                <span class="hidden sm:inline">New Dispute</span>
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
                <x-table.cell header sortable sortField="case_number">Case #</x-table.cell>
                <x-table.cell header sortable sortField="subject" class="hidden md:table-cell">Subject</x-table.cell>
                <x-table.cell header class="hidden lg:table-cell">Parties</x-table.cell>
                <x-table.cell header class="hidden md:table-cell">Investigating Officer</x-table.cell>
                <x-table.cell header sortable sortField="status" class="hidden sm:table-cell">Status</x-table.cell>
                <x-table.cell header class="text-center">Actions</x-table.cell>
            </x-table.head>

            <x-table.body>
                @forelse ($disputes as $dispute)
                    <x-table.row wire:key="dispute-{{ $dispute->id }}">
                        <x-table.cell class="align-middle">
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $dispute->case_number }}</p>
                            <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">{{ $dispute->created_at->format('M d, Y') }}</p>
                            @if ($dispute->blotterEntry)
                                <p class="text-xs text-blue-500 dark:text-blue-400 mt-0.5">{{ $dispute->blotterEntry->blotter_number }}</p>
                            @endif
                        </x-table.cell>

                        <x-table.cell class="hidden md:table-cell align-middle text-gray-600 dark:text-zinc-300 max-w-xs truncate">
                            {{ $dispute->subject }}
                        </x-table.cell>

                        <x-table.cell class="hidden lg:table-cell align-middle">
                            <div class="flex flex-col gap-1">
                                @foreach ($dispute->parties as $party)
                                    <div class="flex items-center gap-1.5">
                                        <x-ui.badge variant="{{ $party->role === \App\Enums\DisputePartyRole::Complainant ? 'info' : 'danger' }}">
                                            {{ ucfirst($party->role->value) }}
                                        </x-ui.badge>
                                        <span class="text-xs text-gray-600 dark:text-zinc-400">{{ $party->person->full_name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </x-table.cell>

                        <x-table.cell class="hidden md:table-cell align-middle text-gray-500 dark:text-zinc-400">
                            {{ $dispute->officer?->full_name ?? '—' }}
                        </x-table.cell>

                        <x-table.cell class="hidden sm:table-cell align-middle">
                            <x-ui.badge variant="{{ match($dispute->status) {
                                \App\Enums\DisputeStatus::Filed     => 'info',
                                \App\Enums\DisputeStatus::Ongoing   => 'warning',
                                \App\Enums\DisputeStatus::Settled   => 'success',
                                \App\Enums\DisputeStatus::Dismissed => 'secondary',
                                \App\Enums\DisputeStatus::Escalated => 'danger',
                            } }}">
                                {{ ucfirst($dispute->status->value) }}
                            </x-ui.badge>
                        </x-table.cell>

                        <x-table.cell class="text-center align-middle">
                            <div class="flex items-center justify-center gap-2">
                                <x-ui.view-button :href="route('disputes.view', $dispute)" />

                                @can('update', $dispute)
                                    <x-ui.edit-button :href="route('disputes.edit', $dispute)" />
                                @endcan

                                @can('delete', $dispute)
                                    <x-ui.delete-button :id="$dispute->id" :name="$dispute->case_number" resource="dispute" />
                                @endcan
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="6" class="py-12 text-center text-gray-400 dark:text-zinc-500">
                            <svg class="mx-auto mb-3 w-10 h-10 text-gray-200 dark:text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                            </svg>
                            <p class="text-sm font-medium">No disputes found</p>
                            <p class="text-xs mt-1">Try adjusting your search or filter.</p>
                        </x-table.cell>
                    </x-table.row>
                @endforelse
            </x-table.body>
        </table>

        <x-ui.pagination :paginator="$disputes" />
    </x-table.index>
</div>