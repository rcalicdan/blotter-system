<div>
    <x-form.label>Linked Blotter Entry</x-form.label>
    <livewire:forms.searchable-dropdown wire:model="blotter_id" :model="\App\Models\BlotterEntry::class" :searchFields="['blotter_number', 'incident_location']"
        displayField="blotter_number" :subLabelFields="['incident_date', 'incident_location']" :subLabelFieldFormats="['incident_date' => 'F j, Y']" subLabelSeparator=" · "
        placeholder="Search blotter number..." key="blotter-dropdown" />
    @error('blotter_id')
        <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
    @enderror
</div>
