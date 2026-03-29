<div>
    {{-- Header Section --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-start gap-4">
            <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-xl">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-3 flex-wrap">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white leading-none">
                        {{ $blotterEntry->blotter_number }}
                    </h1>
                    <x-ui.badge
                        variant="{{ match ($blotterEntry->status) {
                            \App\Enums\BlotterStatus::Open => 'info',
                            \App\Enums\BlotterStatus::Closed => 'success',
                            \App\Enums\BlotterStatus::Referred => 'warning',
                        } }}">
                        {{ ucfirst($blotterEntry->status->value) }}
                    </x-ui.badge>
                </div>
                <p class="mt-2 text-sm text-gray-500 dark:text-zinc-400 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Recorded by <span
                        class="font-medium text-gray-700 dark:text-zinc-200">{{ $blotterEntry->recorder->name }}</span>
                    on {{ $blotterEntry->created_at->format('M d, Y') }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <x-ui.button href="{{ route('blotters.index') }}" variant="secondary"
                icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>'>
                <span>Back</span>
            </x-ui.button>

            @can('update', $blotterEntry)
                <x-ui.edit-button :href="route('blotters.edit', $blotterEntry)" size="md" />
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Main Content Column --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- Incident "When & Where" Quick Info --}}
            <div
                class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-gray-100 dark:border-zinc-800 shadow-sm">
                <div
                    class="grid grid-cols-1 md:grid-cols-3 gap-6 divide-y md:divide-y-0 md:divide-x divide-gray-100 dark:divide-zinc-800">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Date Occurred</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $blotterEntry->incident_date->format('F d, Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 md:pl-6 pt-4 md:pt-0">
                        <div
                            class="w-10 h-10 rounded-full bg-orange-50 dark:bg-orange-900/20 flex items-center justify-center text-orange-600 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Time Occurred</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $blotterEntry->incident_time ? \Carbon\Carbon::parse($blotterEntry->incident_time)->format('h:i A') : '—' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 md:pl-6 pt-4 md:pt-0">
                        <div
                            class="w-10 h-10 rounded-full bg-green-50 dark:bg-green-900/20 flex items-center justify-center text-green-600 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Incident Location
                            </p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                {{ $blotterEntry->incident_location }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Narrative Section --}}
            <x-form.card title="Incident Narrative">
                <div
                    class="bg-gray-50/50 dark:bg-zinc-800/30 rounded-xl p-6 border border-gray-100/50 dark:border-zinc-800/50">
                    <p class="text-base text-gray-700 dark:text-zinc-300 leading-relaxed whitespace-pre-line italic">
                        "{{ $blotterEntry->narrative }}"
                    </p>
                </div>
            </x-form.card>
        </div>

        {{-- Sidebar Column --}}
        <div class="space-y-6">
            {{-- Parties --}}
            <x-form.card title="Involved Parties">
                <div class="space-y-4">
                    @forelse ($blotterEntry->parties as $party)
                        <div
                            class="flex items-start gap-4 p-3 rounded-xl border border-transparent hover:border-gray-100 dark:hover:border-zinc-800 transition-colors group">
                            <div class="relative">
                                @if ($party->person->photo_url)
                                    <img src="{{ $party->person->photo_url }}"
                                        class="h-11 w-11 rounded-xl object-cover ring-2 ring-white dark:ring-zinc-900 shadow-sm" />
                                @else
                                    <x-ui.avatar :name="$party->person->full_name" class="h-11 w-11 rounded-xl" />
                                @endif

                                <div @class([
                                    'absolute -bottom-1 -right-1 w-5 h-5 rounded-full border-2 border-white dark:border-zinc-900 flex items-center justify-center text-[10px]',
                                    'bg-blue-500 text-white' =>
                                        $party->role === \App\Enums\BlotterPartyRole::Complainant,
                                    'bg-red-500 text-white' =>
                                        $party->role === \App\Enums\BlotterPartyRole::Respondent,
                                    'bg-amber-500 text-white' =>
                                        $party->role === \App\Enums\BlotterPartyRole::Witness,
                                ])>
                                    {{ substr(ucfirst($party->role->value), 0, 1) }}
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p
                                    class="text-sm font-bold text-gray-900 dark:text-white truncate group-hover:text-red-600 transition-colors">
                                    {{ $party->person->full_name }}
                                </p>
                                <p
                                    class="text-[10px] uppercase font-bold tracking-widest text-gray-400 dark:text-zinc-500 mt-1">
                                    {{ $party->role->value }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 italic px-3">No parties listed.</p>
                    @endforelse
                </div>
            </x-form.card>

            {{-- Linked Case --}}
            @if ($blotterEntry->dispute)
                <div
                    class="bg-gradient-to-br from-red-600 to-red-700 rounded-2xl p-5 text-white shadow-lg shadow-red-600/20">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 bg-white/20 rounded-lg backdrop-blur-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.826a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-sm tracking-wide">Linked Dispute</h4>
                    </div>
                    <div class="space-y-3">
                        <p class="text-xs text-red-100 leading-tight">This blotter is associated with a formal dispute
                            case:</p>
                        <p class="text-sm font-bold bg-white/10 p-2 rounded-lg border border-white/20">
                            {{ $blotterEntry->dispute->case_number }}
                        </p>
                        <x-ui.button :href="route('disputes.view', $blotterEntry->dispute)"
                            class="w-full bg-white text-red-600 hover:bg-red-50 border-none !shadow-none !ring-0">
                            View Dispute Case
                        </x-ui.button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
