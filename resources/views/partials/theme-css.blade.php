@php
  $vars = $theme?->variables ?? [];

  // helpers
  $v = fn($k,$d=null)=> $vars[$k] ?? $d;

  $isDark = (bool)($theme->is_dark ?? false);

  // mapeo mínimo → variables de Bootstrap
  $bs = [
    '--bs-body-bg'          => $v('--bg',           $isDark ? '#0b1220' : '#ffffff'),
    '--bs-body-color'       => $v('--text',         $isDark ? '#e5e7eb' : '#111827'),
    '--bs-card-bg'          => $v('--card-bg',      $isDark ? '#0f172a' : '#ffffff'),
    '--bs-border-color'     => $v('--border',       $isDark ? '#1f2937' : '#e5e7eb'),
    '--bs-heading-color'    => $v('--text',         $isDark ? '#f3f4f6' : '#0f172a'),
    '--bs-link-color'       => $v('--brand',        '#0d6efd'),
    '--bs-link-hover-color' => $v('--brand-hover',  $v('--brand','#0d6efd')),
    '--bs-primary'          => $v('--brand',        '#0d6efd'),
  ];

  // sidebar
  $sidebarBg    = $v('--sidebar-bg',    $isDark ? '#0f172a' : '#1f2937');
  $sidebarText  = $v('--sidebar-text',  $isDark ? '#cbd5e1' : '#e5e7eb');
  $sidebarAct   = $v('--sidebar-active',$v('--brand','#0d6efd'));
@endphp

<style>
  :root{
    {{-- inyecta tus variables personalizadas --}}
    @foreach($vars as $k=>$val) {{ $k }}: {{ $val }}; @endforeach

    {{-- mapea a variables de Bootstrap --}}
    @foreach($bs as $k=>$val) {{ $k }}: {{ $val }}; @endforeach
  }

  /* Fondo y texto global coherentes con el theme */
  body {
    background: var(--bs-body-bg) !important;
    color: var(--bs-body-color) !important;
  }

  /* Cards y bordes */
  .card {
    background: var(--bs-card-bg) !important;
    border-color: var(--bs-border-color) !important;
  }
  .card .card-header {
    background: transparent;
    border-bottom-color: var(--bs-border-color) !important;
  }

  /* Tablas */
  .table {
    color: var(--bs-body-color);
  }
  .table thead,
  .table.table-light thead {
    color: {{ $isDark ? '#e5e7eb' : '#111827' }};
    background-color: {{ $isDark ? '#111827' : '#f8fafc' }};
  }
  .table tbody tr {
    border-color: var(--bs-border-color);
  }

  /* Alerts básicas (ajuste sutil en dark) */
  .alert {
    color: inherit;
    border-color: var(--bs-border-color);
    background-color: {{ $isDark ? '#111827' : '#f8fafc' }};
  }

  /* Links y botones primarios al color de brand */
  a, .link-primary { color: var(--bs-link-color); }
  a:hover, .link-primary:hover { color: var(--bs-link-hover-color); }

  .btn-primary {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
    color: {{ $isDark ? '#0b1220' : '#ffffff' }};
  }
  .btn-outline-primary {
    color: var(--bs-primary);
    border-color: var(--bs-primary);
  }
  .btn-outline-primary:hover {
    background-color: var(--bs-primary);
    color: {{ $isDark ? '#0b1220' : '#ffffff' }};
  }

  /* Sidebar Velzon */
  .app-menu.navbar-menu {
    background: {{ $sidebarBg }};
  }
  .app-menu .nav-link,
  .app-menu .menu-title { color: {{ $sidebarText }}; }
  .app-menu .nav-link.active,
  .app-menu .nav-link:hover { color: {{ $sidebarAct }}; }

  /* Navbar claro/oscuro automático según theme */
  body.theme-dark .navbar { background-color: #0f172a !important; }
  body.theme-dark .navbar .nav-link { color: #e5e7eb !important; }
  body.theme-dark .navbar .navbar-brand { color: #e5e7eb !important; }
  body.theme-dark .navbar .navbar-toggler { border-color: #334155; }
  body.theme-dark .navbar .navbar-toggler-icon {
    filter: invert(0.9);
  }

  /* Inputs y form controls mejorados en dark */
  @if($isDark)
  .form-control, .form-select, .form-check-input {
    background-color: #0b1220;
    color: #e5e7eb;
    border-color: #1f2937;
  }
  .form-control:focus, .form-select:focus {
    background-color: #0b1220;
    color: #e5e7eb;
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 .25rem rgba(96,165,250,.15);
  }
  .form-check-input:checked {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
  }
  @endif
</style>
