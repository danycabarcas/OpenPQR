@php
  function isActive($routes){
    foreach((array)$routes as $r){
      if(request()->routeIs($r)) return 'active';
    }
    return '';
  }
@endphp

<ul class="navbar-nav" id="navbar-nav">

  {{-- Dashboard global --}}
  <li class="nav-item">
    <a class="nav-link menu-link {{ isActive('admin.dashboard') }}" href="{{ route('admin.dashboard') }}">
      <i class="ri-dashboard-2-line"></i> <span>Dashboard</span>
    </a>
  </li>

  <li class="menu-title"><span>Gestión Global</span></li>

  {{-- Planes --}}
  <li class="nav-item">
    <a class="nav-link menu-link {{ isActive('admin.plans.*') }}" href="{{ route('admin.plans.index') }}">
      <i class="ri-price-tag-3-line"></i> <span>Planes</span>
    </a>
  </li>

  {{-- Empresas --}}
  <li class="nav-item">
    <a class="nav-link menu-link {{ isActive('admin.companies.*') }}" href="{{ route('admin.companies.index') }}">
      <i class="ri-building-2-line"></i> <span>Empresas</span>
    </a>
  </li>

  <li class="menu-title"><span>Cuenta</span></li>

  {{-- Perfil --}}
  <li class="nav-item">
    <a class="nav-link menu-link {{ isActive('profile.*') }}" href="{{ route('profile.edit') }}">
      <i class="ri-user-settings-line"></i> <span>Perfil</span>
    </a>
  </li>
</ul>
