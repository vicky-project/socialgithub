@extends('socialgithub::layouts.app')

@section('title', 'Repositori GitHub - ' . ($githubUser->nickname ?? ''))

@section('content')
@if(isset($error))
<div class="alert alert-danger">
  {{ $error }}
</div>
@endif

@if(count($repos) > 0)
<div class="list-group list-group-flush repo-list">
  @foreach($repos as $repo)
  <div class="list-group-item px-0 py-3">
    <div class="d-flex justify-content-between align-items-start">
      <div class="flex-grow-1">
        {{-- Nama repo dan badge --}}
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

        {{-- Deskripsi --}}
        @if($repo['description'])
        <p class="repo-description">
          {{ $repo['description'] }}
        </p>
        @endif

        {{-- Topics --}}
        @if(!empty($repo['topics']))
        <div class="mb-1">
          @foreach($repo['topics'] as $topic)
          <span class="badge bg-light text-primary border me-1">
            {{ $topic }}
          </span>
          @endforeach
        </div>
        @endif

        {{-- Meta: bahasa, bintang, fork, lisensi, waktu --}}
        <div class="repo-meta d-flex flex-wrap align-items-center">
          @if($repo['language'])
          @php
          $langColor = $languageColors[$repo['language']] ?? '#ccc';
          $langColor = isset($langColor['color']) ? $langColor['color'] : $langColor;
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

      {{-- Tombol GitHub --}}
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
<p class="text-muted">
  Tidak ada repositori publik.
</p>
@endif
@endsection