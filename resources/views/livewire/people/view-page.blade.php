<div>
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-start gap-4">
            @if ($person->photo_url)
                <img src="{{ $person->photo_url }}"
                    class="h-16 w-16 rounded-2xl object-cover ring-4 ring-white dark:ring-zinc-900 shadow-sm" />
            @else
                <x-ui.avatar :name="$person->full_name" class="h-16 w-16 rounded-2xl text-xl" />
            @endif
            <div>
                <div class="flex items-center gap-3 flex-wrap">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white leading-none">
                        {{ $person->full_name }}
                    </h1>
                    @if ($person->is_criminal)
                        <x-ui.badge variant="danger">Known Offender</x-ui.badge>
                    @endif
                </div>
                <p class="mt-2 text-sm text-gray-500 dark:text-zinc-400">
                    Registered on {{ $person->created_at->format('M d, Y') }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-shrink-0">
            <x-ui.button href="{{ route('people.index') }}" variant="secondary"
                icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>'>
                <span>Back</span>
            </x-ui.button>
            @can('update', $person)
                <x-ui.edit-button :href="route('people.edit', $person)" size="md" />
                <x-ui.button wire:click="toggleCriminalStatus" variant="{{ $person->is_criminal ? 'secondary' : 'danger' }}"
                    size="md">
                    {{ $person->is_criminal ? 'Remove Offender Status' : 'Mark as Offender' }}
                </x-ui.button>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <x-form.card title="Criminal Records & Offenses">
                <x-slot:title>
                    <div class="flex items-center justify-between w-full">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">Criminal Records</h3>
                            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Official offenses logged in the
                                system.</p>
                        </div>
                        @can('create', \App\Models\CriminalRecord::class)
                            <x-ui.button href="{{ route('people.records.create', $person) }}" size="sm">
                                Add Record
                            </x-ui.button>
                        @endcan
                    </div>
                </x-slot:title>

                <div class="space-y-4">
                    @forelse ($person->criminalRecords as $record)
                        <div
                            class="p-4 bg-gray-50/50 dark:bg-zinc-800/30 rounded-xl border border-gray-100 dark:border-zinc-800">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white">{{ $record->charge }}
                                    </h4>
                                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">Committed on
                                        {{ $record->date_committed->format('F d, Y') }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <x-ui.badge
                                        variant="{{ match ($record->status) {
                                            'Cleared' => 'success',
                                            'Wanted' => 'danger',
                                            default => 'warning',
                                        } }}">{{ $record->status }}</x-ui.badge>

                                    @can('update', $record)
                                        <a href="{{ route('records.edit', $record) }}"
                                            class="text-gray-400 hover:text-blue-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('delete', $record)
                                        <button wire:click="deleteRecord({{ $record->id }})"
                                            wire:confirm="Delete this record?"
                                            class="text-gray-400 hover:text-red-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endcan
                                </div>
                            </div>
                            @if ($record->notes)
                                <p
                                    class="text-sm text-gray-700 dark:text-zinc-300 mt-3 pt-3 border-t border-gray-200 dark:border-zinc-700">
                                    {{ $record->notes }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 italic">No criminal records found.</p>
                    @endforelse
                </div>
            </x-form.card>
        </div>

        <div class="space-y-6">
            <x-form.card title="Personal Info">
                <dl class="space-y-4">
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">Contact
                            Number</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $person->contact_number ?? '—' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">
                            Birthdate</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $person->birthdate ? $person->birthdate->format('F d, Y') : '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">Address
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $person->address ?? '—' }}</dd>
                    </div>
                </dl>
            </x-form.card>

            <x-form.card title="Blotter Involvements">
                <div class="space-y-3">
                    @forelse ($person->blotterParties as $party)
                        <div class="flex items-center justify-between text-sm">
                            <a href="{{ route('blotters.view', $party->blotterEntry) }}"
                                class="font-semibold text-blue-600 hover:underline">
                                {{ $party->blotterEntry->blotter_number }}
                            </a>
                            <x-ui.badge variant="secondary">{{ ucfirst($party->role->value) }}</x-ui.badge>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 italic">No blotter involvements.</p>
                    @endforelse
                </div>
            </x-form.card>

            <x-form.card title="Dispute Involvements">
                <div class="space-y-3">
                    @forelse ($person->disputeParties as $party)
                        <div class="flex items-center justify-between text-sm">
                            <a href="{{ route('disputes.view', $party->dispute) }}"
                                class="font-semibold text-blue-600 hover:underline">
                                {{ $party->dispute->case_number }}
                            </a>
                            <x-ui.badge variant="secondary">{{ ucfirst($party->role->value) }}</x-ui.badge>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 italic">No dispute involvements.</p>
                    @endforelse
                </div>
            </x-form.card>
        </div>
    </div>
</div>
