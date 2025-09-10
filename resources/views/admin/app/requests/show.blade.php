@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Radicado: {{ $ticket->tracking_code }}</h1>
    <a href="{{ route('app.requests.index') }}" class="btn btn-sm btn-secondary">Volver</a>
  </div>

  <div class="row g-3">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-body">
          <h5 class="mb-1">{{ $ticket->subject }}</h5>
          <div class="text-muted small mb-2">
            Creada: {{ optional($ticket->created_at)->format('Y-m-d H:i') }} ·
            Estado: <span class="badge bg-secondary">{{ $ticket->status }}</span> ·
            SLA: <span class="badge bg-{{ $slaBadge }}">{{ $slaText }}</span>
          </div>
          <hr>
          <p class="mb-0" style="white-space: pre-line">{{ $ticket->description }}</p>
        </div>
      </div>

      <div class="card mt-3">
        <div class="card-header">Datos del ciudadano</div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-4"><strong>Nombre:</strong> {{ $ticket->citizen_name }} {{ $ticket->citizen_lastname }}</div>
            <div class="col-md-4"><strong>Documento:</strong> {{ $ticket->citizen_type_document }} {{ $ticket->citizen_document }}</div>
            <div class="col-md-4"><strong>Teléfono:</strong> {{ $ticket->citizen_phone ?? '—' }}</div>
            <div class="col-md-6 mt-2"><strong>Correo:</strong> {{ $ticket->citizen_email ?? '—' }}</div>
            <div class="col-md-6 mt-2"><strong>Dirección:</strong> {{ $ticket->citizen_address ?? '—' }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card">
        <div class="card-header">Gestión</div>
        <div class="card-body">
          <form class="mb-3" method="POST" action="{{ route('app.requests.assign', $ticket->id) }}">
            @csrf @method('PATCH')
            <label class="form-label">Dependencia</label>
            <div class="input-group">
              <select name="department_id" class="form-select">
                @foreach($departments as $d)
                  <option value="{{ $d->id }}" @selected($ticket->department_id==$d->id)>{{ $d->name }}</option>
                @endforeach
              </select>
              <button class="btn btn-outline-primary">Asignar</button>
            </div>
          </form>

          <form method="POST" action="{{ route('app.requests.updateStatus', $ticket->id) }}">
            @csrf @method('PATCH')
            <label class="form-label">Estado</label>
            <div class="input-group">
              <select name="status" class="form-select">
                @foreach(['nueva','en_proceso','respondida','cerrada','rechazada'] as $st)
                  <option value="{{ $st }}" @selected($ticket->status==$st)>{{ $st }}</option>
                @endforeach
              </select>
              <button class="btn btn-outline-success">Actualizar</button>
            </div>
          </form>

          <hr>
          <div>
            <div><strong>Tipo:</strong> {{ $ticket->type }}</div>
            <div><strong>Vía:</strong> {{ $ticket->created_via }}</div>
            <div><strong>Vence:</strong> {{ $ticket->response_due_date? \Carbon\Carbon::parse($ticket->response_due_date)->format('Y-m-d') : '—' }}</div>
            <div><strong>Anónima:</strong> {{ $ticket->is_anonymous ? 'Sí' : 'No' }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
