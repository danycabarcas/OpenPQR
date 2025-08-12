<!-- ========== Sidebar Start ========== -->
<div class="app-menu navbar-menu">
    <div class="navbar-brand-box">
        <a href="{{ url('/admin') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('velzon_assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('velzon_assets/images/logo-dark.png') }}" alt="" height="24">
            </span>
        </a>
        <a href="{{ url('/admin') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('velzon_assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('velzon_assets/images/logo-light.png') }}" alt="" height="24">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menú</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ url('/admin') }}">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#">
                        <i class="ri-user-3-line"></i> <span data-key="t-users">Usuarios</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#">
                        <i class="ri-building-2-line"></i> <span data-key="t-companies">Empresas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#">
                        <i class="ri-folder-3-line"></i> <span data-key="t-requests">Solicitudes</span>
                    </a>
                </li>
                <!-- Agrega más items aquí -->
            </ul>
        </div>
    </div>
    <div class="sidebar-background"></div>
</div>
<div class="vertical-overlay"></div>
<!-- ========== Sidebar End ========== -->
