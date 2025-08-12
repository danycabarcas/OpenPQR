{{-- resources/views/admin/companies/_form.blade.php --}}
@php
    /** @var \App\Models\Company|null $company */
    $isEdit = isset($company) && $company?->id;
@endphp

<div class="row g-4">
    {{-- Columna izquierda --}}
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Información básica</h4>
            </div>
            <div class="card-body">
                {{-- Errores --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <div class="fw-semibold mb-2">Revisa los campos:</div>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre de la empresa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="name"
                               value="{{ old('name', $company->name ?? '') }}"
                               placeholder="Ej: Alcaldía de Magdalena" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Slug (URL)</label>
                        <input type="text" class="form-control" name="slug" id="slug"
                               value="{{ old('slug', $company->slug ?? '') }}"
                               placeholder="Se genera a partir del nombre">
                        <div class="form-text">Ejemplo: <code>openpqr.com.co/tuempresa</code></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Sector</label>
                        <select class="form-select" name="sector">
                            @php
                                $sectores = ['publica' => 'Entidad pública','salud'=>'Salud',
                                             'retail'=>'Retail','servicios'=>'Servicios',
                                             'educacion'=>'Educación','tecnologia'=>'Tecnología','otros'=>'Otros'];
                                $sectorVal = old('sector', $company->sector ?? '');
                            @endphp
                            <option value="">Seleccione</option>
                            @foreach($sectores as $k => $v)
                                <option value="{{ $k }}" @selected($sectorVal===$k)>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Color primario</label>
                        <div class="d-flex gap-2">
                            <input type="color" class="form-control form-control-color" style="width: 3rem;"
                                   value="{{ old('color_primary', $company->color_primary ?? '#1e88e5') }}"
                                   id="color_picker">
                            <input type="text" class="form-control" name="color_primary" id="color_primary"
                                   value="{{ old('color_primary', $company->color_primary ?? '#1e88e5') }}"
                                   placeholder="#1e88e5">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email de contacto</label>
                        <input type="email" class="form-control" name="email_contact"
                               value="{{ old('email_contact', $company->email_contact ?? '') }}"
                               placeholder="contacto@empresa.com">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Teléfono de contacto</label>
                        <input type="text" class="form-control" name="phone_contact"
                               value="{{ old('phone_contact', $company->phone_contact ?? '') }}"
                               placeholder="3001234567">
                    </div>

                    <div class="col-md-12">
                        <div class="form-check form-switch form-switch-md">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_active"
                                   name="is_active" value="1"
                                   @checked(old('is_active', $company->is_active ?? true))>
                            <label class="form-check-label" for="is_active">Empresa activa</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- En create muestra aviso de plan default --}}
        @unless($isEdit)
            <div class="alert alert-info">
                Al crear la empresa quedará con el plan <b>Startup</b> por defecto. Podrás cambiarlo luego.
            </div>
        @endunless
    </div>

    {{-- Columna derecha --}}
    <div class="col-xl-4">
        {{-- Logo --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Logo</h5>
            </div>
            <div class="card-body">
                @if(!empty($company?->logo_url))
                    <div class="mb-3">
                        <img src="{{ $company->logo_url }}" alt="Logo actual"
                             class="img-fluid rounded border">
                    </div>
                @endif
                <input type="file" class="form-control" name="logo" accept="image/*">
                <div class="form-text">PNG/JPG, recomendado ~512x512.</div>
            </div>
        </div>

        {{-- Banner --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Banner</h5>
            </div>
            <div class="card-body">
                @if(!empty($company?->banner_url))
                    <div class="mb-3">
                        <img src="{{ $company->banner_url }}" alt="Banner actual"
                             class="img-fluid rounded border">
                    </div>
                @endif
                <input type="file" class="form-control" name="banner" accept="image/*">
                <div class="form-text">PNG/JPG, recomendado ~1200x300.</div>
            </div>
        </div>

        {{-- En EDIT: bloque para cambio de plan (si el controlador ya pasa $plans y $activePlanId) --}}
        @isset($plans)
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Plan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.companies.updatePlan', $company) }}" method="post" class="row g-2">
                    @csrf @method('PATCH')
                    <div class="col-12">
                        <select name="plan_id" class="form-select">
                            @foreach($plans as $p)
                                <option value="{{ $p->id }}" @selected(($activePlanId ?? null)===$p->id)>
                                    {{ $p->name }} — ${{ number_format($p->price,0,',','.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-outline-primary"><i class="ri-refresh-line"></i> Cambiar plan</button>
                    </div>
                </form>
            </div>
        </div>
        @endisset
    </div>
</div>

{{-- Botones pie --}}
<div class="mt-4 d-flex gap-2">
    <a href="{{ route('admin.companies.index') }}" class="btn btn-soft-secondary">Cancelar</a>
    <button class="btn btn-primary">
        <i class="ri-save-3-line"></i> {{ $isEdit ? 'Actualizar' : 'Crear empresa' }}
    </button>
</div>

{{-- Helpers UI --}}
@push('scripts')
<script>
    // sin dependencia externa: slug desde name
    const elName  = document.getElementById('name');
    const elSlug  = document.getElementById('slug');
    const elPick  = document.getElementById('color_picker');
    const elColor = document.getElementById('color_primary');

    if (elName && elSlug && !elSlug.value) {
        elName.addEventListener('input', () => {
            const s = elName.value
                .toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g,'') // quita acentos
                .replace(/[^a-z0-9]+/g,'-')                      // no alfanum => guión
                .replace(/^-+|-+$/g,'');                         // recorta guiones
            elSlug.value = s;
        });
    }
    if (elPick && elColor) {
        elPick.addEventListener('input', () => elColor.value = elPick.value);
        elColor.addEventListener('input', () => elPick.value = elColor.value);
    }
</script>
@endpush
