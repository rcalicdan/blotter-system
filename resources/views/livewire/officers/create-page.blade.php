<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add Officer</h1>
        </div>
        <x-ui.button href="{{ route('officers.index') }}" variant="secondary"
            icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>'>
            <span class="hidden sm:inline">Back</span>
        </x-ui.button>
    </div>

    <form wire:submit="save">
        <x-form.card title="Officer Information">
            <x-form.grid :cols="2">
                <div>
                    <x-form.label for="first_name" :required="true">First Name</x-form.label>
                    <x-form.input id="first_name" wire:model="first_name" />
                </div>
                <div>
                    <x-form.label for="last_name" :required="true">Last Name</x-form.label>
                    <x-form.input id="last_name" wire:model="last_name" />
                </div>
                <div>
                    <x-form.label for="badge_number">Badge Number</x-form.label>
                    <x-form.input id="badge_number" wire:model="badge_number" />
                </div>
                <div>
                    <x-form.label for="rank">Rank</x-form.label>
                    <x-form.input id="rank" wire:model="rank" />
                </div>
                <div>
                    <x-form.label for="status" :required="true">Status</x-form.label>
                    <x-form.select id="status" wire:model="status">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </x-form.select>
                </div>
            </x-form.grid>

            <x-slot:footer>
                <div class="flex items-center justify-end gap-3">
                    <x-ui.button href="{{ route('officers.index') }}" variant="secondary">Cancel</x-ui.button>
                    <x-ui.button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-75">Save</x-ui.button>
                </div>
            </x-slot:footer>
        </x-form.card>
    </form>
</div>