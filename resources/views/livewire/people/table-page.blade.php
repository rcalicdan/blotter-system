<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">People</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage people records in the system.</p>
        </div>
        @can('create', \App\Models\Person::class)
            <x-ui.button href="{{ route('people.create') }}"
                icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>'>
                <span class="hidden sm:inline">Add Person</span>
            </x-ui.button>
        @endcan
    </div>

    <x-table.index>
        <x-table.header :searchable="true" />

        <table class="w-full">
            <x-table.head>
                <x-table.cell header sortable sortField="first_name">Name</x-table.cell>
                <x-table.cell header class="hidden md:table-cell">Contact</x-table.cell>
                <x-table.cell header class="hidden lg:table-cell">Address</x-table.cell>
                <x-table.cell header sortable sortField="created_at" class="hidden lg:table-cell">Added</x-table.cell>
                <x-table.cell header class="text-center">Actions</x-table.cell>
            </x-table.head>

            <x-table.body>
                @forelse ($people as $person)
                    <x-table.row wire:key="person-{{ $person->id }}">
                        {{-- Name --}}
                        <x-table.cell>
                            <div class="flex items-center gap-3">
                                {{-- Photo or Initials --}}
                                @if ($person->photo_url)
                                    <img
                                        src="{{ $person->photo_url }}"
                                        alt="{{ $person->full_name }}"
                                        class="h-9 w-9 rounded-full object-cover border border-gray-100 dark:border-zinc-700 flex-shrink-0"
                                    />
                                @else
                                    <x-ui.avatar :name="$person->full_name" />
                                @endif

                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $person->full_name }}</p>
                                    @if ($person->birthdate)
                                        <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">
                                            {{ $person->birthdate->format('M d, Y') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </x-table.cell>

                        {{-- Contact --}}
                        <x-table.cell class="hidden md:table-cell text-gray-500 dark:text-zinc-400">
                            {{ $person->contact_number ?? '—' }}
                        </x-table.cell>

                        {{-- Address --}}
                        <x-table.cell class="hidden lg:table-cell text-gray-500 dark:text-zinc-400 max-w-xs truncate">
                            {{ $person->address ?? '—' }}
                        </x-table.cell>

                        {{-- Added --}}
                        <x-table.cell class="hidden lg:table-cell text-gray-500 dark:text-zinc-400">
                            {{ $person->created_at->format('M d, Y') }}
                        </x-table.cell>

                        {{-- Actions --}}
                        <x-table.cell class="text-center align-middle">
                            <div class="flex items-center justify-center gap-2">
                                @can('update', $person)
                                    <x-ui.edit-button :href="route('people.edit', $person)" />
                                @endcan

                                @can('delete', $person)
                                    <x-ui.delete-button :id="$person->id" :name="$person->full_name" resource="person" />
                                @endcan
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="5" class="py-12 text-center text-gray-400 dark:text-zinc-500">
                            <svg class="mx-auto mb-3 w-10 h-10 text-gray-200 dark:text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <p class="text-sm font-medium">No people found</p>
                            <p class="text-xs mt-1">Try adjusting your search.</p>
                        </x-table.cell>
                    </x-table.row>
                @endforelse
            </x-table.body>
        </table>

        <x-ui.pagination :paginator="$people" />
    </x-table.index>
</div>