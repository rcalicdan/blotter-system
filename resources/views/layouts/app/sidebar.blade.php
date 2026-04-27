<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @fluxAppearance
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky collapsible="mobile"
        class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.header class="mb-4 px-2">
            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-3">
                <div
                    class="flex aspect-square size-10 items-center justify-center rounded-xl bg-gray-900 dark:bg-zinc-800 text-white shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="size-6 text-red-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.905c-.921-.137-1.855-.188-2.79-.153m2.79.153c.646.096 1.256.43 1.711.934L21 7.5v.375m-4.75-2.97c-1.882.102-3.723.51-5.45 1.191m5.45-1.19c.469.027.94.044 1.41.05m-1.41-.05c-1.182.067-2.357.216-3.51.445m10.141 4.07c1.615.283 2.586 1.889 2.094 3.446m-2.094-3.447a14.223 14.223 0 00-4.242-.451m1.15 9.201a12.248 12.248 0 01-6.062-1.47M12 12.25V3m0 0l2.25 2.25M12 3L9.75 5.25" />
                    </svg>
                </div>

                <div class="flex flex-col">
                    <span
                        class="text-sm font-black text-gray-900 dark:text-white uppercase leading-tight">JusticeSync</span>
                    <span class="text-[9px] font-bold text-red-600 uppercase tracking-widest italic">Incident
                        Manager</span>
                </div>
            </a>
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                wire:navigate>
                {{ __('Dashboard') }}
            </flux:sidebar.item>

            <flux:sidebar.item icon="users" :href="route('users.index')" :current="request()->routeIs('users.*')"
                wire:navigate>
                {{ __('Users') }}
            </flux:sidebar.item>

            <flux:sidebar.item icon="shield-check" :href="route('officers.index')"
                :current="request()->routeIs('officers.*')" wire:navigate>
                {{ __('Officers') }}
            </flux:sidebar.item>

            <flux:sidebar.item icon="briefcase" :href="route('judges.index')" :current="request()->routeIs('judges.*')"
                wire:navigate>
                {{ __('Judges') }}
            </flux:sidebar.item>

            <flux:sidebar.item icon="users" :href="route('people.index')" :current="request()->routeIs('people.*')"
                wire:navigate>
                {{ __('People') }}
            </flux:sidebar.item>

            <flux:sidebar.item icon="document-text" :href="route('blotters.index')"
                :current="request()->routeIs('blotters.*')" wire:navigate>
                {{ __('Blotter') }}
            </flux:sidebar.item>

            <flux:sidebar.item icon="scale" :href="route('disputes.index')"
                :current="request()->routeIs('disputes.*')" wire:navigate>
                {{ __('Disputes') }}
            </flux:sidebar.item>

            <flux:sidebar.item icon="calendar" :href="route('hearings.index')"
                :current="request()->routeIs('hearings.*')" wire:navigate>
                {{ __('Hearings') }}
            </flux:sidebar.item>
        </flux:sidebar.nav>

        <flux:spacer />

        <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                        {{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer" data-test="logout-button">
                        {{ __('Log out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @fluxScripts
</body>

</html>
