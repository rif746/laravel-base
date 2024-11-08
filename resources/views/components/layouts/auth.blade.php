<!DOCTYPE html>
<html lang="{{ $lang }}" data-theme="{{ $theme }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('scripts')
    @stack('styles')
</head>

<body class="min-h-svh font-sans flex items-center justify-center antialiased bg-base-200/50 dark:bg-base-200">
    {{-- The main content with `full-width` --}}
    <x-main full-width>
        {{-- The `$slot` goes here --}}
        <x-slot:content class="flex flex-col items-center justify-center gap-4">
            <x-app-brand icon-width="w-10" text-size="text-5xl" />
            <div class="max-w-md">
                {{ $slot }}
            </div>
        </x-slot:content>
    </x-main>


    {{--  TOAST area --}}
    <x-toast position="toast-top toast-right" />
</body>

</html>
