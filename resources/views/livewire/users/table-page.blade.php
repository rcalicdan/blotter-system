<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">User Management</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage system users and their roles.</p>
        </div>
        <x-ui.button href="#"
            icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>'>
            <span class="hidden sm:inline">Add User</span>
        </x-ui.button>
    </div>

    <x-table.index>
        {{-- Table Header / Filters --}}
        <x-table.header :searchable="true">
            <x-slot:filters>
                <select wire:model.live="roleFilter"
                    class="pl-3 pr-8 py-2.5 bg-gray-50 dark:bg-zinc-800 dark:border-zinc-700 dark:text-gray-300 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-600/20 focus:border-red-600 transition-all">
                    <option value="">All Roles</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->value }}">{{ ucfirst($role->value) }}</option>
                    @endforeach
                </select>
            </x-slot:filters>
        </x-table.header>

        {{-- Table --}}
        <table class="w-full">
            <x-table.head>
                <x-table.cell header sortable sortField="name">Name</x-table.cell>
                <x-table.cell header sortable sortField="email" class="hidden md:table-cell">Email</x-table.cell>
                <x-table.cell header sortable sortField="role" class="hidden sm:table-cell">Role</x-table.cell>
                <x-table.cell header sortable sortField="created_at" class="hidden lg:table-cell">Joined</x-table.cell>
                <x-table.cell header class="text-right">Actions</x-table.cell>
            </x-table.head>

            <x-table.body>
                @forelse ($users as $user)
                    <x-table.row wire:key="user-{{ $user->id }}">
                        {{-- Name (also shows email + role on mobile) --}}
                        <x-table.cell>
                            <div class="flex items-center gap-3">
                                <x-ui.avatar :name="$user->name" />
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                                        @if ($user->id === auth()->id())
                                            <span class="text-[10px] font-bold text-red-600 uppercase tracking-wider">You</span>
                                        @endif
                                    </div>
                                    {{-- Shown only on mobile --}}
                                    <p class="text-xs text-gray-500 dark:text-zinc-400 truncate mt-0.5 md:hidden">
                                        {{ $user->email }}
                                    </p>
                                    <div class="mt-1 sm:hidden">
                                        @php
                                            $roleVariant = match ($user->role) {
                                                \App\Enums\UserRole::SuperAdmin => 'danger',
                                                \App\Enums\UserRole::Admin      => 'warning',
                                                \App\Enums\UserRole::Staff      => 'info',
                                                default                         => 'secondary',
                                            };
                                        @endphp
                                        <x-ui.badge :variant="$roleVariant">
                                            {{ $user->role ? ucfirst($user->role->value) : 'No Role' }}
                                        </x-ui.badge>
                                    </div>
                                </div>
                            </div>
                        </x-table.cell>

                        {{-- Email (hidden on mobile) --}}
                        <x-table.cell class="hidden md:table-cell text-gray-500 dark:text-zinc-400">
                            {{ $user->email }}
                        </x-table.cell>

                        {{-- Role (hidden on mobile) --}}
                        <x-table.cell class="hidden sm:table-cell">
                            @php
                                $roleVariant = match ($user->role) {
                                    \App\Enums\UserRole::SuperAdmin => 'danger',
                                    \App\Enums\UserRole::Admin      => 'warning',
                                    \App\Enums\UserRole::Staff      => 'info',
                                    default                         => 'secondary',
                                };
                            @endphp
                            <x-ui.badge :variant="$roleVariant">
                                {{ $user->role ? ucfirst($user->role->value) : 'No Role' }}
                            </x-ui.badge>
                        </x-table.cell>

                        {{-- Joined (hidden on mobile + tablet) --}}
                        <x-table.cell class="hidden lg:table-cell text-gray-500 dark:text-zinc-400">
                            {{ $user->created_at->format('M d, Y') }}
                        </x-table.cell>

                        {{-- Actions --}}
                        <x-table.cell class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                <x-ui.edit-button href="#" />

                                @if ($user->id !== auth()->id())
                                    <x-ui.delete-button :id="$user->id" :name="$user->name" resource="user" />
                                @endif
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="5" class="py-12 text-center text-gray-400 dark:text-zinc-500">
                            <svg class="mx-auto mb-3 w-10 h-10 text-gray-200 dark:text-zinc-700" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <p class="text-sm font-medium">No users found</p>
                            <p class="text-xs mt-1">Try adjusting your search or filter.</p>
                        </x-table.cell>
                    </x-table.row>
                @endforelse
            </x-table.body>
        </table>

        {{-- Pagination --}}
        <x-ui.pagination :paginator="$users" />
    </x-table.index>
</div>