<!DOCTYPE html>
<html lang="{{ $lang }}" data-theme="{{ $theme }}" class="{{ $theme }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen font-sans antialiased bg-base-200/50 dark:bg-base-200">

    {{-- The navbar with `sticky` and `full-width` --}}
    <x-nav class="bg-base-200" sticky full-width>

        <x-slot:brand>
            {{-- Drawer toggle for "main-drawer" --}}
            <label for="main-drawer" class="lg:hidden mr-3">
                <x-icon name="o-bars-3" class="cursor-pointer" />
            </label>

            {{-- Brand --}}
            <x-app-brand />
        </x-slot:brand>
    </x-nav>

    {{-- The main content with `full-width` --}}
    <x-main with-nav full-width>

        {{-- This is a sidebar that works also as a drawer on small screens --}}
        {{-- Notice the `main-drawer` reference here --}}
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-200">

            {{-- User --}}
            @if ($user = auth()->user())
                <x-list-item :link="route('profile.index')" :item="$user" value="name" sub-value="email" no-separator class="pt-2">
                    <x-slot:actions>
                        <form id="logout" method="POST" action="{{ url('/logout') }}">
                            @csrf
                            <x-button x-on:click="document.getElementById('logout').submit()" icon="o-power"
                                class="btn-circle btn-ghost btn-xs" tooltip-left="logoff" no-wire-navigate />
                        </form>
                    </x-slot:actions>
                </x-list-item>

                <x-menu-separator />
            @endif

            {{-- Activates the menu item when a route matches the `link` property --}}
            <x-menu activate-by-route>
                <x-menu-item :title="__('locale/nav.dashboard')" icon="o-home" link="{{ route('dashboard') }}" />
                <x-menu-item :title="__('locale/nav.roles')" icon="o-shield-check" link="{{ route('role.index') }}" />
                <x-menu-item :title="__('locale/nav.users')" icon="o-user-group" link="{{ route('user.index') }}" />
            </x-menu>
        </x-slot:sidebar>

        {{-- The `$slot` goes here --}}
        <x-slot:content>
            {{ $slot }}
        </x-slot:content>
    </x-main>

    {{--  TOAST area --}}
    <x-toast position="toast-top toast-right" />
</body>

</html>
