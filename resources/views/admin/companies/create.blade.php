{{-- resources/views/admin/companies/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Crear empresa')

@section('content')
<form action="{{ route('admin.companies.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
    @csrf

    <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-sm-0">Nueva empresa</h4>
        <div>
            <a href="{{ route('admin.companies.index') }}" class="btn btn-soft-secondary btn-sm">
                <i class="ri-arrow-go-back-line"></i> Volver
            </a>
        </div>
    </div>

    @include('admin.companies._form', ['company' => null])
</form>
@endsection
