<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Hearings</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage scheduled hearings for disputes.</p>
        </div>
        @can('create', \App\Models\Hearing::class)
            <x-ui.button href="{{ route('hearings.create') }}"
                icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>'>
                <span class="hidden sm:inline">Schedule Hearing</span>
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
                <x-table.cell header sortable sortField="scheduled_date">Schedule</x-table.cell>
                <x-table.cell header class="hidden md:table-cell">Dispute</x-table.cell>
                <x-table.cell header class="hidden lg:table-cell">Location</x-table.cell>
                <x-table.cell header class="hidden md:table-cell">Presiding Judge</x-table.cell>
                <x-table.cell header sortable sortField="status" class="hidden sm:table-cell">Status</x-table.cell>
                <x-table.cell header class="text-center">Actions</x-table.cell>
            </x-table.head>

            <x-table.body>
                @forelse ($hearings as $hearing)
                    <x-table.row wire:key="hearing-{{ $hearing->id }}">
                        <x-table.cell class="align-middle">
                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ $hearing->scheduled_date->format('M d, Y') }}
                            </p>
                            @if ($hearing->scheduled_time)
                                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">
                                    {{ \Carbon\Carbon::parse($hearing->scheduled_time)->format('h:i A') }}
                                </p>
                            @endif
                        </x-table.cell>

                        <x-table.cell class="hidden md:table-cell align-middle">
                            <a href="{{ route('disputes.view', $hearing->dispute) }}"
                                class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                {{ $hearing->dispute->case_number }}
                            </a>
                            <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5 truncate max-w-xs">
                                {{ $hearing->dispute->subject }}
                            </p>
                        </x-table.cell>

                        <x-table.cell class="hidden lg:table-cell align-middle text-gray-500 dark:text-zinc-400">
                            {{ $hearing->location }}
                        </x-table.cell>

                        <x-table.cell class="hidden md:table-cell align-middle text-gray-500 dark:text-zinc-400">
                            {{ $hearing->judge?->full_name ?? '—' }}
                        </x-table.cell>

                        <x-table.cell class="hidden sm:table-cell align-middle">
                            <x-ui.badge variant="{{ match($hearing->status) {
                                \App\Enums\HearingStatus::Scheduled => 'info',
                                \App\Enums\HearingStatus::Completed => 'success',
                                \App\Enums\HearingStatus::Cancelled => 'danger',
                            } }}">
                                {{ ucfirst($hearing->status->value) }}
                            </x-ui.badge>
                        </x-table.cell>

                        <x-table.cell class="text-center align-middle">
                            <div class="flex items-center justify-center gap-2">
                                <x-ui.view-button :href="route('hearings.view', $hearing)" />

                                @can('update', $hearing)
                                    <x-ui.edit-button :href="route('hearings.edit', $hearing)" />
                                @endcan

                                @can('delete', $hearing)
                                    <x-ui.delete-button :id="$hearing->id" :name="$hearing->scheduled_date->format('M d, Y')" resource="hearing" />
                                @endcan
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="6" class="py-12 text-center text-gray-400 dark:text-zinc-500">
                            <svg class="mx-auto mb-3 w-10 h-10 text-gray-200 dark:text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-sm font-medium">No hearings found</p>
                            <p class="text-xs mt-1">Try adjusting your search or filter.</p>
                        </x-table.cell>
                    </x-table.row>
                @endforelse
            </x-table.body>
        </table>

        <x-ui.pagination :paginator="$hearings" />
    </x-table.index>
</div>