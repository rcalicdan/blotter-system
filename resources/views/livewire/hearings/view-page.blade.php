<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Hearing Details</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">
                For dispute
                <a href="{{ route('disputes.view', $hearing->dispute) }}" class="text-blue-500 hover:underline">
                    {{ $hearing->dispute->case_number }}
                </a>
            </p>
        </div>
        <div class="flex items-center gap-2">
            @can('update', $hearing)
                <x-ui.edit-button :href="route('hearings.edit', $hearing)" size="md" />
            @endcan
            <x-ui.button href="{{ route('hearings.index') }}" variant="secondary"
                icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>'>
                <span class="hidden sm:inline">Back</span>
            </x-ui.button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Info --}}
        <div class="lg:col-span-2 space-y-6">
            <x-form.card title="Hearing Details">
                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">
                            Scheduled Date</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                            {{ $hearing->scheduled_date->format('F d, Y') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">
                            Scheduled Time</dt>
                        <dd class="mt-1 text-sm text-gray-700 dark:text-zinc-300">
                            {{ $hearing->scheduled_time ? \Carbon\Carbon::parse($hearing->scheduled_time)->format('h:i A') : '—' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">Status
                        </dt>
                        <dd class="mt-1">
                            <x-ui.badge
                                variant="{{ match ($hearing->status) {
                                    \App\Enums\HearingStatus::Scheduled => 'info',
                                    \App\Enums\HearingStatus::Completed => 'success',
                                    \App\Enums\HearingStatus::Cancelled => 'danger',
                                } }}">
                                {{ ucfirst($hearing->status->value) }}
                            </x-ui.badge>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">
                            Conducted By</dt>
                        <dd class="mt-1 text-sm text-gray-700 dark:text-zinc-300">
                            {{ $hearing->conductor?->name ?? '—' }}
                        </dd>
                    </div>
                    <div class="col-span-2">
                        <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">
                            Location</dt>
                        <dd class="mt-1 text-sm text-gray-700 dark:text-zinc-300">{{ $hearing->location }}</dd>
                    </div>
                    @if ($hearing->notes)
                        <div class="col-span-2">
                            <dt
                                class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500 mb-2">
                                Notes</dt>
                            <dd class="text-sm text-gray-700 dark:text-zinc-300 leading-relaxed whitespace-pre-line">
                                {{ $hearing->notes }}</dd>
                        </div>
                    @endif
                </dl>
            </x-form.card>

            {{-- Resolution --}}
            @if ($hearing->resolution)
                <x-form.card title="Resolution">
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">
                                Type</dt>
                            <dd class="mt-1 text-sm text-gray-700 dark:text-zinc-300">
                                {{ ucfirst($hearing->resolution->resolution_type->value) }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">
                                Resolved At</dt>
                            <dd class="mt-1 text-sm text-gray-700 dark:text-zinc-300">
                                {{ $hearing->resolution->resolved_at->format('M d, Y h:i A') }}</dd>
                        </div>
                        @if ($hearing->resolution->details)
                            <div>
                                <dt
                                    class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500 mb-1">
                                    Details</dt>
                                <dd
                                    class="text-sm text-gray-700 dark:text-zinc-300 leading-relaxed whitespace-pre-line">
                                    {{ $hearing->resolution->details }}</dd>
                            </div>
                        @endif
                    </dl>
                </x-form.card>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Linked Dispute --}}
            <x-form.card title="Linked Dispute">
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">Case
                            Number</dt>
                        <dd class="mt-1">
                            <x-ui.view-button :href="route('disputes.view', $hearing->dispute)" size="xs">
                                {{ $hearing->dispute->case_number }}
                            </x-ui.view-button>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">Subject
                        </dt>
                        <dd class="mt-1 text-sm text-gray-700 dark:text-zinc-300">{{ $hearing->dispute->subject }}</dd>
                    </div>
                </dl>
            </x-form.card>

            {{-- Attendees --}}
            <x-form.card title="Attendees">
                @if ($hearing->attendees->isNotEmpty())
                    <div class="space-y-3">
                        @foreach ($hearing->attendees as $attendee)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    @if ($attendee->person->photo_url)
                                        <img src="{{ $attendee->person->photo_url }}"
                                            class="h-8 w-8 rounded-full object-cover flex-shrink-0" />
                                    @else
                                        <x-ui.avatar :name="$attendee->person->full_name" />
                                    @endif
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $attendee->person->full_name }}
                                    </p>
                                </div>
                                @if ($attendee->attended)
                                    <x-ui.badge variant="success">Present</x-ui.badge>
                                @else
                                    <x-ui.badge variant="danger">Absent</x-ui.badge>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400 dark:text-zinc-500">No attendees recorded.</p>
                @endif
            </x-form.card>
        </div>
    </div>
</div>
