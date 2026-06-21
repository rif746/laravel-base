<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8"/>
    {!! SEO::generate() !!}

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
