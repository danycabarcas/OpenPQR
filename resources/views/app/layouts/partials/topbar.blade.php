<header id="page-topbar">
  <div class="layout-width">
    <div class="navbar-header d-flex align-items-center justify-content-between px-3">

      {{-- Botones de menú --}}
      <div class="d-flex align-items-center gap-2">
        {{-- Hamburguesa (móvil) --}}
        <button class="btn btn-sm btn-ghost-secondary d-lg-none"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasSidebar"
                aria-controls="offcanvasSidebar">
          <i class="ri-menu-2-line fs-20"></i>
        </button>

        {{-- Toggle sidebar desktop --}}
        <button id="btn-sidebar-toggle" class="btn btn-sm btn-ghost-secondary d-none d-lg-inline-flex" type="button">
          <i class="ri-sidebar-fold-line fs-20"></i>
        </button>

        {{-- Logo
        <a href="{{ route('app.dashboard') }}" class="navbar-brand d-flex align-items-center gap-2">
          <img src="{{ asset('assets/images/logo-sm.png') }}" alt="logo" height="24">
          <span class="fw-semibold d-none d-sm-inline">OpenPQR</span>
        </a> --}}
      </div>

      {{-- Perfil de usuario --}}
      <div class="d-flex align-items-center gap-2">
        <div class="dropdown">
          <a class="d-flex align-items-center text-reset" href="#" role="button" data-bs-toggle="dropdown">
            <img class="rounded-circle header-profile-user" src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="Avatar">
            <span class="ms-2 d-none d-sm-inline fw-medium">{{ auth()->user()->name ?? 'Usuario' }}</span>
          </a>
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
  </div>
</header>
