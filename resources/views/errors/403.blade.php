<!doctype html>
<html lang="es" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FARM SJB | Acceso restringido</title>
  <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
  <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
</head>
<body>

<main class="error-page d-flex align-items-center justify-content-center p-4">
  <div class="error-card text-center">
    <div class="brand-icon rounded-circle bg-primary d-inline-flex align-items-center justify-content-center mb-4">
      <i class="bi bi-plus-lg text-white fs-5 fw-bold"></i>
    </div>
    <div class="error-code fw-bold text-primary">403</div>
    <h1 class="h4 fw-bold text-dark mb-2">Acceso restringido</h1>
    <p class="text-secondary mb-4">Tu rol no tiene permiso para entrar a esta sección.</p>
    <a href="{{ url('/') }}" class="btn btn-primary text-white px-4 rounded-3">
      <i class="bi bi-house-door me-2"></i>Volver al inicio
    </a>
  </div>
</main>

</body>
</html>
