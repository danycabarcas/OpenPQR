@extends('layouts.velzon_app')
@section('title','Dashboard Empresa')
@section('page-title','Panel de tu Empresa')

@section('content')
<div class="row g-3">

  {{-- KPIs --}}
  @php
    $cards = [
      ['label'=>'Total','value'=>$total],
      ['label'=>'Nuevas','value'=>$nuevas],
      ['label'=>'En proceso','value'=>$enProceso],
      ['label'=>'Respondidas','value'=>$respondidas],
      ['label'=>'Cerradas','value'=>$cerradas],
      ['label'=>'Vence hoy','value'=>$venceHoy,'class'=>'text-warning'],
    ];
  @endphp
  @foreach($cards as $c)
    <div class="col-6 col-md-4 col-lg-2">
      <div class="card">
        <div class="card-body text-center">
          <div class="text-muted">{{ $c['label'] }}</div>
          <h3 class="mb-0 {{ $c['class'] ?? '' }}">{{ $c['value'] }}</h3>
        </div>
      </div>
    </div>
  @endforeach

  <div class="col-12">
    <div class="alert alert-danger"><strong>Vencidas:</strong> {{ $vencidas }}</div>
  </div>

  {{-- Últimas 10 --}}
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Últimas 10 PQRSD</h5>
        <a href="{{ route('app.requests.index') }}" class="btn btn-sm btn-primary">Ver todas</a>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>Radicado</th>
                <th>Asunto</th>
                <th>Estado</th>
                <th>Vence</th>
                <th>Creada</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @forelse($recent as $r)
                <tr>
                  <td class="fw-semibold">{{ $r->tracking_code }}</td>
                  <td>{{ \Illuminate\Support\Str::limit($r->subject, 60) }}</td>
                  <td><span class="badge bg-secondary">{{ $r->status }}</span></td>
                  <td>{{ $r->response_due_date? \Carbon\Carbon::parse($r->response_due_date)->format('Y-m-d') : '—' }}</td>
                  <td>{{ optional($r->created_at)->format('Y-m-d H:i') }}</td>
                  <td><a class="btn btn-sm btn-soft-primary" href="{{ route('app.requests.show',$r->id) }}">Ver</a></td>
                </tr>
              @empty
                <tr><td colspan="6" class="text-center p-4">Sin registros</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
