@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h1 class="h4 mb-3">PQRSD</h1>

  <form class="card card-body mb-3">
    <div class="row g-2 align-items-end">
      <div class="col-md-3">
        <label class="form-label">Buscar</label>
        <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="radicado, asunto, ciudadano...">
      </div>
      <div class="col-md-2">
        <label class="form-label">Estado</label>
        <select name="status" class="form-select">
          <option value="">Todos</option>
          @foreach(['nueva','en_proceso','respondida','cerrada','rechazada'] as $st)
            <option value="{{ $st }}" @selected($status===$st)>{{ $st }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Dependencia</label>
        <select name="department_id" class="form-select">
          <option value="">Todas</option>
          @foreach($departments as $d)
            <option value="{{ $d->id }}" @selected($dept==$d->id)>{{ $d->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Creación (YYYY-MM)</label>
        <input type="month" name="created" value="{{ $created }}" class="form-control">
      </div>
      <div class="col-md-2">
        <button class="btn btn-primary w-100">Filtrar</button>
      </div>
    </div>
  </form>

  <div class="d-flex gap-2 mb-3">
    <span class="badge bg-warning text-dark">Vence hoy: {{ $venceHoy }}</span>
    <span class="badge bg-danger">Vencidas: {{ $vencidas }}</span>
  </div>

  <div class="card">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th>Radicado</th>
            <th>Asunto</th>
            <th>Dependencia</th>
            <th>Estado</th>
            <th>Vence</th>
            <th>Creada</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach($requests as $r)
            @php
              $due = $r->response_due_date ? \Carbon\Carbon::parse($r->response_due_date)->startOfDay() : null;
              $today = \Carbon\Carbon::today();
              $badge = 'secondary';
              $dueTxt = $due? $due->format('Y-m-d') : '—';

              if(!in_array($r->status,['respondida','cerrada','rechazada']) && $due){
                if($due->lessThan($today)) $badge = 'danger';
                elseif($due->equalTo($today)) $badge = 'warning';
                else $badge = 'success';
              }
            @endphp
            <tr>
              <td class="fw-semibold">{{ $r->tracking_code }}</td>
              <td>{{ Str::limit($r->subject, 70) }}</td>
              <td>{{ optional($r->department)->name }}</td>
              <td><span class="badge bg-secondary">{{ $r->status }}</span></td>
              <td><span class="badge bg-{{ $badge }}">{{ $dueTxt }}</span></td>
              <td>{{ optional($r->created_at)->format('Y-m-d H:i') }}</td>
              <td><a class="btn btn-sm btn-outline-primary" href="{{ route('app.requests.show',$r->id) }}">Ver</a></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="card-body">
      {{ $requests->links() }}
    </div>
  </div>
</div>
@endsection
