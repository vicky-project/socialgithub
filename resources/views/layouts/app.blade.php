<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'GitHub')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    body {
      background-color: #ffffff;
      color: #1F2328;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    .github-container {
      max-width: 960px;
      margin: 0 auto;
    }
    .github-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1.5rem;
      padding-bottom: 0.5rem;
      border-bottom: 1px solid #d0d7de;
    }
    .github-header .back-link {
      color: #656d76;
      text-decoration: none;
      font-size: 0.9rem;
      font-weight: 500;
    }
    .github-header .back-link:hover {
      color: #0969da;
      text-decoration: none;
    }
    .github-header .page-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: #1F2328;
      margin: 0;
    }
    .repo-list .list-group-item {
      background-color: transparent;
      border: none;
      border-bottom: 1px solid #d0d7de;
      padding: 1rem 0;
    }
    .repo-list .list-group-item:last-child {
      border-bottom: none;
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
    /* Tombol GitHub kecil di kanan */
    .repo-github-link {
      border: 1px solid #d0d7de;
      background-color: #f6f8fa;
      color: #24292f;
      padding: 0.2rem 0.5rem;
      font-size: 0.85rem;
    }
    .repo-github-link:hover {
      background-color: #eaeef2;
      color: #000;
    }
    .pagination {
      --bs-pagination-color: #0969da;
      --bs-pagination-hover-color: #0969da;
      --bs-pagination-active-bg: #0969da;
      --bs-pagination-active-border-color: #0969da;
      font-size: 0.85rem;
    }
  </style>
</head>
<body>
  <div class="container github-container py-4">
    @yield('content')
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>