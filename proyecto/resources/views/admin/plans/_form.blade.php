{{-- resources/views/admin/plans/_form.blade.php --}}
<form method="POST" action="{{ $action }}">
    @csrf
    @if(strtoupper($method ?? 'POST') !== 'POST')
        @method($method)
    @endif

    <div class="row">
        <div class="col-lg-7">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">Información básica</h5>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del plan</label>
                        <input type="text" name="name" id="name" class="form-control"
                               value="{{ old('name', $plan->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" name="slug" id="slug" class="form-control"
                               value="{{ old('slug', $plan->slug) }}"
                               placeholder="starter, essential, pro, enterprise">
                        <div class="form-text">Si lo dejas vacío, se generará desde el nombre.</div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea name="description" id="description" rows="3" class="form-control"
                                  placeholder="Texto corto del plan...">{{ old('description', $plan->description) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Precio mensual (COP)</label>
                        <input type="number" name="price" id="price" class="form-control"
                               min="0" step="1"
                               value="{{ old('price', $plan->price) }}">
                        <div class="form-text">0 = Gratis o “A la medida”.</div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">Límites</h5>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="max_requests_per_month" class="form-label">PQRSD/mes</label>
                            <input type="number" name="max_requests_per_month" id="max_requests_per_month"
                                   class="form-control" min="0" step="1"
                                   value="{{ old('max_requests_per_month', $plan->max_requests_per_month) }}">
                            <div class="form-text">0 = Ilimitado</div>
                        </div>
                        <div class="col-md-4">
                            <label for="max_departments" class="form-label">Dependencias</label>
                            <input type="number" name="max_departments" id="max_departments"
                                   class="form-control" min="0" step="1"
                                   value="{{ old('max_departments', $plan->max_departments) }}">
                            <div class="form-text">0 = Ilimitado</div>
                        </div>
                        <div class="col-md-4">
                            <label for="max_users" class="form-label">Agentes/usuarios</label>
                            <input type="number" name="max_users" id="max_users"
                                   class="form-control" min="0" step="1"
                                   value="{{ old('max_users', $plan->max_users) }}">
                            <div class="form-text">0 = Ilimitado</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">Branding y funcionalidades</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="custom_colors" name="custom_colors"
                                       {{ old('custom_colors', $plan->custom_colors) ? 'checked' : '' }}>
                                <label class="form-check-label" for="custom_colors">Colores personalizados (logo + color)</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="custom_banner" name="custom_banner"
                                       {{ old('custom_banner', $plan->custom_banner) ? 'checked' : '' }}>
                                <label class="form-check-label" for="custom_banner">Banner personalizado</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="enable_sign" name="enable_sign"
                                       {{ old('enable_sign', $plan->enable_sign) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_sign">Firma electrónica en PDFs</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="enable_qr" name="enable_qr"
                                       {{ old('enable_qr', $plan->enable_qr) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_qr">QR para seguimiento</label>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <div class="col-lg-5">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">Operación</h5>

                    <div class="mb-3">
                        <label for="support_channel" class="form-label">Soporte</label>
                        <select name="support_channel" id="support_channel" class="form-select">
                            @foreach(($supportOptions ?? ['tickets_email'=>'Tickets + Email','whatsapp'=>'WhatsApp']) as $k => $label)
                                <option value="{{ $k }}" @selected(old('support_channel', $plan->support_channel) === $k)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="storage_gb" class="form-label">Almacenamiento (GB)</label>
                        <input type="number" name="storage_gb" id="storage_gb" class="form-control"
                               min="0" step="1"
                               value="{{ old('storage_gb', $plan->storage_gb) }}">
                        <div class="form-text">Deja 0 si no aplica o es “a medida”.</div>
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="allow_custom_domain" name="allow_custom_domain"
                               {{ old('allow_custom_domain', $plan->allow_custom_domain) ? 'checked' : '' }}>
                        <label class="form-check-label" for="allow_custom_domain">Permitir dominio propio (CNAME)</label>
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                               {{ old('is_active', $plan->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Plan activo</label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.plans.index') }}" class="btn btn-light">Cancelar</a>
                <button class="btn btn-primary">{{ $submitLabel ?? 'Guardar' }}</button>
            </div>
        </div>
    </div>
</form>
