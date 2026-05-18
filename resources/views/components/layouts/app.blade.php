<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    {!! SEO::generate() !!}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ temp_asset($settings['web-favicon']) }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ temp_asset($settings['web-favicon']) }}" type="image/x-icon">
    @if (isset($settings['google-tag_manager_id']))
        <!-- Google Tag Manager -->
        <script>
            (function(w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start': new Date().getTime(),
                    event: 'gtm.js'
                });
                var f = d.getElementsByTagName(s)[0];
                j = d.createElement(s);
                dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src = 'https://googletagmanager.com;
            })(window, document, 'script', 'dataLayer', '{{ $settings['google-tag_manager_id'] }}');
        </script>
        <!-- End Google Tag Manager -->
    @endif

    @livewireStyles
    @stack('scripts')
    @vite(['resources/scss/app.scss', 'resources/js/alpinejs.js', 'resources/js/plugin/jquery.js', 'resources/js/bootstrap.js', 'resources/js/plugin/sweetalert2.js'])
    @stack('styles')
</head>

<body>
    @if (isset($settings['google-tag_manager_id']))
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://googletagmanager.com" height="0" width="0"
                style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
    @endif

    <div id="overlay" class="overlay"></div>
    <!-- TOPBAR -->
    <x-layouts.nav.topbar />

    <!-- SIDEBAR -->
    <x-layouts.nav.sidebar />

    <!-- MAIN CONTENT -->
    <main id="content" class="content">
        <div class="container-fluid min-vh-90 pt-10">
            {{ $slot }}
        </div>

        <footer class="text-secondary mt-6 py-2 text-center">
            <p>Copyright © 2026 InApp Inventory Dashboard. Developed by <a href="https://codescandy.com/"
                    target="_blank" class="text-primary">CodesCandy</a> •
                Distributed by <a href="https://themewagon.com/" target="_blank" class="text-primary">ThemeWagon</a>
            </p>
        </footer>
    </main>

    @livewireScriptConfig()
</body>

</html>
