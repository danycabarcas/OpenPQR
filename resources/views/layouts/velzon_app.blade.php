<!doctype html>
<html lang="es" data-layout="vertical" data-sidebar-size="lg">
<head>
  <meta charset="utf-8" />
  <title>@yield('title','Empresa - OpenPQR')</title>
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
    @includeIf('app.layouts.partials.topbar')

    {{-- Sidebar Empresa --}}
    <div class="app-menu navbar-menu">
      <div class="navbar-brand-box text-center py-3">
  <a href="{{ route('app.dashboard') }}" class="d-block">
    <img src="{{ asset('assets/images/logo-light.png') }}" alt="OpenPQR" height="40">
  </a>
</div>

      <div id="scrollbar">
        <div class="container-fluid">
          @include('app.layouts.partials.sidebar-menu')
        </div>
      </div>
    </div>

    {{-- Overlay (para móvil) --}}
    <div class="vertical-overlay"></div>

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
  <script src="{{ asset('assets/js/app.js') }}"></script>

  {{-- Script custom para colapsar sidebar en desktop --}}
  <script>
  document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('btn-sidebar-toggle');
    if (btn) {
      btn.addEventListener('click', () => {
        const html = document.documentElement;
        const current = html.getAttribute('data-sidebar-size') || 'lg';
        html.setAttribute('data-sidebar-size', current === 'lg' ? 'sm' : 'lg');
      });
    }
  });
  </script>
</body>
</html>
