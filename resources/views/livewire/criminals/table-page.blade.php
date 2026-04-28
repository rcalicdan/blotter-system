<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Known Offenders</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">List of individuals with recorded offenses.</p>
        </div>
    </div>

    <x-table.index>
        <x-table.header :searchable="true" />

        <table class="w-full">
            <x-table.head>
                <x-table.cell header sortable sortField="first_name">Name</x-table.cell>
                <x-table.cell header class="hidden md:table-cell text-center">Total Offenses</x-table.cell>
                <x-table.cell header sortable sortField="created_at" class="hidden lg:table-cell">Registered</x-table.cell>
                <x-table.cell header class="text-center">Actions</x-table.cell>
            </x-table.head>

            <x-table.body>
                @forelse ($criminals as $person)
                    <x-table.row wire:key="person-{{ $person->id }}">
                        <x-table.cell class="align-middle">
                            <div class="flex items-center gap-3">
                                @if ($person->photo_url)
                                    <img src="{{ $person->photo_url }}" class="h-9 w-9 rounded-full object-cover border border-red-200 dark:border-red-900 flex-shrink-0" />
                                @else
                                    <x-ui.avatar :name="$person->full_name" variant="danger" />
                                @endif

                                <div class="min-w-0">
                                    <p class="font-semibold text-red-600 dark:text-red-400">{{ $person->full_name }}</p>
                                    @if ($person->birthdate)
                                        <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">
                                            {{ $person->birthdate->format('M d, Y') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </x-table.cell>

                        <x-table.cell class="hidden md:table-cell text-center align-middle">
                            <x-ui.badge variant="danger">
                                {{ $person->criminal_records_count }} Record(s)
                            </x-ui.badge>
                        </x-table.cell>

                        <x-table.cell class="hidden lg:table-cell align-middle text-gray-500 dark:text-zinc-400">
                            {{ $person->created_at->format('M d, Y') }}
                        </x-table.cell>

                        <x-table.cell class="text-center align-middle">
                            <div class="flex items-center justify-center gap-2">
                                @can('view', $person)
                                    <x-ui.view-button :href="route('people.view', $person)">Profile</x-ui.view-button>
                                @endcan
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="5" class="py-12 text-center text-gray-400 dark:text-zinc-500">
                            <p class="text-sm font-medium">No criminal records found in the system.</p>
                        </x-table.cell>
                    </x-table.row>
                @endforelse
            </x-table.body>
        </table>
        <x-ui.pagination :paginator="$criminals" />
    </x-table.index>
</div>