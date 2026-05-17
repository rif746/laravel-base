<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    {!! SEO::generate() !!}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" sizes="180x180" href="./assets/images/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./assets/images/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./assets/images/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="./assets/images/favicon_io/site.webmanifest">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @livewireStyles
    @vite(['resources/scss/app.scss', 'resources/js/alpinejs.js', 'resources/js/plugin/jquery.js', 'resources/js/bootstrap.js', 'resources/js/plugin/sweetalert2.js'])
    @stack('styles')
    @stack('scripts')
</head>

<body>

    <div class="d-flex align-items-center justify-content-center min-vh-100 container">
        <div class="card" style="max-width:420px; width:100%;">
            <div class="card-body p-5">
                <div class="mb-3 text-center">
                    <a href="{{ url('/') }}" class="d-inline-block mb-4">
                        <img src="{{ temp_asset('images/logo.svg') }}" alt="" width="36">
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
