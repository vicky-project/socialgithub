@extends('socialgithub::layouts.github')

@section('title', 'Repositori GitHub - ' . ($githubUser->nickname ?? ''))

@section('content')
@if(isset($error))
<div class="alert alert-danger">
  {{ $error }}
</div>
@endif

@if(count($repos) > 0)
<div class="list-group list-group-flush">
  @foreach($repos as $repo)
  <div class="list-group-item px-0 py-3 border-bottom">
    <div class="d-flex justify-content-between align-items-start">
      {{-- Informasi utama --}}
      <div class="flex-grow-1">
        <h5 class="mb-1 fw-bold">
          <a href="{{ $repo['html_url'] }}" target="_blank" class="text-decoration-none">
            {{ $repo['name'] }}
          </a>
          <span class="badge bg-secondary ms-2 align-middle">
            {{ ucfirst($repo['visibility'] ?? 'public') }}
          </span>
          @if($repo['fork'])
          <small class="text-muted ms-1">(forked)</small>
          @endif
        </h5>

        <p class="mb-1 text-muted">
          {{ $repo['description'] ?? '' }}
        </p>

        @if(!empty($repo['topics']))
        <div class="mb-1">
          @foreach($repo['topics'] as $topic)
          <a href="#" class="badge bg-light text-dark text-decoration-none me-1">
            {{ $topic }}
          </a>
          @endforeach
        </div>
        @endif

        <div class="d-flex flex-wrap align-items-center text-muted small mt-2">
          @if($repo['language'])
          @php
          $langColor = $languageColors[$repo['language']] ?? '#ccc';
          @endphp
          <span class="d-inline-flex align-items-center me-3">
            <span class="d-inline-block rounded-circle me-1"
              style="width: 12px; height: 12px; background-color: {{ $langColor }};"></span>
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

      {{-- Tombol arahkan ke GitHub --}}
      <div class="ms-3">
        <a href="{{ $repo['html_url'] }}" target="_blank"
          class="btn btn-sm btn-outline-secondary"
          title="Lihat di GitHub">
          <i class="bi bi-github"></i>
        </a>
      </div>
    </div>
  </div>
  @endforeach
</div>
@elseif(!isset($error))
<p>
  Tidak ada repositori publik.
</p>
@endif
@endsection