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
    @vite(['resources/scss/app.scss', 'resources/js/alpinejs.js', 'resources/js/plugin/jquery.js', 'resources/js/bootstrap.js', 'resources/js/plugin/sweetalert2.js'])
    @stack('styles')
    @stack('scripts')
</head>

<body>

    <div class="d-flex align-items-center justify-content-center min-vh-100 container">
        {{ $slot }}
    </div>

</body>

</html>
