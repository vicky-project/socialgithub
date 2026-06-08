<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'GitHub Repos')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    body {
      background-color: #f6f8fa;
    }
    .github-container {
      max-width: 960px;
      margin: 0 auto;
    }
    .repo-list .list-group-item {
      background-color: transparent;
      border-bottom: 1px solid #d0d7de;
    }
    .repo-name a {
      color: #0969da;
      font-size: 1.25rem;
      font-weight: 600;
    }
    .repo-name a:hover {
      text-decoration: underline;
    }
    .repo-visibility-badge {
      font-size: 0.7rem;
      font-weight: 500;
      padding: 0.1em 0.5em;
      border-radius: 2em;
      border: 1px solid #d0d7de;
      color: #57606a;
      background-color: #f6f8fa;
      vertical-align: middle;
      margin-left: 0.3rem;
    }
    .repo-description {
      color: #57606a;
      font-size: 0.9rem;
      margin-bottom: 0.3rem;
    }
    .repo-meta {
      font-size: 0.8rem;
      color: #57606a;
    }
    .language-dot {
      display: inline-block;
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background-color: #ccc;
      margin-right: 0.2rem;
      vertical-align: middle;
    }
  </style>
</head>
<body>
  <div class="container py-4 github-container">
    <div class="d-flex align-items-center mb-4">
      <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm me-3">
        <i class="bi bi-arrow-left"></i> Kembali
      </a>
      <h2 class="mb-0 fw-bold">@yield('title', 'GitHub Repos')</h2>
    </div>

    @yield('content')
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>