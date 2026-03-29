<x-form.section title="Attendees" description="Track attendance of involved parties for this hearing.">
    @if (count($attendees) > 0)
        <div class="space-y-3">
            @foreach ($attendees as $index => $attendee)
                <div wire:key="attendee-{{ $index }}"
                    class="flex items-center justify-between p-4 bg-gray-50 dark:bg-zinc-800/50 rounded-xl border border-gray-100 dark:border-zinc-700">
                    <div class="flex items-center gap-3">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $attendee['name'] }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5 capitalize">
                                {{ $attendee['role'] }}
                            </p>
                        </div>
                    </div>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="attendees.{{ $index }}.attended"
                            class="rounded border-gray-300 text-red-600 focus:ring-red-500" />
                        <span class="text-sm text-gray-600 dark:text-zinc-400">Attended</span>
                    </label>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-gray-400 dark:text-zinc-500">
            Select a dispute above to load attendees from its parties.
        </p>
    @endif
</x-form.section>
