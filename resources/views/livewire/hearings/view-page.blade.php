<div>
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-start gap-4">
            <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-xl">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-3 flex-wrap">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white leading-none">
                        Hearing Details
                    </h1>
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
                <p class="mt-2 text-sm text-gray-500 dark:text-zinc-400">
                    Part of Case:
                    <a href="{{ route('disputes.view', $hearing->dispute) }}"
                        class="font-bold text-red-600 hover:underline">
                        {{ $hearing->dispute->case_number }}
                    </a>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <x-ui.button href="{{ route('hearings.index') }}" variant="secondary"
                icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>'>
                <span>Back</span>
            </x-ui.button>

            @can('update', $hearing)
                <x-ui.edit-button :href="route('hearings.edit', $hearing)" size="md" />
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-gray-100 dark:border-zinc-800 shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 divide-y md:divide-y-0 md:divide-x divide-gray-100 dark:divide-zinc-800">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Date</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $hearing->scheduled_date->format('F d, Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 md:pl-6 pt-4 md:pt-0">
                        <div class="w-10 h-10 rounded-full bg-orange-50 dark:bg-orange-900/20 flex items-center justify-center text-orange-600 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Time</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $hearing->scheduled_time ? \Carbon\Carbon::parse($hearing->scheduled_time)->format('h:i A') : 'TBA' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 md:pl-6 pt-4 md:pt-0">
                        <div class="w-10 h-10 rounded-full bg-green-50 dark:bg-green-900/20 flex items-center justify-center text-green-600 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Location</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                {{ $hearing->location }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <x-form.card title="Hearing Notes & Minutes">
                <div class="bg-gray-50/50 dark:bg-zinc-800/30 rounded-xl p-6 border border-gray-100/50 dark:border-zinc-800/50">
                    @if ($hearing->notes)
                        <p class="text-base text-gray-700 dark:text-zinc-300 leading-relaxed whitespace-pre-line">
                            {{ $hearing->notes }}
                        </p>
                    @else
                        <p class="text-sm text-gray-400 italic">No notes were recorded for this session.</p>
                    @endif
                </div>
            </x-form.card>

            <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-gray-100 dark:border-zinc-800 shadow-sm">
                <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-4">Presiding Judge</p>
                <div class="flex items-center gap-4">
                    <x-ui.avatar :name="$hearing->judge?->full_name ?? 'None'" class="h-12 w-12 rounded-xl text-lg" />
                    <div>
                        <p class="text-base font-bold text-gray-900 dark:text-white">
                            {{ $hearing->judge?->full_name ?? 'Not Assigned' }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-zinc-400">Official Presiding Judge</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <x-form.card title="Attendance Tracking">
                <div class="space-y-2">
                    @forelse ($hearing->attendees as $attendee)
                        <div @class([
                            'flex items-center justify-between p-3 rounded-xl border transition-all',
                            'bg-green-50/50 border-green-100 dark:bg-green-900/10 dark:border-green-900/30' => $attendee->attended,
                            'bg-red-50/50 border-red-100 dark:bg-red-900/10 dark:border-red-900/30' => !$attendee->attended,
                        ])>
                            <div class="flex items-center gap-3 min-w-0">
                                @if ($attendee->person->photo_url)
                                    <img src="{{ $attendee->person->photo_url }}" class="h-8 w-8 rounded-lg object-cover flex-shrink-0" />
                                @else
                                    <x-ui.avatar :name="$attendee->person->full_name" class="h-8 w-8 rounded-lg" />
                                @endif
                                <p class="text-sm font-bold text-gray-900 dark:text-white truncate">
                                    {{ $attendee->person->full_name }}
                                </p>
                            </div>

                            @if ($attendee->attended)
                                <div class="text-green-600 dark:text-green-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            @else
                                <div class="text-red-600 dark:text-red-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 italic px-2">No attendees recorded.</p>
                    @endforelse
                </div>
            </x-form.card>

            <div class="bg-gradient-to-br from-red-600 to-red-700 rounded-2xl p-5 text-white shadow-lg shadow-red-600/20">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-white/20 rounded-lg backdrop-blur-md">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                        </svg>
                    </div>
                    <h4 class="font-bold text-sm tracking-wide">Linked Dispute Case</h4>
                </div>
                <div class="space-y-3">
                    <p class="text-sm font-bold bg-white/10 p-2 rounded-lg border border-white/20">
                        {{ $hearing->dispute->case_number }}
                    </p>
                    <p class="text-xs text-red-100 line-clamp-2 italic">Subject: {{ $hearing->dispute->subject }}</p>
                    <x-ui.button :href="route('disputes.view', $hearing->dispute)" class="w-full !bg-white !text-red-600 hover:!bg-red-50 border-none !shadow-none !ring-0">
                        Go to Main Case
                    </x-ui.button>
                </div>
            </div>

            @if ($hearing->resolution)
                <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-2xl p-5 text-white shadow-lg shadow-emerald-600/20">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 bg-white/20 rounded-lg backdrop-blur-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-sm tracking-wide">Session Conclusion</h4>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] uppercase font-bold text-emerald-100 tracking-wider">Outcome Type</p>
                            <p class="text-sm font-bold mt-1">
                                {{ ucfirst($hearing->resolution->resolution_type->value) }}</p>
                        </div>
                        @if ($hearing->resolution->details)
                            <div class="pt-2 border-t border-white/10">
                                <p class="text-[10px] uppercase font-bold text-emerald-100 tracking-wider mb-1">Final Minutes</p>
                                <p class="text-xs leading-relaxed italic opacity-90">
                                    {{ $hearing->resolution->details }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>