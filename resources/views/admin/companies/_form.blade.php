{{-- resources/views/admin/companies/_form.blade.php --}}
@php
    /** @var \App\Models\Company|null $company */
    $isEdit = isset($company) && $company?->id;
    $selectedPlanId = old('plan_id', $activePlanId ?? null);
@endphp

<div class="row g-4">
    {{-- Columna izquierda --}}
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">{{ $isEdit ? 'Datos de la empresa' : 'Nueva empresa' }}</h4>
            </div>
            <div class="card-body">
                {{-- Errores de validación --}}
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
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="name"
                               value="{{ old('name', $company->name ?? '') }}"
                               placeholder="Ej: Alcaldía del Magdalena" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Slug (URL)</label>
                        <input type="text" class="form-control" name="slug" id="slug"
                               value="{{ old('slug', $company->slug ?? '') }}"
                               placeholder="Se genera a partir del nombre">
                        <div class="form-text">Ej: <code>openpqr.com.co/tuempresa</code></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Sector</label>
                        @php
                            $sectores = [
                                'publica'   => 'Entidad pública',
                                'salud'     => 'Salud',
                                'retail'    => 'Retail',
                                'servicios' => 'Servicios',
                                'educacion' => 'Educación',
                                'tecnologia'=> 'Tecnología',
                                'otros'     => 'Otros',
                            ];
                            $sectorVal = old('sector', $company->sector ?? '');
                        @endphp
                        <select class="form-select" name="sector">
                            <option value="">Seleccione</option>
                            @foreach($sectores as $k => $v)
                                <option value="{{ $k }}" @selected($sectorVal===$k)>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Color primario</label>
                        <div class="d-flex gap-2">
                            <input type="color" class="form-control form-control-color" style="width:3rem"
                                   value="{{ old('color_primary', $company->color_primary ?? '#1e88e5') }}"
                                   id="color_picker">
                            <input type="text" class="form-control" name="color_primary" id="color_primary"
                                   value="{{ old('color_primary', $company->color_primary ?? '#1e88e5') }}"
                                   placeholder="#1e88e5">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Correo de contacto</label>
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

        {{-- Aviso de plan por defecto en CREATE --}}
        @unless($isEdit)
            <div class="alert alert-info">
                La empresa iniciará con el plan <b>Startup</b> (puedes elegir otro aquí abajo).
            </div>
        @endunless

        {{-- Selección de plan (en create y edit) --}}
        @isset($plans)
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Plan</h5>
            </div>
            <div class="card-body">
                <select name="plan_id" class="form-select">
                    @foreach($plans as $p)
                        <option value="{{ $p->id }}" {{ (string)$selectedPlanId === (string)$p->id ? 'selected' : '' }}>
                            {{ $p->name }} —
                            @if(is_null($p->price) || $p->price == 0)
                                Gratis
                            @else
                                ${{ number_format($p->price,0,',','.') }}/mes
                            @endif
                        </option>
                    @endforeach
                </select>
                @if($isEdit)
                    <div class="form-text mt-2">
                        Al guardar, si cambias el plan, se cerrará la suscripción activa y se abrirá una nueva.
                    </div>
                @endif
            </div>
        </div>
        @endisset
    </div>

    {{-- Columna derecha: archivos --}}
    <div class="col-xl-4">
        {{-- Logo --}}
        <div class="card mb-4">
            <div class="card-header"><h5 class="card-title mb-0">Logo</h5></div>
            <div class="card-body">
                @if($isEdit && !empty($company?->logo_url))
                    <div class="mb-2">
                        <img src="{{ asset(str_starts_with($company->logo_url,'storage/') ? $company->logo_url : ('storage/'.$company->logo_url)) }}"
                             class="img-fluid rounded border" alt="Logo" style="max-height:80px">
                    </div>
                @endif
                <input type="file" class="form-control" name="logo" accept="image/*">
                <div class="form-text">PNG/JPG. Recomendado ~512x512. Máx 2MB.</div>
            </div>
        </div>

        {{-- Banner --}}
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Banner</h5></div>
            <div class="card-body">
                @if($isEdit && !empty($company?->banner_url))
                    <div class="mb-2">
                        <img src="{{ asset(str_starts_with($company->banner_url,'storage/') ? $company->banner_url : ('storage/'.$company->banner_url)) }}"
                             class="img-fluid rounded border" alt="Banner" style="max-height:140px">
                    </div>
                @endif
                <input type="file" class="form-control" name="banner" accept="image/*">
                <div class="form-text">PNG/JPG. Recomendado ~1200x300. Máx 4MB.</div>
            </div>
        </div>
    </div>
</div>

{{-- Botonera pie --}}
<div class="mt-4 d-flex gap-2">
    <a href="{{ route('admin.companies.index') }}" class="btn btn-soft-secondary">Cancelar</a>
    <button class="btn btn-primary">
        <i class="ri-save-3-line me-1"></i> {{ $isEdit ? 'Actualizar' : 'Crear empresa' }}
    </button>
</div>

@push('scripts')
<script>
    // Autogenerar slug desde nombre si el campo slug está vacío
    const elName  = document.getElementById('name');
    const elSlug  = document.getElementById('slug');
    const elPick  = document.getElementById('color_picker');
    const elColor = document.getElementById('color_primary');

    if (elName && elSlug) {
        elName.addEventListener('input', () => {
            if (elSlug.value.trim() !== '') return;
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
