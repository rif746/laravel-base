<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    {!! SEO::generate() !!}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/scss/app.scss', 'resources/js/alpinejs.js', 'resources/js/plugin/jquery.js', 'resources/js/bootstrap.js', 'resources/js/plugin/sweetalert2.js'])
    @stack('styles')
</head>

<body>
    <div id="overlay" class="overlay"></div>
    <!-- TOPBAR -->
    <x-layout.nav.topbar />

    <!-- SIDEBAR -->
    <x-layout.nav.sidebar />

    <!-- MAIN CONTENT -->
    <main id="content" class="content py-10">
        <div class="container-fluid">
            {{ $slot }}
        </div>
    </main>


    @stack('scripts')
</body>

</html>
