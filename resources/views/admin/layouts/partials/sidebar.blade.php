<div class="app-menu navbar-menu">
    <div class="navbar-brand-box">
        <a href="{{ route('dashboard') }}" class="logo logo-light text-decoration-none">
            <span class="logo-sm">OP</span>
            <span class="logo-lg">OpenPQR</span>
        </a>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span>Administración</span></li>

                <a href="{{ route('admin.companies.index') }}" class="nav-link">
                <i class="ri-building-2-line"></i> Empresas
                </a>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.plans.*') ? 'active' : '' }}"
                       href="{{ route('admin.plans.index') }}">
                        <i class="ri-price-tag-3-line"></i>
                        <span>Planes</span>
                    </a>
                </li>


                {{-- agrega más items según tus módulos --}}
            </ul>
        </div>
    </div>

    <div class="sidebar-background"></div>
</div>
<div class="vertical-overlay"></div>
