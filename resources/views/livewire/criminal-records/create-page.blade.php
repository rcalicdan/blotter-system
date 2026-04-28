<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add Criminal Record</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">For {{ $person->full_name }}</p>
        </div>
        <x-ui.button href="{{ route('people.view', $person) }}" variant="secondary"
            icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>'>
            <span class="hidden sm:inline">Back</span>
        </x-ui.button>
    </div>

    <form wire:submit="save">
        <x-form.card title="Offense Details">
            <x-form.grid :cols="2">
                <div class="sm:col-span-2">
                    <x-form.label for="charge" :required="true">Charge / Offense</x-form.label>
                    <x-form.input id="charge" wire:model="charge" placeholder="e.g. Theft, Assault" />
                </div>
                <div>
                    <x-form.label for="date_committed" :required="true">Date Committed</x-form.label>
                    <x-form.input id="date_committed" type="date" wire:model="date_committed" />
                </div>
                <div>
                    <x-form.label for="status" :required="true">Status</x-form.label>
                    <x-form.select id="status" wire:model="status">
                        <option value="Arrested">Arrested</option>
                        <option value="Wanted">Wanted</option>
                        <option value="Convicted">Convicted</option>
                        <option value="Cleared">Cleared</option>
                    </x-form.select>
                </div>
                <div class="sm:col-span-2">
                    <x-form.label for="notes">Notes</x-form.label>
                    <x-form.text-area id="notes" wire:model="notes" :rows="3" />
                </div>
            </x-form.grid>

            <x-slot:footer>
                <div class="flex items-center justify-end gap-3">
                    <x-ui.button href="{{ route('people.view', $person) }}" variant="secondary">Cancel</x-ui.button>
                    <x-ui.button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-75">Save Record</x-ui.button>
                </div>
            </x-slot:footer>
        </x-form.card>
    </form>
</div>