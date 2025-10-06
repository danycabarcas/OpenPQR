@php
  function isActive($routes){
    foreach((array)$routes as $r){
      if(request()->routeIs($r)) return 'active';
    }
    return '';
  }
@endphp

<ul class="navbar-nav" id="navbar-nav">

  {{-- Dashboard empresa --}}
  <li class="nav-item">
    <a class="nav-link menu-link {{ isActive('app.dashboard') }}" href="{{ route('app.dashboard') }}">
      <i class="ri-dashboard-2-line"></i> <span>Dashboard</span>
    </a>
  </li>

  <li class="menu-title"><span>Gestión</span></li>

  {{-- PQRSD --}}
  <li class="nav-item">
    <a class="nav-link menu-link {{ isActive('app.requests.*') }}" href="{{ route('app.requests.index') }}">
      <i class="ri-file-list-3-line"></i> <span>PQRSD</span>
    </a>
  </li>

  {{-- Dependencias (solo ciertos roles) --}}
  @role('company_admin|supervisor')
  <li class="nav-item">
    <a class="nav-link menu-link {{ isActive('app.departments.*') }}" href="{{ route('app.departments.index') }}">
      <i class="ri-team-line"></i> <span>Dependencias</span>
    </a>
  </li>
  @endrole

  <li class="menu-title"><span>Cuenta</span></li>

  {{-- Perfil --}}
  <li class="nav-item">
    <a class="nav-link menu-link {{ isActive('profile.*') }}" href="{{ route('profile.edit') }}">
      <i class="ri-user-settings-line"></i> <span>Perfil</span>
    </a>
  </li>
</ul>
