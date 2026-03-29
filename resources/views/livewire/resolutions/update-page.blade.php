<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Resolution</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">
                For dispute
                <a href="{{ route('disputes.view', $dispute) }}" class="text-blue-500 hover:underline">
                    {{ $dispute->case_number }}
                </a>
            </p>
        </div>
        <x-ui.button href="{{ route('disputes.resolution.view', $dispute) }}" variant="secondary"
            icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>'>
            <span class="hidden sm:inline">Back</span>
        </x-ui.button>
    </div>

    <form wire:submit="save">
        <x-form.card
            title="Resolution Details"
            description="Update the outcome of this dispute.">

            @include('livewire.resolutions.partials.fields')

            <x-slot:footer>
                <div class="flex items-center justify-end gap-3">
                    <x-ui.button href="{{ route('disputes.resolution.view', $dispute) }}" variant="secondary">Cancel</x-ui.button>
                    <x-ui.button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-75">
                        <span wire:loading.remove>Save Changes</span>
                        <span wire:loading>Saving...</span>
                    </x-ui.button>
                </div>
            </x-slot:footer>
        </x-form.card>
    </form>
</div>