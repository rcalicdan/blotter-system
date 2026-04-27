<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add Judge</h1>
        </div>
        <x-ui.button href="{{ route('judges.index') }}" variant="secondary"
            icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>'>
            <span class="hidden sm:inline">Back</span>
        </x-ui.button>
    </div>

    <form wire:submit="save">
        <x-form.card title="Judge Information">
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
                    <x-form.label for="court_branch">Court Branch</x-form.label>
                    <x-form.input id="court_branch" wire:model="court_branch" />
                </div>
                <div>
                    <x-form.label for="status" :required="true">Status</x-form.label>
                    <x-form.select id="status" wire:model="status">
                        <option value="Active">Active</option>
                        <option value="Retired">Retired</option>
                    </x-form.select>
                </div>
            </x-form.grid>

            <x-slot:footer>
                <div class="flex items-center justify-end gap-3">
                    <x-ui.button href="{{ route('judges.index') }}" variant="secondary">Cancel</x-ui.button>
                    <x-ui.button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-75">Save</x-ui.button>
                </div>
            </x-slot:footer>
        </x-form.card>
    </form>
</div>