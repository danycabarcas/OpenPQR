{{-- Mantiene tus campos y agrega logo/banner + (opcional) plan --}}

<div class="row g-3">

  {{-- Nombre --}}
  <div class="col-md-6">
    <label class="form-label">Nombre de la empresa *</label>
    <input type="text" name="name" class="form-control"
           value="{{ old('name', $company->name ?? '') }}" required>
    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  {{-- Slug (editable si quieres) --}}
  <div class="col-md-6">
    <label class="form-label">Slug</label>
    <input type="text" name="slug" class="form-control"
           value="{{ old('slug', $company->slug ?? '') }}" placeholder="auto si se deja vacío">
    @error('slug') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  {{-- Sector --}}
  <div class="col-md-6">
    <label class="form-label">Sector</label>
    <select name="sector" class="form-select">
      @php
        $sector = old('sector', $company->sector ?? '');
        $sectores = ['publica' => 'Pública', 'salud' => 'Salud', 'productos' => 'Productos', 'servicios' => 'Servicios',
                     'educacion' => 'Educación', 'tecnologia' => 'Tecnología', 'ong' => 'ONG', 'otro' => 'Otro'];
      @endphp
      <option value="">Seleccione…</option>
      @foreach($sectores as $k => $v)
        <option value="{{ $k }}" @selected($sector === $k)>{{ $v }}</option>
      @endforeach
    </select>
    @error('sector') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  {{-- Email de contacto --}}
  <div class="col-md-6">
    <label class="form-label">Correo de contacto</label>
    <input type="email" name="email_contact" class="form-control"
           value="{{ old('email_contact', $company->email_contact ?? '') }}">
    @error('email_contact') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  {{-- Teléfono --}}
  <div class="col-md-6">
    <label class="form-label">Teléfono de contacto</label>
    <input type="text" name="phone_contact" class="form-control"
           value="{{ old('phone_contact', $company->phone_contact ?? '') }}">
    @error('phone_contact') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  {{-- Color primario (mantengo tu selector de color + el hex sincronizados) --}}
  <div class="col-md-6">
    <label class="form-label d-block">Color primario</label>
    @php
      $hex = old('color_primary', $company->color_primary ?? '#1e88e5');
      $hex = str_starts_with($hex, '#') ? $hex : "#{$hex}";
    @endphp
    <div class="d-flex gap-2 align-items-center">
      <input type="color" id="colorPicker" value="{{ $hex }}" style="width: 44px; height: 44px; padding: 0;">
      <input type="text" name="color_primary" id="colorHex" class="form-control"
             value="{{ $hex }}" placeholder="#RRGGBB" pattern="^#?[0-9A-Fa-f]{6}$">
    </div>
    <small class="text-muted">Formato: #RRGGBB (p. ej. #1e88e5)</small><br>
    @error('color_primary') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  {{-- Logo (nuevo) --}}
  <div class="col-md-6">
    <label class="form-label">Logo (horizontal)</label>
    <input type="file" name="logo" class="form-control" accept="image/*" id="logoInput">
    <small class="text-muted">Sugerido: 320×80 a 400×120px, PNG con fondo transparente.</small>
    @error('logo') <div><small class="text-danger">{{ $message }}</small></div> @enderror

    @if(!empty($company?->logo_url))
      <div class="mt-2 d-flex align-items-center gap-3">
        <img src="{{ $company->logo_url }}" alt="Logo actual" id="logoPreview"
             style="max-height:60px; max-width: 240px;">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="remove_logo" id="remove_logo" value="1">
          <label class="form-check-label" for="remove_logo">Eliminar logo actual</label>
        </div>
      </div>
    @else
      <img src="" id="logoPreview" class="mt-2 d-none" style="max-height:60px; max-width:240px;">
    @endif
  </div>

  {{-- Banner (nuevo) --}}
  <div class="col-12">
    <label class="form-label">Banner</label>
    <input type="file" name="banner" class="form-control" accept="image/*" id="bannerInput">
    <small class="text-muted">Sugerido: 1600×360 a 1920×400px, JPG/PNG liviano.</small>
    @error('banner') <div><small class="text-danger">{{ $message }}</small></div> @enderror

    @if(!empty($company?->banner_url))
      <div class="mt-2">
        <img src="{{ $company->banner_url }}" alt="Banner actual" id="bannerPreview"
             style="max-height:160px; width:auto;">
        <div class="form-check mt-2">
          <input class="form-check-input" type="checkbox" name="remove_banner" id="remove_banner" value="1">
          <label class="form-check-label" for="remove_banner">Eliminar banner actual</label>
        </div>
      </div>
    @else
      <img src="" id="bannerPreview" class="mt-2 d-none" style="max-height:160px; width:auto;">
    @endif
  </div>

  {{-- Activo --}}
  <div class="col-md-6">
    <label class="form-label d-block">Estado</label>
    <div class="form-check form-switch">
      @php $active = (bool) old('is_active', $company->is_active ?? true); @endphp
      <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
             @checked($active)>
      <label class="form-check-label" for="is_active">Empresa activa</label>
    </div>
    @error('is_active') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  {{-- (Opcional) Selección inmediata de plan si pasas $plans --}}
  @isset($plans)
    <div class="col-md-6">
      <label class="form-label">Plan</label>
      @php
        $selectedPlanId = old('plan_id', $activePlanId ?? ($company->activeSubscription->plan_id ?? ''));
      @endphp
      <select name="plan_id" class="form-select">
        <option value="">(dejar como está)</option>
        @foreach($plans as $p)
          <option value="{{ $p->id }}" @selected((string)$selectedPlanId === (string)$p->id)>
            {{ $p->name }}
          </option>
        @endforeach
      </select>
      <small class="text-muted">Si lo dejas vacío, no se modifica el plan actual.</small>
      @error('plan_id') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
  @endisset

</div>

{{-- Helpers JS: sincroniza color y previsualiza imágenes --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const picker = document.getElementById('colorPicker');
  const hex = document.getElementById('colorHex');

  if (picker && hex) {
    const norm = v => v?.startsWith('#') ? v : '#'+v;
    picker.addEventListener('input', () => { hex.value = picker.value; });
    hex.addEventListener('input', () => {
      const v = hex.value.trim();
      if (/^#?[0-9a-fA-F]{6}$/.test(v)) picker.value = norm(v);
    });
  }

  const previewFile = (input, imgEl) => {
    if (!input || !imgEl) return;
    input.addEventListener('change', () => {
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
          imgEl.src = e.target.result;
          imgEl.classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
      }
    });
  };

  previewFile(document.getElementById('logoInput'),   document.getElementById('logoPreview'));
  previewFile(document.getElementById('bannerInput'), document.getElementById('bannerPreview'));
});
</script>
@endpush
