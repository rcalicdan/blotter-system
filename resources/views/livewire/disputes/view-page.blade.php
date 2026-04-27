<div>
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-start gap-4">
            <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-xl">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-3 flex-wrap">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white leading-none">
                        {{ $dispute->case_number }}
                    </h1>
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
                </div>
                <p class="mt-2 text-sm text-gray-500 dark:text-zinc-400 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Filed by <span
                        class="font-medium text-gray-700 dark:text-zinc-200">{{ $dispute->filer->name }}</span>
                    on {{ $dispute->created_at->format('M d, Y') }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-shrink-0">
            <x-ui.button href="{{ route('disputes.index') }}" variant="secondary"
                icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>'>
                <span>Back</span>
            </x-ui.button>
            @can('update', $dispute)
                <x-ui.edit-button :href="route('disputes.edit', $dispute)" size="md" />
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
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Investigating Officer</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $dispute->officer?->full_name ?? 'Unassigned' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 md:pl-6 pt-4 md:pt-0">
                        <div class="w-10 h-10 rounded-full bg-orange-50 dark:bg-orange-900/20 flex items-center justify-center text-orange-600 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.826a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Linked Blotter</p>
                            @if ($dispute->blotterEntry)
                                <a href="{{ route('blotters.view', $dispute->blotterEntry) }}"
                                    class="text-sm font-bold text-red-600 hover:underline">
                                    {{ $dispute->blotterEntry->blotter_number }}
                                </a>
                            @else
                                <p class="text-sm font-semibold text-gray-400">—</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-4 md:pl-6 pt-4 md:pt-0">
                        <div class="w-10 h-10 rounded-full bg-green-50 dark:bg-green-900/20 flex items-center justify-center text-green-600 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Case Subject</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                {{ $dispute->subject }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <x-form.card title="Dispute Description">
                <div class="bg-gray-50/50 dark:bg-zinc-800/30 rounded-xl p-6 border border-gray-100/50 dark:border-zinc-800/50">
                    <p class="text-base text-gray-700 dark:text-zinc-300 leading-relaxed whitespace-pre-line italic">
                        "{{ $dispute->description ?? 'No description provided.' }}"
                    </p>
                </div>
            </x-form.card>

            <x-form.card title="Hearing Schedule">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm text-gray-500 dark:text-zinc-400">
                        {{ $dispute->hearings->count() }} hearing(s) on record
                    </p>
                    @can('create', \App\Models\Hearing::class)
                        <x-ui.button href="{{ route('hearings.create', ['dispute_id' => $dispute->id]) }}" size="sm"
                            icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>'>
                            Schedule Hearing
                        </x-ui.button>
                    @endcan
                </div>

                @if ($dispute->hearings->isNotEmpty())
                    <div class="space-y-4">
                        @foreach ($dispute->hearings as $hearing)
                            <div class="group flex items-center justify-between p-4 bg-white dark:bg-zinc-900 rounded-xl border border-gray-100 dark:border-zinc-800 hover:border-red-200 dark:hover:border-red-900/30 transition-all shadow-sm">
                                <div class="flex items-center gap-4">
                                    <div @class([
                                        'w-12 h-12 rounded-xl flex flex-col items-center justify-center text-xs font-bold uppercase tracking-tighter',
                                        'bg-blue-50 text-blue-600 border border-blue-100' =>
                                            $hearing->status === \App\Enums\HearingStatus::Scheduled,
                                        'bg-green-50 text-green-600 border border-green-100' =>
                                            $hearing->status === \App\Enums\HearingStatus::Completed,
                                        'bg-red-50 text-red-400 border border-red-100' =>
                                            $hearing->status === \App\Enums\HearingStatus::Cancelled,
                                    ])>
                                        <span>{{ $hearing->scheduled_date->format('M') }}</span>
                                        <span class="text-lg leading-none">{{ $hearing->scheduled_date->format('d') }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">
                                            {{ $hearing->scheduled_time ? \Carbon\Carbon::parse($hearing->scheduled_time)->format('h:i A') : 'TBA' }}
                                        </p>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            </svg>
                                            <span class="text-xs text-gray-500 dark:text-zinc-400">{{ $hearing->location }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-2">
                                    <x-ui.badge
                                        variant="{{ match ($hearing->status) {
                                            \App\Enums\HearingStatus::Scheduled => 'info',
                                            \App\Enums\HearingStatus::Completed => 'success',
                                            \App\Enums\HearingStatus::Cancelled => 'danger',
                                        } }}">
                                        {{ ucfirst($hearing->status->value) }}
                                    </x-ui.badge>
                                    <p class="text-[10px] text-gray-400 dark:text-zinc-500 font-medium">
                                        By {{ $hearing->judge?->full_name ?? 'TBA' }}
                                    </p>
                                    <x-ui.view-button :href="route('hearings.view', $hearing)" size="xs" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 bg-gray-50/50 dark:bg-zinc-800/30 rounded-xl border border-dashed border-gray-200 dark:border-zinc-700">
                        <svg class="w-8 h-8 text-gray-300 dark:text-zinc-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-sm text-gray-400 dark:text-zinc-500">No hearings scheduled yet.</p>
                    </div>
                @endif
            </x-form.card>
        </div>

        <div class="space-y-6">
            <x-form.card title="Involved Parties">
                <div class="space-y-4">
                    @foreach ($dispute->parties as $party)
                        <div class="flex items-start gap-4 p-3 rounded-xl border border-transparent hover:border-gray-100 dark:hover:border-zinc-800 transition-colors group">
                            <div class="relative">
                                @if ($party->person->photo_url)
                                    <img src="{{ $party->person->photo_url }}" class="h-11 w-11 rounded-xl object-cover ring-2 ring-white dark:ring-zinc-900 shadow-sm" />
                                @else
                                    <x-ui.avatar :name="$party->person->full_name" class="h-11 w-11 rounded-xl" />
                                @endif
                                <div @class([
                                    'absolute -bottom-1 -right-1 w-5 h-5 rounded-full border-2 border-white dark:border-zinc-900 flex items-center justify-center text-[10px]',
                                    'bg-blue-500 text-white' => $party->role === \App\Enums\DisputePartyRole::Complainant,
                                    'bg-red-500 text-white' => $party->role === \App\Enums\DisputePartyRole::Respondent,
                                ])>
                                    {{ substr(ucfirst($party->role->value), 0, 1) }}
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-bold text-gray-900 dark:text-white truncate group-hover:text-red-600 transition-colors">
                                    {{ $party->person->full_name }}
                                </p>
                                <p class="text-[10px] uppercase font-bold tracking-widest text-gray-400 dark:text-zinc-500 mt-1">
                                    {{ $party->role->value }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-form.card>

            @if ($dispute->resolution)
                <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-2xl p-5 text-white shadow-lg shadow-emerald-600/20">
                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-white/20 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h4 class="font-bold text-sm tracking-wide">Case Resolution</h4>
                        </div>
                        <div class="flex items-center gap-1 flex-shrink-0">
                            <a href="{{ route('disputes.resolution.view', $dispute) }}" class="p-1.5 rounded-lg bg-white/10 hover:bg-white/20 transition-colors" title="View Resolution">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            @can('update', $dispute->resolution)
                                <a href="{{ route('disputes.resolution.edit', $dispute) }}" class="p-1.5 rounded-lg bg-white/10 hover:bg-white/20 transition-colors" title="Edit Resolution">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                            @endcan
                            @can('delete', $dispute->resolution)
                                <button type="button" wire:click="deleteResolution" wire:confirm="Are you sure you want to delete this resolution? This cannot be undone." class="p-1.5 rounded-lg bg-white/10 hover:bg-red-500/60 transition-colors" title="Delete Resolution">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            @endcan
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] uppercase font-bold text-emerald-100 tracking-wider">Settlement Type</p>
                            <p class="text-sm font-bold bg-white/10 p-2 rounded-lg border border-white/20 mt-1">
                                {{ ucfirst($dispute->resolution->resolution_type->value) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-emerald-100 tracking-wider">Resolved On</p>
                            <p class="text-xs font-medium mt-1">
                                {{ $dispute->resolution->resolved_at->format('M d, Y h:i A') }}
                            </p>
                        </div>
                        @if ($dispute->resolution->details)
                            <div class="pt-3 border-t border-white/10">
                                <p class="text-[10px] uppercase font-bold text-emerald-100 tracking-wider mb-1">Final Details</p>
                                <p class="text-xs leading-relaxed italic opacity-90">
                                    {{ $dispute->resolution->details }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-zinc-900 rounded-2xl p-5 border border-dashed border-gray-200 dark:border-zinc-700">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2 bg-gray-100 dark:bg-zinc-800 rounded-lg">
                            <svg class="w-5 h-5 text-gray-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-sm text-gray-700 dark:text-zinc-300">Case Resolution</h4>
                    </div>
                    <p class="text-xs text-gray-400 dark:text-zinc-500 mb-4 leading-relaxed">
                        This dispute has not been resolved yet. Once a resolution is reached, you can record it here.
                    </p>
                    @can('create', \App\Models\Resolution::class)
                        <x-ui.button :href="route('disputes.resolution.create', $dispute)" class="w-full justify-center"
                            icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'>
                            Record Resolution
                        </x-ui.button>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>