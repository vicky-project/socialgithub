@extends('socialgithub::layouts.app')

@section('title', 'Repositori GitHub - ' . auth()->user()->name ?? '')

@section('content')
@if(isset($error))
<div class="alert alert-danger">
  {{ $error }}
</div>
@endif

@if(count($repos) > 0)
<div class="row">
  @foreach($repos as $repo)
  <div class="col-md-6 mb-4">
    <div class="card h-100 shadow-sm">
      <div class="card-body">
        {{-- Nama repo dan badge --}}
        <h5 class="card-title d-flex align-items-center flex-wrap">
          <a href="{{ $repo['html_url'] }}" target="_blank" class="text-decoration-none">
            {{ $repo['full_name'] }}
          </a>
          @if($repo['fork'])
          <span class="badge bg-secondary ms-2">Fork</span>
          @endif
          <span class="badge bg-light text-dark ms-2">{{ ucfirst($repo['visibility'] ?? 'public') }}</span>
        </h5>

        {{-- Deskripsi --}}
        <p class="card-text">
          {{ $repo['description'] ?? 'Tidak ada deskripsi' }}
        </p>

        {{-- Topics --}}
        @if(!empty($repo['topics']))
        <div class="mb-2">
          @foreach($repo['topics'] as $topic)
          <span class="badge bg-info text-dark me-1">{{ $topic }}</span>
          @endforeach
        </div>
        @endif

        {{-- Info: bahasa, bintang, fork, issues, lisensi --}}
        <div class="d-flex flex-wrap align-items-center text-muted small mb-2">
          @if($repo['language'])
          <span class="badge bg-primary me-2">{{ $repo['language'] }}</span>
          @endif
          <span class="me-3"><i class="bi bi-star"></i> {{ number_format($repo['stargazers_count']) }}</span>
          <span class="me-3"><i class="bi bi-diagram-2"></i> {{ number_format($repo['forks_count']) }}</span>
          @if($repo['open_issues_count'] > 0)
          <span class="me-3"><i class="bi bi-exclamation-circle"></i> {{ $repo['open_issues_count'] }}</span>
          @endif
          @if(isset($repo['license']) && $repo['license'])
          <span class="me-3"><i class="bi bi-shield-check"></i> {{ $repo['license']['spdx_id'] ?? $repo['license']['name'] }}</span>
          @endif
        </div>

        {{-- Tombol-tombol aksi ke fitur repositori --}}
        <div class="d-flex flex-wrap gap-1 mt-2">
          <a href="{{ $repo['html_url'] }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Kode">
            <i class="bi bi-code-slash"></i> Kode
          </a>
          @if($repo['has_issues'])
          <a href="{{ $repo['html_url'] }}/issues" target="_blank" class="btn btn-sm btn-outline-secondary" title="Issues">
            <i class="bi bi-exclamation-circle"></i> Issues
            @if($repo['open_issues_count'] > 0)
            <span class="badge bg-secondary ms-1">{{ $repo['open_issues_count'] }}</span>
            @endif
          </a>
          @endif
          @if($repo['has_pull_requests'])
          <a href="{{ $repo['html_url'] }}/pulls" target="_blank" class="btn btn-sm btn-outline-secondary" title="Pull Requests">
            <i class="bi bi-git-pull-request"></i> Pull
          </a>
          @endif
          @if($repo['has_wiki'])
          <a href="{{ $repo['html_url'] }}/wiki" target="_blank" class="btn btn-sm btn-outline-secondary" title="Wiki">
            <i class="bi bi-book"></i> Wiki
          </a>
          @endif
          @if($repo['has_projects'])
          <a href="{{ $repo['html_url'] }}/projects" target="_blank" class="btn btn-sm btn-outline-secondary" title="Projects">
            <i class="bi bi-kanban"></i> Projects
          </a>
          @endif
          @if($repo['has_pages'])
          <a href="https://{{ $repo['owner']['login'] }}.github.io/{{ $repo['name'] }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="GitHub Pages">
            <i class="bi bi-globe2"></i> Pages
          </a>
          @endif
        </div>

        {{-- Waktu update & branch default --}}
        <div class="text-muted small mt-2">
          <i class="bi bi-clock"></i> Diperbarui {{ \Carbon\Carbon::parse($repo['updated_at'])->diffForHumans() }}
          @if(isset($repo['default_branch']))
          · <i class="bi bi-git-branch"></i> {{ $repo['default_branch'] }}
          @endif
        </div>
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