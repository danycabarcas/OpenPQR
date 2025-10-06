<!doctype html>
<html lang="es" data-bs-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title','OpenPQR')</title>

  {{-- Bootstrap 5.3 (CDN) --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- Iconos (Material Icons + Bootstrap Icons opcional) --}}
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@300;400;600" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  {{-- Tus estilos --}}
  <link href="{{ asset('app.css') }}" rel="stylesheet">
  @stack('styles')
</head>
<body>
  <div id="app" class="d-flex">

    {{-- SIDEBAR fijo (desktop) --}}
    <aside id="sidebar" class="bg-body border-end">
      <div class="p-3 d-flex align-items-center gap-2 border-bottom">
        <span class="material-symbols-outlined">support_agent</span>
        <strong>OpenPQR</strong>
      </div>

      <nav class="p-2">
        <a class="menu-link {{ request()->routeIs('app.dashboard')?'active':'' }}" href="{{ route('app.dashboard') }}">
          <i class="bi bi-speedometer2 me-2"></i> Panel
        </a>
        <a class="menu-link {{ request()->routeIs('app.requests.*')?'active':'' }}" href="{{ route('app.requests.index') }}">
          <i class="bi bi-card-checklist me-2"></i> PQRSD
        </a>

        @role('company_admin|supervisor')
        <a class="menu-link {{ request()->routeIs('app.departments.*')?'active':'' }}" href="{{ route('app.departments.index') }}">
          <i class="bi bi-diagram-3 me-2"></i> Dependencias
        </a>
        @endrole

        <div class="mt-2 pt-2 border-top">
          <a class="menu-link {{ request()->routeIs('profile.edit')?'active':'' }}" href="{{ route('profile.edit') }}">
            <i class="bi bi-person-gear me-2"></i> Perfil
          </a>

          @role('super_admin')
          <a class="menu-link" href="{{ route('admin.companies.index') }}">
            <i class="bi bi-buildings me-2"></i> Admin Empresas
          </a>
          @endrole
        </div>
      </nav>
    </aside>

    {{-- CONTENIDO --}}
    <div class="flex-grow-1 d-flex flex-column min-vh-100">

      {{-- Topbar --}}
      <header class="navbar navbar-expand bg-body border-bottom sticky-top">
        <div class="container-fluid">
          <div class="d-flex align-items-center gap-2">
            {{-- Hamburguesa (móvil) abre offcanvas --}}
            <button class="btn btn-outline-secondary d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar">
              <span class="material-symbols-outlined">menu</span>
            </button>
            {{-- Toggle colapso (desktop) --}}
            <button id="btnSidebarToggle" class="btn btn-outline-secondary d-none d-lg-inline-flex">
              <span class="material-symbols-outlined">sidebar</span>
            </button>
          </div>

          <div class="ms-auto d-flex align-items-center gap-2">
            <span class="text-muted small d-none d-sm-inline">{{ auth()->user()->name ?? 'Usuario' }}</span>
            <div class="dropdown">
              <button class="btn btn-outline-secondary rounded-circle p-2" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Perfil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <form method="POST" action="{{ route('logout') }}">@csrf
                    <button class="dropdown-item">Cerrar sesión</button>
                  </form>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </header>

      {{-- Page content --}}
      <main class="container-fluid py-3 flex-grow-1">
        @hasSection('page-title')
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h1 class="h4 mb-0">@yield('page-title')</h1>
            @yield('breadcrumbs')
          </div>
        @endif

        @yield('content')
      </main>

      <footer class="border-top py-3">
        <div class="container-fluid small text-muted d-flex justify-content-between">
          <span>&copy; <script>document.write(new Date().getFullYear())</script> OpenPQR</span>
          <span>Hecho con <span class="text-danger">♥</span></span>
        </div>
      </footer>
    </div>
  </div>

  {{-- Offcanvas Sidebar (móvil) --}}
  <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasSidebar">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title">Menú</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
      <nav class="p-2">
        {{-- reutilizamos el mismo menú que en el aside --}}
        <a class="menu-link {{ request()->routeIs('app.dashboard')?'active':'' }}" href="{{ route('app.dashboard') }}">
          <i class="bi bi-speedometer2 me-2"></i> Panel
        </a>
        <a class="menu-link {{ request()->routeIs('app.requests.*')?'active':'' }}" href="{{ route('app.requests.index') }}">
          <i class="bi bi-card-checklist me-2"></i> PQRSD
        </a>
        @role('company_admin|supervisor')
        <a class="menu-link {{ request()->routeIs('app.departments.*')?'active':'' }}" href="{{ route('app.departments.index') }}">
          <i class="bi bi-diagram-3 me-2"></i> Dependencias
        </a>
        @endrole
        <div class="mt-2 pt-2 border-top">
          <a class="menu-link {{ request()->routeIs('profile.edit')?'active':'' }}" href="{{ route('profile.edit') }}">
            <i class="bi bi-person-gear me-2"></i> Perfil
          </a>
          @role('super_admin')
          <a class="menu-link" href="{{ route('admin.companies.index') }}">
            <i class="bi bi-buildings me-2"></i> Admin Empresas
          </a>
          @endrole
        </div>
      </nav>
    </div>
  </div>

  {{-- Bootstrap Bundle (CDN) + tu JS --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('app.js') }}"></script>
  @stack('scripts')
</body>
</html>
