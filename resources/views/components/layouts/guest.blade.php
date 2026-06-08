<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8"/>
    {!! SEO::generate() !!}
    <link rel="icon" href="{{ asset_static($settings['web-favicon'] ?? 'images/logo.svg') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset_static($settings['web-favicon'] ?? 'images/logo.svg') }}" type="image/x-icon">
    @if (isset($settings['google-tag_manager_id']))
        <!-- Google Tag Manager -->
        <script>
            (function (w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start': new Date().getTime(),
                    event: 'gtm.js'
                });
                const f = d.getElementsByTagName(s)[0];
                j = d.createElement(s);
                const dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src = 'https://googletagmanager.com'
            })(window, document, 'script', 'dataLayer', '{{ $settings['google-tag_manager_id'] }}');
        </script>
        <!-- End Google Tag Manager -->
    @endif

    @livewireStyles
    @stack('styles')
    @vite(['resources/js/plugin/jquery.js', 'resources/js/bootstrap.js', 'resources/js/plugin/sweetalert2.js'])
    @stack('scripts')
    @vite(['resources/scss/app.scss', 'resources/js/alpinejs.js'])
</head>

<body>

<div class="d-flex align-items-center justify-content-center min-vh-100 container">
    <div class="card" style="max-width:420px; width:100%;">
        <div class="card-body p-5">
            <div class="mb-3 text-center">
                <a href="{{ url('/') }}" class="d-inline-block mb-4">
                    <img src="{{ asset_static('images/logo.svg') }}" alt="" width="36">
                </a>
                <h1 class="card-title h5 mb-5">{{ __($title ?? 'Laravel') }}</h1>

            </div>

            @if (session()->has('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            {{ $slot }}
        </div>
    </div>

</div>

@livewireScriptConfig()
</body>

</html>
