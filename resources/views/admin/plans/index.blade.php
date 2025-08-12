@extends('admin.layouts.app')

@section('title', 'Planes')

@section('page-header')
    <div class="d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Planes</h4>
        <a href="{{ route('admin.plans.create') }}" class="btn btn-primary">
            <i class="ri-add-line"></i> Nuevo plan
        </a>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>PQRS/mes</th>
                        <th>Deps</th>
                        <th>Agentes</th>
                        <th>Branding</th>
                        <th>Dominio</th>
                        <th>Soporte</th>
                        <th>$ Mensual</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($plans as $p)
                    <tr>
                        <td class="fw-semibold">{{ $p->name }}</td>
                        <td>{{ $p->max_requests_per_month == 0 ? 'Ilimitado' : $p->max_requests_per_month }}</td>
                        <td>{{ $p->max_departments == 0 ? 'Ilimitado' : $p->max_departments }}</td>
                        <td>{{ $p->max_users == 0 ? 'Ilimitado' : $p->max_users }}</td>
                        <td>
                            @php
                                $branding = match (true) {
                                    $p->custom_banner => 'Completo',
                                    $p->custom_colors => 'Logo + color',
                                    default => 'Logo'
                                };
                            @endphp
                            {{ $branding }}
                        </td>
                        <td>{{ $p->allow_custom_domain ? 'Propio' : 'Subdominio' }}</td>
                        <td>{{ $p->support_channel === 'whatsapp' ? 'WhatsApp' : 'Tickets + Email' }}</td>
                        <td>
                            @if(($p->price ?? 0) > 0)
                                ${{ number_format($p->price, 0, ',', '.') }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.plans.edit', $p) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="ri-edit-line"></i>
                            </a>
                            <form action="{{ route('admin.plans.destroy', $p) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar plan?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="ri-delete-bin-6-line"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted">Sin planes</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $plans->links() }}
        </div>
    </div>
</div>
@endsection
