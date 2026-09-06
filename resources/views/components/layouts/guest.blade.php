<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    {!! SEO::generate() !!}

    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="@logoPath" type="image/x-icon" />
    <link rel="shortcut icon" href="@logoPath" type="image/x-icon" />

    @webmasterMeta
    @gtmHead

    @livewireStyles
    @stack('styles')
    @vite(['resources/js/plugin/jquery.js', 'resources/js/bootstrap.js', 'resources/js/plugin/sweetalert2.js'])
    @stack('scripts')
    @vite(['resources/scss/app.scss', 'resources/js/alpinejs.js'])
</head>

<body class="bg-body-tertiary">
@gtmBody

{{ $slot }}

@livewireScriptConfig()
</body>
</html>
