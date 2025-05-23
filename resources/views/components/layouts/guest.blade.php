<!DOCTYPE html>
<html lang="{{ $lang }}" data-theme="{{ $theme }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {!! SEO::generate() !!}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('scripts')
    @stack('styles')
</head>

<body class="min-h-screen font-sans antialiased bg-base-200/50 dark:bg-base-200">
    {{-- The navbar with `sticky` and `full-width` --}}
    <x-nav class="bg-base-200" sticky full-width>

        <x-slot:brand>
            {{-- Brand --}}
            <x-app-brand />
        </x-slot:brand>

        {{-- Right side actions --}}
        <x-slot:actions>
            @guest
                <x-dropdown class="block md:hidden" no-x-anchor right>
                    <x-slot:trigger>
                        <x-button icon="o-bars-3" class="btn-sm btn-ghost block md:hidden" />
                    </x-slot:trigger>

                    <x-menu-item :label="__('Login')" icon="o-arrow-right-end-on-rectangle" :link="route('login')" />
                    <x-menu-item :label="__('Register')" icon="o-user-plus" :link="route('register')" />
                </x-dropdown>
                <div class="hidden md:block">
                    <x-button :label="__('Login')" icon="o-arrow-right-end-on-rectangle" :link="route('login')" class="btn-ghost btn-sm" responsive />
                    <x-button :label="__('Register')" icon="o-user-plus" :link="route('register')" class="btn-ghost btn-sm" responsive />
                </div>
            @else
                <x-dropdown class="block md:hidden" no-x-anchor right>
                    <x-slot:trigger>
                        <x-button icon="o-bars-3" class="btn-sm btn-ghost block md:hidden" />
                    </x-slot:trigger>

                    <x-menu-item :label="__('Dashboard')" icon="o-home" :link="route('login')" />
                </x-dropdown>
                <div class="hidden md:block">
                    <x-button :label="__('Dashboard')" icon="o-home" :link="route('dashboard')" class="btn-ghost btn-sm" responsive />
                </div>
            @endguest
        </x-slot:actions>
    </x-nav>

    {{-- The main content with `full-width` --}}
    <x-main full-width>
        {{-- The `$slot` goes here --}}
        <x-slot:content>
            {{ $slot }}
        </x-slot:content>
    </x-main>


    {{--  TOAST area --}}
    <x-toast position="toast-top toast-right" />
</body>
</html>
