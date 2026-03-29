<div class="space-y-8">
    {{-- Header / Welcome Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Dashboard</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">
                Hello, <span
                    class="font-semibold text-gray-800 dark:text-zinc-200">{{ explode(' ', auth()->user()->name)[0] }}</span>.
                <span class="hidden sm:inline">Here is a summary of the system's current status.</span>
            </p>
        </div>

        <div class="flex items-center gap-3">
            <div class="hidden lg:block text-right mr-2">
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">
                    {{ now()->format('l') }}</p>
                <p class="text-sm font-medium text-gray-600 dark:text-zinc-300">{{ now()->format('M d, Y') }}</p>
            </div>
            {{-- Quick Action Button --}}
            <flux:dropdown>
                <x-ui.button variant="primary"
                    icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>'>
                    Quick Action
                </x-ui.button>
                <flux:menu>
                    <flux:menu.item :href="route('blotters.create')">New Blotter Entry</flux:menu.item>
                    <flux:menu.item :href="route('disputes.create')">File New Dispute</flux:menu.item>
                    <flux:menu.item :href="route('people.create')">Register Person</flux:menu.item>
                </flux:menu>
            </flux:dropdown>
        </div>
    </div>

    {{-- 4-Column Main Metrics --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $stats = [
                [
                    'label' => 'Blotter Entries',
                    'count' => $totalBlotters,
                    'route' => 'blotters.index',
                    'color' => 'red',
                    'icon' =>
                        'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                    'sub' => "$openBlotters open",
                ],
                [
                    'label' => 'Total Disputes',
                    'count' => $totalDisputes,
                    'route' => 'disputes.index',
                    'color' => 'orange',
                    'icon' =>
                        'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3',
                    'sub' => "$ongoingDisputes ongoing",
                ],
                [
                    'label' => 'Hearings',
                    'count' => $totalHearings,
                    'route' => 'hearings.index',
                    'color' => 'blue',
                    'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                    'sub' => "$scheduledHearings today",
                ],
                [
                    'label' => 'Registered People',
                    'count' => $totalPeople,
                    'route' => 'people.index',
                    'color' => 'purple',
                    'icon' =>
                        'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
                    'sub' => "$totalResolutions settled",
                ],
            ];
        @endphp

        @foreach ($stats as $stat)
            <a href="{{ route($stat['route']) }}"
                class="group relative bg-white dark:bg-zinc-900 p-6 rounded-3xl border border-gray-100 dark:border-zinc-800 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div
                    class="absolute -right-4 -top-4 w-24 h-24 bg-{{ $stat['color'] }}-50/50 dark:bg-{{ $stat['color'] }}-900/10 rounded-full group-hover:scale-150 transition-transform duration-500">
                </div>

                <div class="relative flex items-center justify-between mb-4">
                    <div
                        class="p-3 bg-{{ $stat['color'] }}-50 dark:bg-{{ $stat['color'] }}-900/20 rounded-2xl text-{{ $stat['color'] }}-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="{{ $stat['icon'] }}" />
                        </svg>
                    </div>
                    <div class="text-{{ $stat['color'] }}-600 opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </div>
                </div>

                <h3 class="text-3xl font-black text-gray-900 dark:text-white">{{ number_format($stat['count']) }}</h3>
                <p class="text-sm font-bold text-gray-500 dark:text-zinc-500 uppercase tracking-wide mt-1">
                    {{ $stat['label'] }}</p>

                <div
                    class="mt-4 flex items-center text-xs font-bold text-{{ $stat['color'] }}-600 dark:text-{{ $stat['color'] }}-400 bg-{{ $stat['color'] }}-50 dark:bg-{{ $stat['color'] }}-900/30 w-fit px-2 py-1 rounded-lg">
                    <span class="relative flex h-2 w-2 mr-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-{{ $stat['color'] }}-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-{{ $stat['color'] }}-500"></span>
                    </span>
                    {{ $stat['sub'] }}
                </div>
            </a>
        @endforeach
    </div>

    {{-- Case Analytics Breakdown --}}
    <div class="bg-gray-50/50 dark:bg-zinc-800/30 rounded-[2rem] p-6 border border-gray-100 dark:border-zinc-800">
        <div class="flex items-center gap-2 mb-6 px-2">
            <h2 class="text-lg font-bold text-gray-800 dark:text-zinc-200">Dispute Analytics</h2>
            <div class="h-px flex-1 bg-gray-200 dark:bg-zinc-800 ml-4"></div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach ([['label' => 'Filed', 'count' => $filedDisputes, 'icon' => 'document-text', 'class' => 'text-blue-600 bg-blue-50/50 dark:bg-blue-900/10'], ['label' => 'Ongoing', 'count' => $ongoingDisputes, 'icon' => 'clock', 'class' => 'text-amber-600 bg-amber-50/50 dark:bg-amber-900/10'], ['label' => 'Settled', 'count' => $settledDisputes, 'icon' => 'check-circle', 'class' => 'text-emerald-600 bg-emerald-50/50 dark:bg-emerald-900/10'], ['label' => 'Dismissed', 'count' => $dismissedDisputes, 'icon' => 'x-circle', 'class' => 'text-gray-500 bg-white dark:bg-zinc-800'], ['label' => 'Escalated', 'count' => $escalatedDisputes, 'icon' => 'trending-up', 'class' => 'text-red-600 bg-red-50/50 dark:bg-red-900/10']] as $item)
                <div
                    class="group flex flex-col items-center justify-center p-4 rounded-2xl border border-gray-100 dark:border-zinc-700/50 {{ $item['class'] }} transition-all hover:shadow-lg">
                    <span class="text-2xl font-black mb-1">{{ $item['count'] }}</span>
                    <span
                        class="text-[10px] font-black uppercase tracking-widest opacity-80">{{ $item['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Bottom Section: Recent Activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Recent Blotters --}}
        <section
            class="bg-white dark:bg-zinc-900 rounded-[2rem] border border-gray-100 dark:border-zinc-800 shadow-sm overflow-hidden flex flex-col">
            <div class="p-6 pb-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-red-50 dark:bg-red-900/20 rounded-lg text-red-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Recent Blotters
                    </h3>
                </div>
                <a href="{{ route('blotters.index') }}"
                    class="text-xs font-bold text-red-600 hover:text-red-700 transition-colors bg-red-50 dark:bg-red-900/20 px-3 py-1 rounded-full">All
                    Records</a>
            </div>

            <div class="flex-1 px-2 pb-4">
                <div class="space-y-1">
                    @forelse ($recentBlotters as $blotter)
                        <a href="{{ route('blotters.view', $blotter) }}"
                            class="flex items-center justify-between p-4 rounded-2xl hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-all group">
                            <div class="min-w-0">
                                <p
                                    class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-red-600 transition-colors truncate">
                                    {{ $blotter->blotter_number }}</p>
                                <p
                                    class="text-[10px] font-bold text-gray-400 dark:text-zinc-500 uppercase mt-0.5 tracking-tight">
                                    {{ $blotter->incident_date->format('M d, Y') }}</p>
                            </div>
                            <x-ui.badge
                                variant="{{ match ($blotter->status) {\App\Enums\BlotterStatus::Open => 'info',\App\Enums\BlotterStatus::Closed => 'success',default => 'warning'} }}"
                                class="scale-90">
                                {{ $blotter->status->value }}
                            </x-ui.badge>
                        </a>
                    @empty
                        <div class="py-12 text-center">
                            <svg class="w-12 h-12 text-gray-200 dark:text-zinc-800 mx-auto mb-3" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 11V16M12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21Z"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="text-xs font-bold text-gray-400">No blotters entries.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- Recent Disputes --}}
        <section
            class="bg-white dark:bg-zinc-900 rounded-[2rem] border border-gray-100 dark:border-zinc-800 shadow-sm overflow-hidden flex flex-col">
            <div class="p-6 pb-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-orange-50 dark:bg-orange-900/20 rounded-lg text-orange-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Recent Disputes
                    </h3>
                </div>
                <a href="{{ route('disputes.index') }}"
                    class="text-xs font-bold text-orange-600 hover:text-orange-700 transition-colors bg-orange-50 dark:bg-orange-900/20 px-3 py-1 rounded-full">All
                    Cases</a>
            </div>

            <div class="flex-1 px-2 pb-4">
                <div class="space-y-1">
                    @forelse ($recentDisputes as $dispute)
                        <a href="{{ route('disputes.view', $dispute) }}"
                            class="flex items-center justify-between p-4 rounded-2xl hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-all group">
                            <div class="min-w-0">
                                <p
                                    class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-orange-600 transition-colors truncate">
                                    {{ $dispute->case_number }}</p>
                                <p
                                    class="text-[10px] font-bold text-gray-400 dark:text-zinc-500 uppercase mt-0.5 tracking-tight truncate">
                                    {{ $dispute->subject }}</p>
                            </div>
                            <x-ui.badge
                                variant="{{ match ($dispute->status) {\App\Enums\DisputeStatus::Settled => 'success',\App\Enums\DisputeStatus::Escalated => 'danger',default => 'warning'} }}"
                                class="scale-90">
                                {{ $dispute->status->value }}
                            </x-ui.badge>
                        </a>
                    @empty
                        <div class="py-12 text-center">
                            <p class="text-xs font-bold text-gray-400">No disputes found.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- Upcoming Hearings --}}
        <section
            class="bg-white dark:bg-zinc-900 rounded-[2rem] border border-gray-100 dark:border-zinc-800 shadow-sm overflow-hidden flex flex-col">
            <div class="p-6 pb-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-blue-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Next Hearings
                    </h3>
                </div>
                <a href="{{ route('hearings.index') }}"
                    class="text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors bg-blue-50 dark:bg-blue-900/20 px-3 py-1 rounded-full">Schedule</a>
            </div>

            <div class="flex-1 px-2 pb-4">
                <div class="space-y-1">
                    @forelse ($upcomingHearings as $hearing)
                        <a href="{{ route('hearings.view', $hearing) }}"
                            class="flex items-center gap-4 p-4 rounded-2xl hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-all group">
                            <div
                                class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-900/30 flex flex-col items-center justify-center text-blue-700 dark:text-blue-300 flex-shrink-0">
                                <span
                                    class="text-[8px] font-black uppercase leading-none">{{ $hearing->scheduled_date->format('M') }}</span>
                                <span
                                    class="text-sm font-black leading-none mt-0.5">{{ $hearing->scheduled_date->format('d') }}</span>
                            </div>
                            <div class="min-w-0">
                                <p
                                    class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-blue-600 transition-colors truncate">
                                    {{ $hearing->dispute->case_number }}</p>
                                <p
                                    class="text-[10px] font-bold text-gray-400 dark:text-zinc-500 uppercase mt-0.5 tracking-tight truncate">
                                    {{ $hearing->scheduled_time ? \Carbon\Carbon::parse($hearing->scheduled_time)->format('h:i A') : 'TBA' }}
                                    @if ($hearing->location)
                                        · {{ $hearing->location }}
                                    @endif
                                </p>
                            </div>
                        </a>
                    @empty
                        <div class="py-12 text-center text-xs font-bold text-gray-400">
                            Clear schedule for today.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
</div>
