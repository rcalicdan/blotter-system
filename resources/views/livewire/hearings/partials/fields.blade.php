<x-form.grid :cols="2">
    <div class="sm:col-span-2">
        <x-form.label :required="true">Dispute</x-form.label>
        <livewire:form.searchable-dropdown wire:model.live="dispute_id" :model="\App\Models\Dispute::class" :searchFields="['case_number', 'subject']"
            displayField="case_number" :subLabelFields="['subject']" placeholder="Search dispute by case number..."
            key="dispute-dropdown" />
        @error('dispute_id')
            <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <x-form.label for="scheduled_date" :required="true">Scheduled Date</x-form.label>
        <x-form.input id="scheduled_date" type="date" wire:model="scheduled_date" />
    </div>

    <div>
        <x-form.label for="scheduled_time">Scheduled Time</x-form.label>
        <x-form.input id="scheduled_time" type="time" wire:model="scheduled_time" />
    </div>

    <div class="sm:col-span-2">
        <x-form.label for="location" :required="true">Location</x-form.label>
        <x-form.input id="location" wire:model="location" placeholder="Enter hearing location" />
    </div>

    <div>
        <x-form.label for="status" :required="true">Status</x-form.label>
        <x-form.select id="status" wire:model="status" placeholder="Select status">
            @foreach ($statuses as $status)
                <option value="{{ $status->value }}">{{ ucfirst($status->value) }}</option>
            @endforeach
        </x-form.select>
    </div>

    <div>
        <x-form.label>Presiding Judge</x-form.label>
        <livewire:form.searchable-dropdown wire:model="judge_id" :model="\App\Models\Judge::class" :searchFields="['first_name', 'last_name', 'court_branch']"
            displayField="full_name" :subLabelFields="['court_branch']" placeholder="Search judge..." key="judge-dropdown" />
        @error('judge_id')
            <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
        @enderror
    </div>

    <div class="sm:col-span-2">
        <x-form.label for="notes">Notes</x-form.label>
        <x-form.text-area id="notes" wire:model="notes" placeholder="Additional notes about this hearing..."
            :rows="3" />
    </div>
</x-form.grid>