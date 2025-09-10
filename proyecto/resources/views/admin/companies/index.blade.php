@extends('admin.layouts.app')

@section('title', 'Empresas')

@section('page-header')
<div class="d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Empresas</h4>
    <a href="{{ route('admin.companies.create') }}" class="btn btn-primary">
        <i class="ri-add-line"></i> Nueva empresa
    </a>
</div>
@endsection

@section('content')
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-6">
                <input type="text" name="q" class="form-control" placeholder="Buscar por nombre, slug o email..."
                       value="{{ request('q') }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-secondary"><i class="ri-search-line"></i> Buscar</button>
            </div>
            @if(request()->has('q') && request('q') !== '')
            <div class="col-auto">
                <a href="{{ route('admin.companies.index') }}" class="btn btn-light">Limpiar</a>
            </div>
            @endif
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Empresa</th>
                    <th>Contacto</th>
                    <th>Plan actual</th>
                    <th>Suscripción</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($companies as $company)
                @php
                    $sub = $company->activeSubscription; // relación helper
                    $plan = $sub?->plan;
                @endphp
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $company->name }}</div>
                        <div class="text-muted small">/{{ $company->slug }}</div>
                        @if($company->sector)
                            <div class="text-muted small">{{ $company->sector }}</div>
                        @endif
                    </td>
                    <td>
                        @if($company->email_contact)
                            <div class="small"><i class="ri-mail-line me-1"></i>{{ $company->email_contact }}</div>
                        @endif
                        @if($company->phone_contact)
                            <div class="small"><i class="ri-phone-line me-1"></i>{{ $company->phone_contact }}</div>
                        @endif
                    </td>
                    <td>
                        @if($plan)
                            <span class="badge bg-primary">{{ $plan->name }}</span>
                            @if($sub?->is_trial)
                                <span class="badge bg-warning text-dark">Trial</span>
                            @endif
                            <div class="text-muted small">
                                @if(!is_null($plan->price) && $plan->price > 0)
                                    ${{ number_format($plan->price, 0, ',', '.') }} / mes
                                @else
                                    Gratis
                                @endif
                            </div>
                        @else
                            <span class="badge bg-secondary">Sin plan</span>
                        @endif
                    </td>
                    <td class="small">
                        @if($sub)
                            <div>
                                <span class="badge bg-{{ $sub->status === 'active' ? 'success' : ($sub->status === 'cancelled' ? 'secondary' : 'warning') }}">
                                    {{ ucfirst($sub->status) }}
                                </span>
                            </div>
                            <div class="text-muted">Inicio: {{ \Illuminate\Support\Carbon::parse($sub->start_date)->format('Y-m-d') }}</div>
                            @if($sub->end_date)
                                <div class="text-muted">Fin: {{ \Illuminate\Support\Carbon::parse($sub->end_date)->format('Y-m-d') }}</div>
                            @endif
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($company->is_active)
                            <span class="badge bg-success">Activa</span>
                        @else
                            <span class="badge bg-secondary">Inactiva</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="btn-group">
                            <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-sm btn-outline-primary">
                                <i class="ri-edit-2-line"></i> Editar
                            </a>

                            {{-- Cambiar plan (modal) --}}
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#changePlanModal-{{ $company->id }}">
                                <i class="ri-shuffle-line"></i> Cambiar plan
                            </button>

                            <form action="{{ route('admin.companies.destroy', $company) }}" method="POST" onsubmit="return confirm('¿Eliminar empresa? Esta acción no se puede deshacer.')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="ri-delete-bin-6-line"></i> Eliminar
                                </button>
                            </form>
                        </div>

                        {{-- Modal Cambiar plan --}}
                        <div class="modal fade" id="changePlanModal-{{ $company->id }}" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title">Cambiar plan – {{ $company->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <form method="POST" action="{{ route('admin.companies.change-plan', $company) }}">
                                @csrf
                                <div class="modal-body">
                                    <label class="form-label">Nuevo plan</label>
                                    <select name="plan_id" class="form-select" required>
                                        @foreach($plans as $p)
                                            <option value="{{ $p->id }}">
                                                {{ $p->name }}
                                                @if(!is_null($p->price) && $p->price > 0)
                                                    – ${{ number_format($p->price,0,',','.') }}/mes
                                                @else
                                                    – Gratis
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text mt-2">
                                        Se cerrará la suscripción activa y se abrirá una nueva desde hoy.
                                    </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                                  <button class="btn btn-primary">Cambiar</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No hay empresas registradas.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($companies->hasPages())
        <div class="card-footer">
            {{ $companies->links() }}
        </div>
    @endif
</div>
@endsection
