{{-- resources/views/admin/layouts/app.blade.php --}}
<!doctype html>
<html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Panel') | OpenPQR</title>

    {{-- CSS núcleo Velzon (TU RUTA) --}}
    <link rel="stylesheet" href="{{ asset('velzon_assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('velzon_assets/css/icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('velzon_assets/css/app.min.css') }}">
    {{-- libs que usa el layout (simplebar, waves, etc.) --}}
    <link rel="stylesheet" href="{{ asset('velzon_assets/libs/simplebar/simplebar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('velzon_assets/libs/waves/waves.min.css') }}">

    {{-- Si vas a inyectar CSS por página --}}
    @stack('styles')
</head>

<body>

<div id="layout-wrapper">
    {{-- Topbar / Header --}}
    @include('admin.layouts.partials.topbar')

    {{-- Sidebar --}}
    @include('admin.layouts.partials.sidebar')

    {{-- Contenido --}}
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                @hasSection('page-header')
                    <div class="row mb-3">
                        <div class="col-12">
                            @yield('page-header')
                        </div>
                    </div>
                @endif

                {{-- flashes / errores --}}
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>

        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 text-muted">
                        © {{ date('Y') }} OpenPQR.
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>

{{-- JS núcleo Velzon (TU RUTA) --}}
<script src="{{ asset('velzon_assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('velzon_assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('velzon_assets/libs/waves/waves.min.js') }}"></script>
<script src="{{ asset('velzon_assets/js/layout.js') }}"></script>
<script src="{{ asset('velzon_assets/js/app.min.js') }}"></script>

{{-- scripts por página --}}
@stack('scripts')
</body>
</html>
