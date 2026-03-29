<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Resolution</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">
                For dispute
                <a href="{{ route('disputes.view', $dispute) }}" class="text-blue-500 hover:underline">
                    {{ $dispute->case_number }}
                </a>
            </p>
        </div>
        <div class="flex items-center gap-2">
            @can('update', $dispute->resolution)
                <x-ui.edit-button :href="route('disputes.resolution.edit', $dispute)" size="md" />
            @endcan
            <x-ui.button href="{{ route('disputes.view', $dispute) }}" variant="secondary"
                icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>'>
                <span class="hidden sm:inline">Back</span>
            </x-ui.button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Info --}}
        <div class="lg:col-span-2">
            <x-form.card title="Resolution Details">
                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">Type
                        </dt>
                        <dd class="mt-1">
                            <x-ui.badge
                                variant="{{ match ($dispute->resolution->resolution_type) {
                                    \App\Enums\ResolutionType::Settled => 'success',
                                    \App\Enums\ResolutionType::Dismissed => 'secondary',
                                    \App\Enums\ResolutionType::Escalated => 'danger',
                                } }}">
                                {{ ucfirst($dispute->resolution->resolution_type->value) }}
                            </x-ui.badge>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">
                            Resolved At</dt>
                        <dd class="mt-1 text-sm text-gray-700 dark:text-zinc-300">
                            {{ $dispute->resolution->resolved_at->format('F d, Y h:i A') }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">
                            Resolved By</dt>
                        <dd class="mt-1 text-sm text-gray-700 dark:text-zinc-300">
                            {{ $dispute->resolution->resolver?->name ?? '—' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">Linked
                            Hearing</dt>
                        <dd class="mt-1">
                            @if ($dispute->resolution->hearing)
                                <x-ui.view-button :href="route('hearings.view', $dispute->resolution->hearing)" size="xs">
                                    {{ $dispute->resolution->hearing->scheduled_date->format('M d, Y') }}
                                </x-ui.view-button>
                            @else
                                <span class="text-sm text-gray-400 dark:text-zinc-500">—</span>
                            @endif
                        </dd>
                    </div>

                    @if ($dispute->resolution->details)
                        <div class="col-span-2">
                            <dt
                                class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500 mb-2">
                                Details</dt>
                            <dd class="text-sm text-gray-700 dark:text-zinc-300 leading-relaxed whitespace-pre-line">
                                {{ $dispute->resolution->details }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </x-form.card>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Dispute Summary --}}
            <x-form.card title="Dispute Summary">
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">Case
                            Number</dt>
                        <dd class="mt-1">
                            <x-ui.view-button :href="route('disputes.view', $dispute)" size="xs">
                                {{ $dispute->case_number }}
                            </x-ui.view-button>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">Subject
                        </dt>
                        <dd class="mt-1 text-sm text-gray-700 dark:text-zinc-300">{{ $dispute->subject }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">Status
                        </dt>
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
                </dl>
            </x-form.card>

            {{-- Parties --}}
            <x-form.card title="Involved Parties">
                <div class="space-y-3">
                    @foreach ($dispute->parties as $party)
                        <div class="flex items-center gap-3">
                            @if ($party->person->photo_url)
                                <img src="{{ $party->person->photo_url }}"
                                    class="h-8 w-8 rounded-full object-cover flex-shrink-0" />
                            @else
                                <x-ui.avatar :name="$party->person->full_name" />
                            @endif
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                    {{ $party->person->full_name }}
                                </p>
                                <x-ui.badge
                                    variant="{{ $party->role === \App\Enums\DisputePartyRole::Complainant ? 'info' : 'danger' }}">
                                    {{ ucfirst($party->role->value) }}
                                </x-ui.badge>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-form.card>
        </div>
    </div>
</div>
