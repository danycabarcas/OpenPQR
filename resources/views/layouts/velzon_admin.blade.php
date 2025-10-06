<!doctype html>
<html lang="es"
      data-layout="vertical"
      data-topbar="light"
      data-sidebar="dark"
      data-sidebar-size="lg">

<head>
  <meta charset="utf-8" />
  <title>@yield('title','Admin - OpenPQR')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  {{-- Velzon CSS --}}
  <link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">

</head>

<body>
  {{-- Placeholders que espera Velzon --}}
  <div id="preloader" style="display:none;"><div id="status"></div></div>
  <div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3"></div>

  <div id="layout-wrapper">
    {{-- Topbar --}}
    @includeIf('admin.layouts.partials.topbar')

    {{-- Sidebar Admin --}}
    <div class="app-menu navbar-menu">
      <div class="navbar-brand-box">
        <a href="{{ route('admin.dashboard') }}" class="logo logo-dark">
          <span class="logo-sm"><img src="{{ asset('assets/images/logo-sm.png') }}" height="40"></span>
          <span class="logo-lg"><img src="{{ asset('assets/images/logo-dark.png') }}" height="40"></span>
        </a>
        <a href="{{ route('admin.dashboard') }}" class="logo logo-light">
          <span class="logo-sm"><img src="{{ asset('assets/images/logo-sm.png') }}" height="40"></span>
          <span class="logo-lg"><img src="{{ asset('assets/images/logo-light.png') }}" height="40"></span>
        </a>
      </div>

      <div id="scrollbar">
        <div class="container-fluid">
          @include('admin.layouts.partials.sidebar-menu')
        </div>
      </div>
    </div>

    {{-- Contenido principal --}}
    <div class="main-content">
      <div class="page-content">
        <div class="container-fluid">
          @yield('content')
        </div>
      </div>
      @includeIf('layouts.partials.velzon-footer')
    </div>
  </div>

  {{-- JS Velzon --}}
  <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script>
  document.addEventListener('DOMContentLoaded', () => {
    // Toggle sidebar en desktop
    const btn = document.getElementById('btn-sidebar-toggle');
    if (btn) {
      btn.addEventListener('click', () => {
        const html = document.documentElement;
        const current = html.getAttribute('data-sidebar-size') || 'lg';
        html.setAttribute('data-sidebar-size', current === 'lg' ? 'sm' : 'lg');
      });
    }

    // Offcanvas (móvil) ya funciona con Bootstrap 5
    // solo asegúrate que el botón tenga data-bs-target="#offcanvasSidebar"
    // y que exista el div#offcanvasSidebar
  });
</script>
  <script src="{{ asset('assets/js/app.js') }}"></script>
</body>
</html>
