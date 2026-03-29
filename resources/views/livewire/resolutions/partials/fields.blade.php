<x-form.grid :cols="2">
    {{-- Resolution Type --}}
    <div>
        <x-form.label for="resolution_type" :required="true">Resolution Type</x-form.label>
        <x-form.select id="resolution_type" wire:model="resolution_type" placeholder="Select type">
            @foreach ($resolutionTypes as $type)
                <option value="{{ $type->value }}">{{ ucfirst($type->value) }}</option>
            @endforeach
        </x-form.select>
        @error('resolution_type')
            <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
        @enderror
    </div>

    {{-- Resolved At --}}
    <div>
        <x-form.label for="resolved_at" :required="true">Resolved At</x-form.label>
        <x-form.input id="resolved_at" type="datetime-local" wire:model="resolved_at" />
        @error('resolved_at')
            <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
        @enderror
    </div>

    {{-- Linked Hearing (optional) --}}
    <div class="sm:col-span-2">
        <x-form.label>Linked Hearing (Optional)</x-form.label>
        @if ($dispute->hearings->isNotEmpty())
            <x-form.select id="hearing_id" wire:model="hearing_id" placeholder="Select a hearing">
                @foreach ($dispute->hearings as $hearing)
                    <option value="{{ $hearing->id }}">
                        {{ $hearing->scheduled_date->format('M d, Y') }}
                        @if ($hearing->scheduled_time)
                            at {{ \Carbon\Carbon::parse($hearing->scheduled_time)->format('h:i A') }}
                        @endif
                        — {{ $hearing->location }}
                    </option>
                @endforeach
            </x-form.select>
        @else
            <p class="text-sm text-gray-400 dark:text-zinc-500 mt-1">No hearings found for this dispute.</p>
        @endif
        @error('hearing_id')
            <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
        @enderror
    </div>

    {{-- Details --}}
    <div class="sm:col-span-2">
        <x-form.label for="details">Details</x-form.label>
        <x-form.text-area id="details" wire:model="details" placeholder="Describe the resolution in detail..."
            :rows="4" />
        @error('details')
            <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
        @enderror
    </div>
</x-form.grid>
