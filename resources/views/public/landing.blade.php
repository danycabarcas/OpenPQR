<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>{{ $company->public_name ?? $company->name }} | PQRSD</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  {{-- Bootstrap + Icons (CDN) --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  {{-- Paleta del theme por empresa (variables CSS) --}}
  @include('partials.theme-css', ['theme' => $company->theme ?? null])

  <style>
    /* Usa variables de theme (no sobreescribir :root) */
    .hero{
      background: linear-gradient(180deg, color-mix(in srgb, var(--brand) 8%, transparent), transparent 60%);
    }
    .navbar-brand img{ height:26px }
    @media (max-width: 991.98px){
      .navbar{ border-bottom:1px solid rgba(0,0,0,.05); }
      .client-badge{ display:block; }
    }
    @media (min-width: 992px){
      .client-badge{ display:none; }
    }
    .card{
      border:0; border-radius:16px; box-shadow:0 10px 30px rgba(0,0,0,.06);
      background: var(--card-bg);
    }
    .section-title{ font-weight:700 }
    .menu-link{ text-decoration:none; color:inherit }
    .form-hint{ font-size:.85rem; color:#6b7280 }
    footer{ border-top:1px solid rgba(0,0,0,.06) }



    .subnav-entity{ background: var(--bs-card-bg); }      /* se adapta al theme */
    .entity-logo{ height: 36px; width: auto; object-fit: contain; }

    @media (max-width: 991.98px){
    .entity-logo{ height: 28px; }
    }

    /* Mejoras para dark theme */
    body.theme-dark .subnav-entity{
    background: #0f172a;
    border-color: #1f2937 !important;
    }





  </style>
</head>
@php
  $theme = $companyTheme ?? null; // o $company->theme en la landing
@endphp

<body class="{{ ($theme && !empty($theme->is_dark) && $theme->is_dark) ? 'theme-dark' : '' }}">


  {{-- NAVBAR (claro por defecto; si tu theme es oscuro, cambia navbar-light -> navbar-dark) --}}
 <nav class="navbar navbar-expand-lg sticky-top {{ ($theme?->is_dark ?? false) ? 'navbar-dark' : 'navbar-light bg-body' }}">

    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="#top">
        <img src="{{ asset('assets/images/logo-sm.png') }}" alt="OpenPQR">
        <span class="fw-semibold"></span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Menú">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navMain">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="#info">La Empresa</a></li>
          <li class="nav-item"><a class="nav-link" href="#form">Radicar PQRSD</a></li>
          <li class="nav-item"><a class="nav-link" href="#track">Consultar</a></li>
          <li class="nav-item"><a class="nav-link" href="#map">Mapa</a></li>
        </ul>
      </div>
    </div>
  </nav>







  {{-- SUBNAV / BRAND STRIP DE LA ENTIDAD --}}
<div class="subnav-entity border-bottom">
  <div class="container d-flex align-items-center justify-content-between py-2">
    <div class="d-flex align-items-center gap-3">
      <img
        src="{{ $company->logo_url ?? asset('assets/images/logo-dark.png') }}"
        alt="{{ $company->public_name ?? $company->name }}"
        class="entity-logo">

    </div>

    {{-- Acciones rápidas (se ocultan en móvil) --}}
    <div class="d-none d-lg-flex gap-2">
      <a href="#form" class="btn btn-primary btn-sm"><i class="bi bi-send"></i> Radicar</a>
      <a href="#track" class="btn btn-outline-primary btn-sm"><i class="bi bi-search"></i> Consultar</a>
    </div>
  </div>
</div>






  {{-- “Chapa” con logo del cliente en móvil --}}
  <div class="client-badge bg-light py-2">
    <div class="container d-flex align-items-center gap-3">
      <img src="{{ $company->logo_url ?? asset('assets/images/logo-dark.png') }}" alt="Logo empresa" height="28">
      <div class="small">
        <strong>{{ $company->public_name ?? $company->name }}</strong><br>
        PQRSD y solicitudes en línea
      </div>
    </div>
  </div>

  {{-- HERO --}}
  <a id="top"></a>
  <section class="hero py-5">
    <div class="container">
      <div class="row g-4 align-items-center">
        <div class="col-lg-6">
          <h1 class="display-6 fw-bold mb-3">Portal de PQRSD</h1>
          <p class="lead text-secondary mb-4">
            Envía, consulta y da seguimiento a tus solicitudes con {{ $company->public_name ?? $company->name }}.
          </p>
          <div class="d-flex gap-2">
            <a href="#form" class="btn btn-primary btn-lg"><i class="bi bi-send"></i> Radicar PQRSD</a>
            <a href="#track" class="btn btn-outline-primary btn-lg"><i class="bi bi-search"></i> Consultar estado</a>
          </div>
        </div>
        <div class="col-lg-6">
          <img class="img-fluid" src="https://cdn.jsdelivr.net/gh/ux-io/assets/illustrations/paperwork.svg" alt="Ilustración">
        </div>
      </div>
    </div>
  </section>

  {{-- SECCIÓN INFO --}}
  <section id="info" class="py-5">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-5">
          <div class="card h-100">
            <div class="card-body">
              <h3 class="section-title mb-3">{{ $company->public_name ?? $company->name }}</h3>
              <p class="mb-2"><i class="bi bi-geo-alt"></i> {{ $company->address ?? 'Dirección no registrada' }}</p>
              <p class="mb-2"><i class="bi bi-telephone"></i> {{ $company->phone ?? '—' }}</p>
              <p class="mb-0"><i class="bi bi-envelope"></i> {{ $company->email ?? '—' }}</p>
            </div>
          </div>
        </div>
        <div class="col-lg-7">
          <div class="card h-100">
            <div class="card-body">
              <h4 class="mb-3">Sobre la empresa</h4>
              <p>{{ $company->public_description ?? 'Descripción pendiente.' }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- FORM RADICAR --}}
  <section id="form" class="py-5 bg-body-tertiary">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-7">
          <div class="card">
            <div class="card-body">
              <h4 class="section-title mb-3">Radicar PQRSD</h4>

              @if(session('ok'))
                <div class="alert alert-success">{{ session('ok') }}</div>
              @endif
              @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
              @endif

              <form action="{{ route('site.pqrsd.store',$slug) }}" method="post" enctype="multipart/form-data" novalidate>
                @csrf

                {{-- Honeypot anti-spam --}}
                <input type="text" name="website" class="d-none" tabindex="-1" autocomplete="off">

                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="form-label">Tipo</label>
                    <select name="type" class="form-select" required>
                      <option value="">Seleccione…</option>
                      <option value="P" @selected(old('type')==='P')>Petición</option>
                      <option value="Q" @selected(old('type')==='Q')>Queja</option>
                      <option value="R" @selected(old('type')==='R')>Reclamo</option>
                      <option value="S" @selected(old('type')==='S')>Sugerencia</option>
                      <option value="D" @selected(old('type')==='D')>Denuncia</option>
                    </select>
                  </div>
                  <div class="col-md-8">
                    <label class="form-label">Asunto</label>
                    <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" required maxlength="180">
                  </div>

                  <div class="col-12">
                    <label class="form-label">Descripción</label>
                    <textarea name="description" rows="5" class="form-control" required>{{ old('description') }}</textarea>
                    <div class="form-hint mt-1">Sé claro y específico. Puedes adjuntar archivos abajo.</div>
                  </div>

                  <div class="col-md-6">
                    <label class="form-label">Nombre completo</label>
                    <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label">Tipo doc.</label>
                    <input type="text" name="id_type" class="form-control" placeholder="CC/NIT/CE" value="{{ old('id_type') }}" required>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label">Documento</label>
                    <input type="text" name="id_number" class="form-control" value="{{ old('id_number') }}" required>
                  </div>

                  <div class="col-md-6">
                    <label class="form-label">Correo</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Celular</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                  </div>

                  <div class="col-12">
                    <label class="form-label">Adjuntos (opcional)</label>
                    <input type="file" name="attachments[]" class="form-control" multiple>
                    <div class="form-hint mt-1">Formatos comunes. Máx 4MB por archivo.</div>
                  </div>

                  <div class="col-12 form-check mt-2">
                    <input class="form-check-input" type="checkbox" id="accept" name="accept" required {{ old('accept') ? 'checked' : '' }}>
                    <label class="form-check-label" for="accept">
                      Acepto la política de tratamiento de datos personales.
                    </label>
                  </div>

                  <div class="col-12">
                    <button class="btn btn-primary btn-lg"><i class="bi bi-send"></i> Enviar</button>
                    @if(session('tracking'))
                      <span class="ms-3 text-success">Código: <strong>{{ session('tracking') }}</strong></span>
                    @endif
                  </div>
                </div>
              </form>

            </div>
          </div>
        </div>

        {{-- CONSULTA --}}
        <div class="col-lg-5" id="track">
          <div class="card">
            <div class="card-body">
              <h5 class="section-title mb-3">Consultar estado</h5>

              @if(session('track_result'))
                @php($r = session('track_result'))
                <div class="alert alert-info">
                  <div><strong>Código:</strong> {{ $r['tracking_code'] }}</div>
                  <div><strong>Estado:</strong> {{ $r['status'] }}</div>
                  <div><strong>Asunto:</strong> {{ $r['subject'] }}</div>
                  <div><strong>Creado:</strong> {{ $r['created_at'] }}</div>
                  <div><strong>Vence:</strong> {{ $r['due_date'] }}</div>
                </div>
              @endif

              <form action="{{ route('site.pqrsd.track',$slug) }}" method="post" novalidate>
                @csrf
                <div class="mb-3">
                  <label class="form-label">Código de radicado</label>
                  <input type="text" name="tracking_code" class="form-control" placeholder="Ej: ABC-250912-1A2B" value="{{ old('tracking_code') }}">
                </div>
                <div class="text-center text-muted my-2">— o —</div>
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label">Documento</label>
                    <input type="text" name="id_number" class="form-control" placeholder="CC/NIT" value="{{ old('id_number') }}">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Celular</label>
                    <input type="text" name="phone" class="form-control" placeholder="3xx xxx xxxx" value="{{ old('phone') }}">
                  </div>
                </div>
                <div class="mt-3">
                  <button class="btn btn-outline-primary w-100"><i class="bi bi-search"></i> Consultar</button>
                </div>
              </form>

            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  {{-- MAPA --}}
  <section id="map" class="py-5">
    <div class="container">
      <h4 class="section-title mb-3">Cómo llegar</h4>
      <div class="ratio ratio-16x9">
        @if(!empty($company->google_maps_embed))
          {!! $company->google_maps_embed !!}
        @else
          <iframe
            src="https://www.google.com/maps?q={{ urlencode($company->address ?? 'Colombia') }}&output=embed"
            style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        @endif
      </div>
    </div>
  </section>

  <footer class="py-4">
    <div class="container text-center small text-muted">
      <div>© <script>document.write(new Date().getFullYear())</script> {{ $company->public_name ?? $company->name }}</div>
      <div>Portal de PQRSD desarrollado con OpenPQR</div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
