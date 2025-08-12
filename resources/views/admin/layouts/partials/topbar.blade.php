<header id="page-topbar">
    <div class="navbar-header d-flex justify-content-between align-items-center p-2">
        <div class="d-flex align-items-center">
            <button type="button" class="btn btn-sm px-3 fs-16 header-item" id="vertical-menu-btn">
                <i class="ri-menu-2-line"></i>
            </button>
            <a href="{{ route('dashboard') }}" class="ms-2 fw-semibold text-decoration-none">
                OpenPQR
            </a>
        </div>

        <div class="d-flex align-items-center">
            <div class="dropdown">
                <a class="d-block text-reset dropdown-toggle" data-bs-toggle="dropdown" href="#">
                    {{ auth()->user()->name ?? 'Usuario' }}
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">Perfil</a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="dropdown-item">Salir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
