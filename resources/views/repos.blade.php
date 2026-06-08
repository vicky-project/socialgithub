@extends('socialgithub::layouts.app')

@section('title', $title ?? 'Repositories')

@section('content')
<div class="github-header">
  <a href="{{ config('socialgithub.back_url') ?? url()->previous() }}" class="back-link">
    <i class="bi bi-arrow-left"></i> Kembali
  </a>
  <h1 class="page-title">Repositories</h1>
</div>

{{-- Form pencarian username --}}
<form action="{{ route('github.repos') }}" method="GET" class="mb-3" id="search-form">
  <div class="input-group">
    <span class="input-group-text"><i class="bi bi-github"></i></span>
    <input type="search" name="username" id="search-username" class="form-control"
    placeholder="Cari username GitHub..."
    value="{{ $username ?? '' }}">
    <button class="btn btn-outline-secondary" type="button" id="clear-search" style="display: none;">
      <i class="bi bi-x-lg"></i>
    </button>
    <button class="btn btn-outline-secondary" type="submit">Cari</button>
  </div>
  @if(isset($username) && !$isOwnProfile)
  <small class="text-muted">Menampilkan repositori dari <strong>{{ $username }}</strong></small>
  @endif
</form>

{{-- Info user (jika ada) --}}
@if(isset($userName))
<div class="d-flex align-items-center mb-3">
  @if($userAvatar)
  <a href="{{ $userHtmlUrl ?? 'https://github.com/' . $username }}" target="_blank" title="Lihat profil GitHub">
    <img src="{{ $userAvatar }}" alt="{{ $userName }}" class="rounded-circle me-2" width="32" height="32">
  </a>
  @endif
  <strong>{{ $userName }}</strong>
  <span class="badge bg-secondary ms-2">{{ number_format($totalPublicRepos) }} repositori publik</span>
</div>
@endif

@if(isset($error))
<div class="alert alert-danger">
  {{ $error }}
</div>
@endif

@if(isset($paginator) && count($paginator) > 0)
<div class="d-flex justify-content-between align-items-center mb-3">
  <small class="text-muted">
    Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}
    dari {{ number_format($paginator->total()) }} repositori
  </small>
  {{ $paginator->links() }}
</div>

<div class="list-group list-group-flush repo-list">
  @foreach($paginator as $repo)
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
          $langColor = is_array($langColor) ? $langColor['color'] : $langColor
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

<div class="d-flex justify-content-between align-items-center mt-3">
  <small class="text-muted">
    Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}
    dari {{ number_format($paginator->total()) }} repositori
  </small>
  {{ $paginator->links() }}
</div>
@elseif(!isset($error))
<p class="text-muted">
  Tidak ada repositori publik untuk pengguna ini.
</p>
@endif
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('search-form');
  const input = document.getElementById('search-username');
  const clearBtn = document.getElementById('clear-search');

  function toggleClearButton() {
  if (input.value.length > 0) {
  clearBtn.style.display = 'inline-block';
  } else {
  clearBtn.style.display = 'none';
  }
  }

  // Event saat mengetik
  input.addEventListener('input', toggleClearButton);

  // Inisialisasi saat halaman dimuat (jika ada nilai dari query string)
  toggleClearButton();

  // Tombol clear diklik
  clearBtn.addEventListener('click', function() {
  input.value = '';
  toggleClearButton();
  input.focus();
  });
  });
</script>
@endpush