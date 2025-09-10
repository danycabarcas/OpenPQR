<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Crear cuenta | OpenPQR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-7 col-xl-6">
      <div class="card shadow-sm">
        <div class="card-body p-4 p-md-5">
          <h4 class="mb-3">Crea tu cuenta</h4>
          <p class="text-muted">30 días de prueba en <strong>Essential</strong>. Sin tarjeta.</p>

          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('signup.store') }}">
            @csrf

            <div class="mb-3">
              <label class="form-label">Nombre de la empresa *</label>
              <input name="company_name" class="form-control" value="{{ old('company_name') }}" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Sector</label>
              <input name="sector" class="form-control" value="{{ old('sector') }}" placeholder="Pública, Salud, Servicios...">
            </div>

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Email (será tu usuario) *</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Teléfono</label>
                <input name="phone" class="form-control" value="{{ old('phone') }}">
              </div>
            </div>

            <div class="row g-3 mt-0">
              <div class="col-md-6">
                <label class="form-label">Contraseña *</label>
                <input type="password" name="password" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Confirmar contraseña *</label>
                <input type="password" name="password_confirmation" class="form-control" required>
              </div>
            </div>

            <div class="form-check mt-3">
              <input class="form-check-input" type="checkbox" value="1" id="terms" name="terms" {{ old('terms') ? 'checked' : '' }}>
              <label class="form-check-label" for="terms">
                Acepto los términos y el tratamiento de datos personales.
              </label>
            </div>

            {{-- TODO: integrar reCAPTCHA si lo deseas --}}

            <div class="d-grid mt-4">
              <button class="btn btn-primary">Crear cuenta</button>
            </div>
          </form>

          <div class="text-center mt-3">
            <a href="{{ route('login') }}">¿Ya tienes cuenta? Inicia sesión</a>
          </div>
        </div>
      </div>
      <p class="text-center text-muted small mt-3">OpenPQR © {{ date('Y') }}</p>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
