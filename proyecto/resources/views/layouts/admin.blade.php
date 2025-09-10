<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Panel OpenPQR')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS de Velzon -->
    <link href="{{ asset('velzon_assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('velzon_assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('velzon_assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('velzon_assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    @include('partials.admin_sidebar')

    <!-- Navbar -->
    @include('partials.admin_navbar')

    <!-- Contenido principal -->
    <div class="main-content">
        <div class="page-content">
            @yield('content')
        </div>
    </div>

    <!-- JS de Velzon -->
    <script src="{{ asset('velzon_assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('velzon_assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('velzon_assets/js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
