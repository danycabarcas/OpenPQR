@extends('admin.layouts.app')

@section('title', 'Editar plan')

@section('page-header')
    <div class="d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Editar plan</h4>
        <a href="{{ route('admin.plans.index') }}" class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line"></i> Volver
        </a>
    </div>
@endsection

@section('content')
    @include('admin.plans._form', [
        'action' => route('admin.plans.update', $plan),
        'method' => 'PUT',
        'plan' => $plan,
        'supportOptions' => $supportOptions ?? ['tickets_email'=>'Tickets + Email','whatsapp'=>'WhatsApp'],
        'submitLabel' => 'Actualizar'
    ])
@endsection
