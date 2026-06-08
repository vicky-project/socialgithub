@extends('socialgithub::layouts.app')

@section('title', $title ?? 'Repositories')

@section('content')
<div class="github-header">
  <a href="{{ url()->previous() }}" class="back-link">
    <i class="bi bi-arrow-left"></i> Kembali
  </a>
  <h1 class="page-title">Repositories</h1>
</div>

{{-- Form pencarian username --}}
<form action="{{ route('github.repos') }}" method="GET" class="mb-4">
  <div class="input-group">
    <span class="input-group-text"><i class="bi bi-github"></i></span>
    <input type="text" name="username" class="form-control"
    placeholder="Cari username GitHub..."
    value="{{ $username ?? '' }}">
    <button class="btn btn-outline-secondary" type="submit">Cari</button>
  </div>
  @if(isset($username) && !$isOwnProfile)
  <small class="text-muted">Menampilkan repositori dari <strong>{{ $username }}</strong></small>
  @endif
</form>

@if(isset($error))
<div class="alert alert-danger">
  {{ $error }}
</div>
@endif

@if(count($repos) > 0)
<div class="list-group list-group-flush repo-list">
  @foreach($repos as $repo)
  <div class="list-group-item">
    <div class="d-flex justify-content-between align-items-start">
      <div class="flex-grow-1">
        <div class="repo-name mb-1">
          <a href="{{ $repo['html_url'] }}" target="_blank" class="text-decoration-none">
            {{ $repo['name'] }}
          </a>
          <span class="repo-visibility-badge">
            {{ ucfirst($repo['visibility'] ?? 'public') }}
          </span>
          @if($repo['fork'])
          <small class="text-muted ms-1">(forked)</small>
          @endif
        </div>

        @if($repo['description'])
        <p class="repo-description">
          {{ $repo['description'] }}
        </p>
        @endif

        @if(!empty($repo['topics']))
        <div class="mb-1">
          @foreach($repo['topics'] as $topic)
          <span class="badge bg-light text-primary border me-1">{{ $topic }}</span>
          @endforeach
        </div>
        @endif

        <div class="repo-meta d-flex flex-wrap align-items-center">
          @if($repo['language'])
          @php
          $langColor = $languageColors[$repo['language']] ?? '#ccc';
          $langColor = is_array($langColor) ? $langColor['color'] : $langColor;
          @endphp
          <span class="me-3 d-inline-flex align-items-center">
            <span class="language-dot" style="background-color: {{ $langColor }};"></span>
            {{ $repo['language'] }}
          </span>
          @endif

          <span class="me-3">
            <i class="bi bi-star"></i> {{ number_format($repo['stargazers_count']) }}
          </span>
          <span class="me-3">
            <i class="bi bi-diagram-2"></i> {{ number_format($repo['forks_count']) }}
          </span>
          @if(isset($repo['license']) && $repo['license'])
          <span class="me-3">
            <i class="bi bi-shield-check"></i>
            {{ $repo['license']['spdx_id'] ?? $repo['license']['name'] }}
          </span>
          @endif
          <span>
            <i class="bi bi-clock"></i>
            Diperbarui {{ \Carbon\Carbon::parse($repo['updated_at'])->diffForHumans() }}
          </span>
        </div>
      </div>
      <div class="ms-3">
        <a href="{{ $repo['html_url'] }}" target="_blank"
          class="btn btn-sm repo-github-link" title="Lihat di GitHub">
          <i class="bi bi-github"></i>
        </a>
      </div>
    </div>
  </div>
  @endforeach
</div>
@elseif(!isset($error))
<p class="text-muted">
  Tidak ada repositori publik untuk pengguna ini.
</p>
@endif
@endsection