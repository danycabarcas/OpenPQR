<!-- ========== Navbar Start ========== -->
<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- Botón del menú móvil -->
                <button type="button" class="btn btn-sm px-3 fs-16 header-item" id="vertical-menu-btn">
                    <i class="ri-menu-2-line"></i>
                </button>
                <!-- Puedes poner aquí logo o nombre -->
            </div>

            <div class="d-flex align-items-center">
                <!-- Usuario -->
                <div class="dropdown ms-sm-3 header-item topbar-user">
                    <button type="button" class="btn" id="page-header-user-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user"
                                 src="{{ asset('velzon_assets/images/users/avatar-1.jpg') }}"
                                 alt="Avatar">
                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">
                                    {{ Auth::user()->name ?? 'Admin' }}
                                </span>
                                <span class="d-none d-xl-block ms-1 fs-12 user-name-sub-text">
                                    {{ Auth::user()->email ?? '' }}
                                </span>
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <h6 class="dropdown-header">¡Bienvenido!</h6>
                        <a class="dropdown-item" href="#"><i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Perfil</span></a>
                        <a class="dropdown-item" href="#"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Salir</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- ========== Navbar End ========== -->
