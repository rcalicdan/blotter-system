<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create Blotter Entry</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">Record a new incident in the blotters.</p>
        </div>
        <x-ui.button href="{{ route('blotters.index') }}" variant="secondary"
            icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>'>
            <span class="hidden sm:inline">Back</span>
        </x-ui.button>
    </div>

    <form wire:submit="save">
        <x-form.card title="Incident Information" description="Fill in the details of the incident.">

            <x-form.grid :cols="2">
                {{-- Blotter Number --}}
                <div>
                    <x-form.label for="blotter_number" :required="true">Blotter Number</x-form.label>
                    <x-form.input id="blotter_number" wire:model="blotter_number" placeholder="e.g. BLT-2026-0001"
                        readonly class="bg-gray-100 dark:bg-zinc-700 cursor-not-allowed" />
                    @error('blotter_number')
                        <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <x-form.label for="status" :required="true">Status</x-form.label>
                    <x-form.select id="status" wire:model="status" placeholder="Select status">
                        @foreach ($statuses as $status)
                            <option value="{{ $status->value }}">{{ ucfirst($status->value) }}</option>
                        @endforeach
                    </x-form.select>
                </div>

                {{-- Incident Date --}}
                <div>
                    <x-form.label for="incident_date" :required="true">Incident Date</x-form.label>
                    <x-form.input id="incident_date" type="date" wire:model="incident_date" />
                </div>

                {{-- Incident Time --}}
                <div>
                    <x-form.label for="incident_time">Incident Time</x-form.label>
                    <x-form.input id="incident_time" type="time" wire:model="incident_time" />
                </div>

                {{-- Location --}}
                <div class="sm:col-span-2">
                    <x-form.label for="incident_location" :required="true">Incident Location</x-form.label>
                    <x-form.input id="incident_location" wire:model="incident_location"
                        placeholder="Enter incident location" />
                </div>

                {{-- Narrative --}}
                <div class="sm:col-span-2">
                    <x-form.label for="narrative" :required="true">Narrative</x-form.label>
                    <x-form.text-area id="narrative" wire:model="narrative"
                        placeholder="Describe the incident in detail..." :rows="5" />
                </div>
            </x-form.grid>

            {{-- Parties --}}
            @include('livewire.blotters.partials.parties')

            <x-slot:footer>
                <div class="flex items-center justify-end gap-3">
                    <x-ui.button href="{{ route('blotters.index') }}" variant="secondary">Cancel</x-ui.button>
                    <x-ui.button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-75">
                        <span wire:loading.remove>Create Entry</span>
                        <span wire:loading>Saving...</span>
                    </x-ui.button>
                </div>
            </x-slot:footer>
        </x-form.card>
    </form>
</div>
