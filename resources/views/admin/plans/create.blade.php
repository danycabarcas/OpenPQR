{{-- resources/views/admin/plans/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Nuevo plan')

@section('page-header')
    <div class="d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Nuevo plan</h4>
        <a href="{{ route('admin.plans.index') }}" class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line"></i> Volver
        </a>
    </div>
@endsection

@section('content')
    @include('admin.plans._form', [
        'action' => route('admin.plans.store'),
        'method' => 'POST',
        'plan' => $plan,  {{-- $plan = new \App\Models\Plan() desde el controller --}}
        'supportOptions' => $supportOptions ?? ['tickets_email' => 'Tickets + Email', 'whatsapp' => 'WhatsApp'],
        'submitLabel' => 'Guardar'
    ])
@endsection
