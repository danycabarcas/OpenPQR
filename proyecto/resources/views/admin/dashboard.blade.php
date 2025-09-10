@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">

    <!-- Título y bienvenida -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-0">Dashboard OpenPQR</h4>
            <small class="text-muted">Bienvenido al panel de control. Aquí podrás ver estadísticas y acceder a los módulos principales.</small>
        </div>
    </div>

    <!-- Cards de estadísticas -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 avatar-sm">
                            <span class="avatar-title bg-primary rounded-2 fs-2">
                                <i class="ri-folder-3-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-1">Solicitudes</p>
                            <h4 class="mb-0"><span class="counter-value" data-target="120">120</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 avatar-sm">
                            <span class="avatar-title bg-success rounded-2 fs-2">
                                <i class="ri-user-3-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-1">Usuarios</p>
                            <h4 class="mb-0"><span class="counter-value" data-target="8">8</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 avatar-sm">
                            <span class="avatar-title bg-warning rounded-2 fs-2">
                                <i class="ri-building-2-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-1">Empresas</p>
                            <h4 class="mb-0"><span class="counter-value" data-target="1">1</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 avatar-sm">
                            <span class="avatar-title bg-info rounded-2 fs-2">
                                <i class="ri-group-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-1">Departamentos</p>
                            <h4 class="mb-0"><span class="counter-value" data-target="4">4</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de solicitudes recientes -->
    <div class="row">
        <div class="col-12">
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Solicitudes recientes</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tipo</th>
                                    <th>Asunto</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>00120</td>
                                    <td>Petición</td>
                                    <td>Solicitud de certificado</td>
                                    <td><span class="badge bg-info">En proceso</span></td>
                                    <td>2024-06-15</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">Ver</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>00119</td>
                                    <td>Queja</td>
                                    <td>Atención demorada</td>
                                    <td><span class="badge bg-success">Resuelta</span></td>
                                    <td>2024-06-14</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">Ver</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>00118</td>
                                    <td>Reclamo</td>
                                    <td>Documento incorrecto</td>
                                    <td><span class="badge bg-danger">Rechazada</span></td>
                                    <td>2024-06-13</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">Ver</a>
                                    </td>
                                </tr>
                                <!-- Más filas de ejemplo -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- container-fluid -->
@endsection
