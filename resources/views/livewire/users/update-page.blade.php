<div>
    {{-- Page Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit User</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">Update the details for {{ $user->name }}.</p>
        </div>
        <x-ui.button href="{{ route('users.index') }}" variant="secondary"
            icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>'>
            <span class="hidden sm:inline">Back</span>
        </x-ui.button>
    </div>

    <form wire:submit="save">
        <x-form.card title="User Information" description="Update the user's personal details.">

            <x-form.grid :cols="2">
                {{-- First Name --}}
                <div>
                    <x-form.label for="first_name" :required="true">First Name</x-form.label>
                    <x-form.input id="first_name" wire:model="first_name" placeholder="Enter first name" />
                </div>

                {{-- Last Name --}}
                <div>
                    <x-form.label for="last_name" :required="true">Last Name</x-form.label>
                    <x-form.input id="last_name" wire:model="last_name" placeholder="Enter last name" />
                </div>

                {{-- Email --}}
                <div>
                    <x-form.label for="email" :required="true">Email</x-form.label>
                    <x-form.input id="email" type="email" wire:model="email" placeholder="Enter email address" />
                </div>
            </x-form.grid>

            <x-form.section title="Change Password" description="Leave blank to keep the current password.">
                <x-form.grid :cols="2">
                    <div>
                        <x-form.label for="password">New Password</x-form.label>
                        <x-form.password id="password" wire:model="password" placeholder="Enter new password" />
                    </div>

                    <div>
                        <x-form.label for="password_confirmation">Confirm New Password</x-form.label>
                        <x-form.password id="password_confirmation" wire:model="password_confirmation"
                            placeholder="Confirm new password" />
                    </div>
                </x-form.grid>
            </x-form.section>

            <x-form.section title="Access Control" description="Update the role to define what this user can access.">
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
                        <span wire:loading.remove>Save Changes</span>
                        <span wire:loading>Saving...</span>
                    </x-ui.button>
                </div>
            </x-slot:footer>
        </x-form.card>
    </form>
</div>
