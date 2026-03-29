<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $dispute->case_number }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">
                Filed on {{ $dispute->created_at->format('M d, Y') }} by {{ $dispute->filer->name }}
            </p>
        </div>
        <div class="flex items-center gap-2">
            @can('update', $dispute)
                <x-ui.edit-button :href="route('disputes.edit', $dispute)" size="md" />
            @endcan
            <x-ui.button href="{{ route('disputes.index') }}" variant="secondary"
                icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>'>
                <span class="hidden sm:inline">Back</span>
            </x-ui.button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Info --}}
        <div class="lg:col-span-2 space-y-6">
            <x-form.card title="Dispute Details">
                <dl class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">
                                Case Number</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $dispute->case_number }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">
                                Status</dt>
                            <dd class="mt-1">
                                <x-ui.badge
                                    variant="{{ match ($dispute->status) {
                                        \App\Enums\DisputeStatus::Filed => 'info',
                                        \App\Enums\DisputeStatus::Ongoing => 'warning',
                                        \App\Enums\DisputeStatus::Settled => 'success',
                                        \App\Enums\DisputeStatus::Dismissed => 'secondary',
                                        \App\Enums\DisputeStatus::Escalated => 'danger',
                                    } }}">
                                    {{ ucfirst($dispute->status->value) }}
                                </x-ui.badge>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">
                                Assigned Officer</dt>
                            <dd class="mt-1 text-sm text-gray-700 dark:text-zinc-300">
                                {{ $dispute->assignee?->name ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">
                                Linked Blotter</dt>
                            <dd class="mt-1">
                                @if ($dispute->blotterEntry)
                                    <x-ui.view-button :href="route('blotter.view', $dispute->blotterEntry)" size="xs">
                                        {{ $dispute->blotterEntry->blotter_number }}
                                    </x-ui.view-button>
                                @else
                                    <span class="text-sm text-gray-400">—</span>
                                @endif
                            </dd>
                        </div>
                        <div class="col-span-2">
                            <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">
                                Subject</dt>
                            <dd class="mt-1 text-sm text-gray-700 dark:text-zinc-300">{{ $dispute->subject }}</dd>
                        </div>
                        @if ($dispute->description)
                            <div class="col-span-2">
                                <dt
                                    class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500 mb-2">
                                    Description</dt>
                                <dd
                                    class="text-sm text-gray-700 dark:text-zinc-300 leading-relaxed whitespace-pre-line">
                                    {{ $dispute->description }}</dd>
                            </div>
                        @endif
                    </div>
                </dl>
            </x-form.card>

            {{-- Hearings --}}
            <x-form.card title="Hearings">
                @if ($dispute->hearings->isNotEmpty())
                    <div class="space-y-3">
                        @foreach ($dispute->hearings as $hearing)
                            <div
                                class="flex items-center justify-between p-3 bg-gray-50 dark:bg-zinc-800/50 rounded-xl border border-gray-100 dark:border-zinc-700">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $hearing->scheduled_date->format('F d, Y') }}
                                        @if ($hearing->scheduled_time)
                                            at {{ \Carbon\Carbon::parse($hearing->scheduled_time)->format('h:i A') }}
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">{{ $hearing->location }}
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-zinc-500 mt-0.5">Conducted by
                                        {{ $hearing->conductor?->name ?? '—' }}</p>
                                </div>
                                <x-ui.badge
                                    variant="{{ match ($hearing->status) {
                                        \App\Enums\HearingStatus::Scheduled => 'info',
                                        \App\Enums\HearingStatus::Completed => 'success',
                                        \App\Enums\HearingStatus::Cancelled => 'danger',
                                        \App\Enums\HearingStatus::Postponed => 'warning',
                                    } }}">
                                    {{ ucfirst($hearing->status->value) }}
                                </x-ui.badge>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400 dark:text-zinc-500">No hearings scheduled yet.</p>
                @endif
            </x-form.card>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Parties --}}
            <x-form.card title="Involved Parties">
                <div class="space-y-3">
                    @foreach ($dispute->parties as $party)
                        <div class="flex items-center gap-3">
                            @if ($party->person->photo_url)
                                <img src="{{ $party->person->photo_url }}"
                                    class="h-9 w-9 rounded-full object-cover flex-shrink-0" />
                            @else
                                <x-ui.avatar :name="$party->person->full_name" />
                            @endif
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                    {{ $party->person->full_name }}</p>
                                <x-ui.badge
                                    variant="{{ $party->role === \App\Enums\DisputePartyRole::Complainant ? 'info' : 'danger' }}">
                                    {{ ucfirst($party->role->value) }}
                                </x-ui.badge>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-form.card>

            {{-- Resolution --}}
            @if ($dispute->resolution)
                <x-form.card title="Resolution">
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">
                                Type</dt>
                            <dd class="mt-1 text-sm text-gray-700 dark:text-zinc-300">
                                {{ ucfirst($dispute->resolution->resolution_type->value) }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">
                                Resolved At</dt>
                            <dd class="mt-1 text-sm text-gray-700 dark:text-zinc-300">
                                {{ $dispute->resolution->resolved_at->format('M d, Y h:i A') }}</dd>
                        </div>
                        @if ($dispute->resolution->details)
                            <div>
                                <dt
                                    class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500 mb-1">
                                    Details</dt>
                                <dd
                                    class="text-sm text-gray-700 dark:text-zinc-300 leading-relaxed whitespace-pre-line">
                                    {{ $dispute->resolution->details }}</dd>
                            </div>
                        @endif
                    </dl>
                </x-form.card>
            @endif
        </div>
    </div>
</div>
