<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $blotterEntry->blotter_number }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">Recorded on {{ $blotterEntry->created_at->format('M d, Y') }} by {{ $blotterEntry->recorder->name }}</p>
        </div>
        <div class="flex items-center gap-2">
            @can('update', $blotterEntry)
                <x-ui.edit-button :href="route('blotters.edit', $blotterEntry)" size="md" />
            @endcan
            <x-ui.button href="{{ route('blotters.index') }}" variant="secondary"
                icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>'>
                <span class="hidden sm:inline">Back</span>
            </x-ui.button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Info --}}
        <div class="lg:col-span-2 space-y-6">
            <x-form.card title="Incident Details">
                <dl class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">Blotter Number</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $blotterEntry->blotter_number }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">Status</dt>
                            <dd class="mt-1">
                                <x-ui.badge variant="{{ match($blotterEntry->status) {
                                    \App\Enums\BlotterStatus::Open     => 'info',
                                    \App\Enums\BlotterStatus::Closed   => 'success',
                                    \App\Enums\BlotterStatus::Referred => 'warning',
                                } }}">
                                    {{ ucfirst($blotterEntry->status->value) }}
                                </x-ui.badge>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">Incident Date</dt>
                            <dd class="mt-1 text-sm text-gray-700 dark:text-zinc-300">{{ $blotterEntry->incident_date->format('F d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">Incident Time</dt>
                            <dd class="mt-1 text-sm text-gray-700 dark:text-zinc-300">
                                {{ $blotterEntry->incident_time ? \Carbon\Carbon::parse($blotterEntry->incident_time)->format('h:i A') : '—' }}
                            </dd>
                        </div>
                        <div class="col-span-2">
                            <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">Location</dt>
                            <dd class="mt-1 text-sm text-gray-700 dark:text-zinc-300">{{ $blotterEntry->incident_location }}</dd>
                        </div>
                    </div>

                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500 mb-2">Narrative</dt>
                        <dd class="text-sm text-gray-700 dark:text-zinc-300 leading-relaxed whitespace-pre-line">{{ $blotterEntry->narrative }}</dd>
                    </div>
                </dl>
            </x-form.card>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Parties --}}
            <x-form.card title="Involved Parties">
                <div class="space-y-3">
                    @foreach ($blotterEntry->parties as $party)
                        <div class="flex items-center gap-3">
                            @if ($party->person->photo_url)
                                <img src="{{ $party->person->photo_url }}" class="h-9 w-9 rounded-full object-cover flex-shrink-0" />
                            @else
                                <x-ui.avatar :name="$party->person->full_name" />
                            @endif
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $party->person->full_name }}</p>
                                <x-ui.badge variant="{{ match($party->role) {
                                    \App\Enums\BlotterPartyRole::Complainant => 'info',
                                    \App\Enums\BlotterPartyRole::Respondent  => 'danger',
                                    \App\Enums\BlotterPartyRole::Witness     => 'warning',
                                } }}">
                                    {{ ucfirst($party->role->value) }}
                                </x-ui.badge>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-form.card>

            {{-- Linked Dispute --}}
            @if ($blotterEntry->dispute)
                <x-form.card title="Linked Dispute">
                    <div class="space-y-2">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $blotterEntry->dispute->case_number }}</p>
                        <p class="text-sm text-gray-500 dark:text-zinc-400">{{ $blotterEntry->dispute->subject }}</p>
                        <x-ui.view-button :href="route('disputes.view', $blotterEntry->dispute)" size="sm" />
                    </div>
                </x-form.card>
            @endif
        </div>
    </div>
</div>