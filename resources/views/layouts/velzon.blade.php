<!doctype html>
<html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">
<head>
  <meta charset="utf-8" />
  <title>@yield('title','OpenPQR')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  {{-- CSS Velzon (ajusta rutas si tu carpeta es distinta) --}}
  <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/custom.min.css') }}" rel="stylesheet" />
  @stack('styles')
</head>
<body>
  <div id="layout-wrapper">

    {{-- TOPBAR con hamburguesa --}}
    @includeIf('admin.layouts.partials.topbar')

    {{-- SIDEBAR (fijo desktop + offcanvas móvil) --}}
    @includeIf('admin.layouts.partials.sidebar')

    <div class="main-content">
      <div class="page-content">
        <div class="container-fluid">

          {{-- Título / migas (opcional) --}}
          @hasSection('page-title')
            <div class="row">
              <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                  <h4 class="mb-sm-0">@yield('page-title')</h4>
                  @yield('breadcrumbs')
                </div>
              </div>
            </div>
          @endif

          @yield('content')

        </div>
      </div>

      @includeIf('layouts.partials.velzon-footer')
    </div>
  </div>

  {{-- JS Velzon --}}
  <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/js/app.js') }}"></script>
  @stack('scripts')
</body>
</html>
