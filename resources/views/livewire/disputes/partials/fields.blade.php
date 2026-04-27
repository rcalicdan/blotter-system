<x-form.grid :cols="2">
    <div>
        <x-form.label for="case_number" :required="true">Case Number</x-form.label>
        <x-form.input id="case_number" wire:model="case_number" placeholder="e.g. DSP-2026-0001" readonly
            class="bg-gray-100 dark:bg-zinc-700 cursor-not-allowed" />
        @error('case_number')
            <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <x-form.label for="status" :required="true">Status</x-form.label>
        <x-form.select id="status" wire:model="status" placeholder="Select status">
            @foreach ($statuses as $status)
                <option value="{{ $status->value }}">{{ ucfirst($status->value) }}</option>
            @endforeach
        </x-form.select>
    </div>

    <div class="sm:col-span-2">
        <x-form.label for="subject" :required="true">Subject</x-form.label>
        <x-form.input id="subject" wire:model="subject" placeholder="Brief subject of the dispute" />
    </div>

    <div class="sm:col-span-2">
        <x-form.label for="description">Description</x-form.label>
        <x-form.text-area id="description" wire:model="description" placeholder="Detailed description of the dispute..."
            :rows="4" />
    </div>
</x-form.grid>

<x-form.section title="References" description="Optionally link to a blotter entry and assign an investigating officer.">
    <x-form.grid :cols="2">
        <div>
            <x-form.label>Linked Blotter Entry</x-form.label>
            <livewire:forms.searchable-dropdown wire:model="blotter_id" :model="\App\Models\BlotterEntry::class" :searchFields="['blotter_number', 'incident_location']"
                displayField="blotter_number" :subLabelFields="['incident_location']" placeholder="Search blotter number..."
                key="blotter-dropdown" />
            @error('blotter_id')
                <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <x-form.label>Investigating Officer</x-form.label>
            <livewire:forms.searchable-dropdown wire:model="officer_id" :model="\App\Models\Officer::class" :searchFields="['first_name', 'last_name', 'badge_number']"
                displayField="full_name" :subLabelFields="['badge_number', 'rank']" placeholder="Search officer..." key="officer-dropdown" />
            @error('officer_id')
                <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
            @enderror
        </div>
    </x-form.grid>
</x-form.section>