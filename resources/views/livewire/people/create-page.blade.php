<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add Person</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">Add a new person to the system.</p>
        </div>
        <x-ui.button href="{{ route('people.index') }}" variant="secondary"
            icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>'>
            <span class="hidden sm:inline">Back</span>
        </x-ui.button>
    </div>

    <form wire:submit="save">
        <x-form.card title="Personal Information" description="Fill in the person's details.">

            {{-- Photo Upload --}}
            <div x-data="{ previewUrl: null }" class="flex flex-col items-center gap-4">
                <div class="relative">
                    <template x-if="previewUrl">
                        <img :src="previewUrl" class="h-24 w-24 rounded-full object-cover border-2 border-gray-200 dark:border-zinc-700" />
                    </template>
                    <template x-if="!previewUrl">
                        <div class="h-24 w-24 rounded-full bg-gray-100 dark:bg-zinc-800 border-2 border-dashed border-gray-300 dark:border-zinc-600 flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </template>
                </div>

                <div class="flex flex-col items-center gap-1">
                    <label class="cursor-pointer">
                        <span class="text-sm font-medium text-red-600 hover:text-red-700">Upload Photo</span>
                        <input
                            type="file"
                            class="hidden"
                            accept="image/*"
                            wire:model="photo"
                            x-on:change="
                                const file = $event.target.files[0];
                                if (file) previewUrl = URL.createObjectURL(file);
                            "
                        />
                    </label>
                    <p class="text-xs text-gray-400">PNG, JPG up to 2MB</p>
                    @error('photo')
                        <p class="text-red-500 text-xs font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <x-form.grid :cols="2">
                <div>
                    <x-form.label for="first_name" :required="true">First Name</x-form.label>
                    <x-form.input id="first_name" wire:model="first_name" placeholder="Enter first name" />
                </div>

                <div>
                    <x-form.label for="last_name" :required="true">Last Name</x-form.label>
                    <x-form.input id="last_name" wire:model="last_name" placeholder="Enter last name" />
                </div>

                <div>
                    <x-form.label for="birthdate">Birthdate</x-form.label>
                    <x-form.input id="birthdate" type="date" wire:model="birthdate" />
                </div>

                <div>
                    <x-form.label for="contact_number">Contact Number</x-form.label>
                    <x-form.input id="contact_number" wire:model="contact_number" placeholder="Enter contact number" />
                </div>

                <div class="sm:col-span-2">
                    <x-form.label for="address">Address</x-form.label>
                    <x-form.text-area id="address" wire:model="address" placeholder="Enter full address" :rows="3" />
                </div>
            </x-form.grid>

            <x-slot:footer>
                <div class="flex items-center justify-end gap-3">
                    <x-ui.button href="{{ route('people.index') }}" variant="secondary">Cancel</x-ui.button>
                    <x-ui.button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-75">
                        <span wire:loading.remove>Add Person</span>
                        <span wire:loading>Saving...</span>
                    </x-ui.button>
                </div>
            </x-slot:footer>
        </x-form.card>
    </form>
</div>