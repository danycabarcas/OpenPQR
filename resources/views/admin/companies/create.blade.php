{{-- resources/views/admin/companies/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Crear empresa')

@section('content')
<div class="container-fluid">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">Crear empresa</h4>
        <div>
            <a href="{{ route('admin.companies.index') }}" class="btn btn-soft-secondary btn-sm">
                <i class="ri-arrow-go-back-line"></i> Volver
            </a>
        </div>
    </div>

    <form action="{{ route('admin.companies.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
        @csrf
        @include('admin.companies._form', [
            'company'      => $company,
            'plans'        => $plans ?? [],
            'activePlanId' => $activePlanId ?? null,
        ])
    </form>
</div>
@endsection
