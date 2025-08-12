{{-- resources/views/admin/companies/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Editar empresa')

@section('content')
<form action="{{ route('admin.companies.update', $company) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-sm-0">Editar empresa</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.companies.index') }}" class="btn btn-soft-secondary btn-sm">
                <i class="ri-arrow-go-back-line"></i> Volver
            </a>
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="ri-check-line me-1"></i> Actualizar
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success"><i class="ri-check-line me-1"></i>{{ session('success') }}</div>
    @endif

    @include('admin.companies._form', ['company' => $company, 'plans' => $plans ?? []])
</form>
@endsection
