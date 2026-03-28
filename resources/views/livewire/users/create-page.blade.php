<div>
    {{-- Page Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create User</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">Add a new user to the system.</p>
        </div>
        <x-ui.button href="{{ route('users.index') }}" variant="secondary"
            icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>'>
            <span class="hidden sm:inline">Back</span>
        </x-ui.button>
    </div>

    <form wire:submit="save">
        <x-form.card
            title="User Information"
            description="Fill in the details to create a new user account.">

            <x-form.grid :cols="2">
                {{-- Name --}}
                <div>
                    <x-form.label for="name" :required="true">Name</x-form.label>
                    <x-form.input
                        id="name"
                        wire:model="name"
                        placeholder="Enter full name" />
                </div>

                {{-- Email --}}
                <div>
                    <x-form.label for="email" :required="true">Email</x-form.label>
                    <x-form.input
                        id="email"
                        type="email"
                        wire:model="email"
                        placeholder="Enter email address" />
                </div>

                {{-- Password --}}
                <div>
                    <x-form.label for="password" :required="true">Password</x-form.label>
                    <x-form.input
                        id="password"
                        type="password"
                        wire:model="password"
                        placeholder="Enter password" />
                </div>

                {{-- Confirm Password --}}
                <div>
                    <x-form.label for="password_confirmation" :required="true">Confirm Password</x-form.label>
                    <x-form.input
                        id="password_confirmation"
                        type="password"
                        wire:model="password_confirmation"
                        placeholder="Confirm password" />
                </div>
            </x-form.grid>

            <x-form.section title="Access Control" description="Assign a role to define what this user can access.">
                <x-form.grid :cols="2">
                    <div>
                        <x-form.label for="role" :required="true">Role</x-form.label>
                        <x-form.select id="role" wire:model="role" placeholder="Select a role">
                            @foreach ($roles as $role)
                                <option value="{{ $role->value }}">{{ ucfirst($role->value) }}</option>
                            @endforeach
                        </x-form.select>
                    </div>
                </x-form.grid>
            </x-form.section>

            <x-slot:footer>
                <div class="flex items-center justify-end gap-3">
                    <x-ui.button href="{{ route('users.index') }}" variant="secondary">
                        Cancel
                    </x-ui.button>
                    <x-ui.button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-75">
                        <span wire:loading.remove>Create User</span>
                        <span wire:loading>Creating...</span>
                    </x-ui.button>
                </div>
            </x-slot:footer>
        </x-form.card>
    </form>
</div>