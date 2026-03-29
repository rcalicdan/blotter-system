<x-form.section title="Involved Parties" description="Add people involved in this incident and their roles.">
    <div class="space-y-4">
        @foreach ($parties as $index => $party)
            <div wire:key="party-{{ $index }}"
                class="flex items-start gap-3 p-4 bg-gray-50 dark:bg-zinc-800/50 rounded-xl border border-gray-100 dark:border-zinc-700">
                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {{-- Person Search --}}
                    <div>
                        <x-form.label :required="true">Person</x-form.label>
                        <livewire:form.searchable-dropdown wire:model="parties.{{ $index }}.person_id"
                            :model="\App\Models\Person::class" :searchFields="['first_name', 'last_name']" displayField="full_name" :subLabelFields="['address', 'birthdate']"
                            :subLabelFieldFormats="['birthdate' => 'F j, Y']" subLabelSeparator=" · " placeholder="Search person..." :key="'party-person-' . $index" />
                        @error("parties.{$index}.person_id")
                            <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Role --}}
                    <div>
                        <x-form.label :required="true">Role</x-form.label>
                        <x-form.select wire:model="parties.{{ $index }}.role" placeholder="Select role">
                            @foreach ($partyRoles as $role)
                                <option value="{{ $role->value }}">{{ ucfirst($role->value) }}</option>
                            @endforeach
                        </x-form.select>
                        @error("parties.{$index}.role")
                            <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Remove Button --}}
                @if (count($parties) > 1)
                    <button type="button" wire:click="removeParty({{ $index }})"
                        class="mt-6 p-1.5 text-gray-400 hover:text-red-500 transition-colors rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
            </div>
        @endforeach

        <button type="button" wire:click="addParty"
            class="flex items-center gap-2 text-sm font-medium text-red-600 hover:text-red-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Another Party
        </button>
    </div>
</x-form.section>
