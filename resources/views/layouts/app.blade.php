<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'GitHub Repos')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
  <div class="container py-4">
    <div class="d-flex align-items-center mb-4">
      <a href="{{ url()->previous() }}" class="btn btn-outline-secondary me-3">
        <i class="bi bi-arrow-left"></i> Kembali
      </a>
      <h2 class="mb-0">@yield('title', 'GitHub Repos')</h2>
    </div>

    @yield('content')
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>