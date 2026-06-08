<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8"/>
    {!! SEO::generate() !!}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset_static($favicon ?? 'images/logo.svg') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset_static($favicon ?? 'images/logo.svg') }}" type="image/x-icon">
    @webmasterMeta
    @gtmHead

    @livewireStyles
    @stack('styles')
    @vite(['resources/js/plugin/jquery.js', 'resources/js/bootstrap.js', 'resources/js/plugin/sweetalert2.js'])
    @stack('scripts')
    @vite(['resources/scss/app.scss', 'resources/js/alpinejs.js'])
</head>

<body>
@gtmBody

<div id="overlay" class="overlay"></div>
<!-- TOPBAR -->
<x-layouts.nav.topbar/>

<!-- SIDEBAR -->
<x-layouts.nav.sidebar/>

<!-- MAIN CONTENT -->
<main id="content" class="content">
    <div class="container-fluid min-vh-90 pt-10">
        {{ $slot }}
    </div>

    <footer class="text-secondary mt-6 py-2 text-center">
        <p>Copyright © 2026 InApp Inventory Dashboard. Developed by <a href="https://codescandy.com/"
                                                                       target="_blank"
                                                                       class="text-primary">CodesCandy</a> •
            Distributed by <a href="https://themewagon.com/" target="_blank" class="text-primary">ThemeWagon</a>
        </p>
    </footer>
</main>

@stack('page-scripts')
@livewireScriptConfig()
</body>

</html>
