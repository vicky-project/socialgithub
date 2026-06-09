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
<form action="{{ route('github.index') }}" method="GET" class="mb-3" id="search-form">
  <div class="input-group">
    <span class="input-group-text"><i class="bi bi-github"></i></span>
    <input type="search" name="username" id="search-username" class="form-control"
    placeholder="Cari username GitHub..."
    value="{{ $username ?? '' }}">
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

        {{-- Meta informasi yang clickable --}}
        <div class="repo-meta d-flex flex-wrap align-items-center">
          @if($repo['language'])
          @php
          $langColor = $languageColors[$repo['language']] ?? '#ccc';
          $langColor = is_array($langColor) ? $langColor['color'] : $langColor;
          @endphp
          <a href="{{ $repo['languages_url'] }}" target="_blank"
            class="text-decoration-none me-3 d-inline-flex align-items-center text-muted">
            <span class="language-dot" style="background-color: {{ $langColor }};"></span>
            {{ $repo['language'] }}
          </a>
          @endif

          <a href="{{ $repo['stargazers_url'] }}" target="_blank"
            class="text-decoration-none me-3 text-muted">
            <i class="bi bi-star"></i> {{ number_format($repo['stargazers_count']) }}
          </a>

          <a href="{{ $repo['forks_url'] }}" target="_blank"
            class="text-decoration-none me-3 text-muted">
            <i class="bi bi-diagram-2"></i> {{ number_format($repo['forks_count']) }}
          </a>

          @if(isset($repo['license']) && $repo['license'])
          @php
          $licenseUrl = $repo['license']['url'] ?? $repo['html_url'] . '/blob/main/LICENSE';
          @endphp
          <a href="{{ $licenseUrl }}" target="_blank"
            class="text-decoration-none me-3 text-muted">
            <i class="bi bi-shield-check"></i>
            {{ $repo['license']['spdx_id'] ?? $repo['license']['name'] }}
          </a>
          @endif

          <a href="{{ $repo['issues_url'] ?? $repo['html_url'] . '/issues' }}" target="_blank"
            class="text-decoration-none me-3 text-muted">
            <i class="bi bi-exclamation-circle"></i> {{ $repo['open_issues_count'] }}
          </a>

          <a href="{{ $repo['tags_url'] }}" target="_blank"
            class="text-decoration-none me-3 text-muted">
            <i class="bi bi-tag"></i> Tags
          </a>

          @php
          $archiveUrl = str_replace(
          ['{archive_format}', '{/ref}'],
          ['zipball', '/' . ($repo['default_branch'] ?? 'main')],
          $repo['archive_url']
          );
          @endphp
          <a href="{{ $archiveUrl }}" target="_blank"
            class="text-decoration-none me-3 text-muted">
            <i class="bi bi-download"></i> Download
          </a>

          <a href="{{ $repo['subscribers_url'] ?? $repo['html_url'] . '/watchers' }}" target="_blank"
            class="text-decoration-none me-3 text-muted">
            <i class="bi bi-eye"></i> {{ number_format($repo['watchers_count']) }}
          </a>

          <span class="text-muted">
            <i class="bi bi-clock"></i> Diperbarui {{ \Carbon\Carbon::parse($repo['updated_at'])->diffForHumans() }}
          </span>
        </div>
      </div>

      <div class="ms-3 d-flex gap-1">
        {{-- Tombol Info (Detail Lengkap) --}}
        <button class="btn btn-sm repo-github-link" type="button"
          data-bs-toggle="collapse" data-bs-target="#detail-{{ $repo['id'] }}"
          aria-expanded="false" title="Lihat Detail Lengkap">
          <i class="bi bi-info-circle"></i>
        </button>
        {{-- Tombol GitHub --}}
        <a href="{{ $repo['html_url'] }}" target="_blank" class="btn btn-sm repo-github-link" title="Lihat di GitHub">
          <i class="bi bi-github"></i>
        </a>
      </div>
    </div>

    {{-- Panel Detail Lengkap (Collapse) --}}
    <div class="collapse mt-3" id="detail-{{ $repo['id'] }}">
      <div class="card card-body bg-light small">
        <div class="row">
          <div class="col-md-6">
            <p class="mb-1">
              <strong>Full Name:</strong> {{ $repo['full_name'] }}
            </p>
            <p class="mb-1">
              <strong>ID:</strong> {{ $repo['id'] }}
            </p>
            <p class="mb-1">
              <strong>Node ID:</strong> {{ $repo['node_id'] }}
            </p>
            <p class="mb-1">
              <strong>Default Branch:</strong> {{ $repo['default_branch'] ?? 'N/A' }}
            </p>
            <p class="mb-1">
              <strong>Size:</strong> {{ number_format($repo['size']) }} KB
            </p>
            <p class="mb-1">
              <strong>Homepage:</strong>
              @if($repo['homepage'])
              <a href="{{ $repo['homepage'] }}" target="_blank">{{ $repo['homepage'] }}</a>
              @else
              N/A
              @endif
            </p>
            <p class="mb-1">
              <strong>Created:</strong> {{ \Carbon\Carbon::parse($repo['created_at'])->format('d M Y') }}
            </p>
            <p class="mb-1">
              <strong>Pushed:</strong> {{ \Carbon\Carbon::parse($repo['pushed_at'])->diffForHumans() }}
            </p>
            <p class="mb-1">
              <strong>Updated:</strong> {{ \Carbon\Carbon::parse($repo['updated_at'])->diffForHumans() }}
            </p>
          </div>
          <div class="col-md-6">
            {{-- Clone URLs dengan Mini Tabs --}}
            <p class="mb-1">
              <strong>Clone URLs:</strong>
            </p>
            <ul class="nav nav-tabs small mb-2" id="cloneTabs-{{ $repo['id'] }}" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="https-tab-{{ $repo['id'] }}" data-bs-toggle="tab" data-bs-target="#https-{{ $repo['id'] }}" type="button" role="tab" aria-controls="https-{{ $repo['id'] }}" aria-selected="true">HTTPS</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="ssh-tab-{{ $repo['id'] }}" data-bs-toggle="tab" data-bs-target="#ssh-{{ $repo['id'] }}" type="button" role="tab" aria-controls="ssh-{{ $repo['id'] }}" aria-selected="false">SSH</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="git-tab-{{ $repo['id'] }}" data-bs-toggle="tab" data-bs-target="#git-{{ $repo['id'] }}" type="button" role="tab" aria-controls="git-{{ $repo['id'] }}" aria-selected="false">Git</button>
              </li>
              @if($repo['svn_url'])
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="svn-tab-{{ $repo['id'] }}" data-bs-toggle="tab" data-bs-target="#svn-{{ $repo['id'] }}" type="button" role="tab" aria-controls="svn-{{ $repo['id'] }}" aria-selected="false">SVN</button>
              </li>
              @endif
            </ul>
            <div class="tab-content small mb-2" id="cloneContent-{{ $repo['id'] }}">
              <div class="tab-pane fade show active" id="https-{{ $repo['id'] }}" role="tabpanel" aria-labelledby="https-tab-{{ $repo['id'] }}">
                <div class="input-group input-group-sm">
                  <input type="text" class="form-control" value="{{ $repo['clone_url'] }}" id="https-url-{{ $repo['id'] }}" readonly>
                  <button class="btn btn-outline-secondary" type="button" onclick="copyUrl('https-url-{{ $repo['id'] }}')"><i class="bi bi-clipboard"></i></button>
                </div>
              </div>
              <div class="tab-pane fade" id="ssh-{{ $repo['id'] }}" role="tabpanel" aria-labelledby="ssh-tab-{{ $repo['id'] }}">
                <div class="input-group input-group-sm">
                  <input type="text" class="form-control" value="{{ $repo['ssh_url'] }}" id="ssh-url-{{ $repo['id'] }}" readonly>
                  <button class="btn btn-outline-secondary" type="button" onclick="copyUrl('ssh-url-{{ $repo['id'] }}')"><i class="bi bi-clipboard"></i></button>
                </div>
              </div>
              <div class="tab-pane fade" id="git-{{ $repo['id'] }}" role="tabpanel" aria-labelledby="git-tab-{{ $repo['id'] }}">
                <div class="input-group input-group-sm">
                  <input type="text" class="form-control" value="{{ $repo['git_url'] }}" id="git-url-{{ $repo['id'] }}" readonly>
                  <button class="btn btn-outline-secondary" type="button" onclick="copyUrl('git-url-{{ $repo['id'] }}')"><i class="bi bi-clipboard"></i></button>
                </div>
              </div>
              @if($repo['svn_url'])
              <div class="tab-pane fade" id="svn-{{ $repo['id'] }}" role="tabpanel" aria-labelledby="svn-tab-{{ $repo['id'] }}">
                <div class="input-group input-group-sm">
                  <input type="text" class="form-control" value="{{ $repo['svn_url'] }}" id="svn-url-{{ $repo['id'] }}" readonly>
                  <button class="btn btn-outline-secondary" type="button" onclick="copyUrl('svn-url-{{ $repo['id'] }}')"><i class="bi bi-clipboard"></i></button>
                </div>
              </div>
              @endif
            </div>

            <p class="mb-1">
              <strong>Features:</strong><br>
              @if($repo['has_issues']) <span class="badge bg-success">Issues</span> @else <span class="badge bg-secondary">No Issues</span> @endif
              @if($repo['has_projects']) <span class="badge bg-success">Projects</span> @else <span class="badge bg-secondary">No Projects</span> @endif
              @if($repo['has_wiki']) <span class="badge bg-success">Wiki</span> @else <span class="badge bg-secondary">No Wiki</span> @endif
              @if($repo['has_pages']) <span class="badge bg-success">Pages</span> @else <span class="badge bg-secondary">No Pages</span> @endif
              @if($repo['has_discussions']) <span class="badge bg-success">Discussions</span> @else <span class="badge bg-secondary">No Discussions</span> @endif
            </p>

            <p class="mb-1">
              <strong>State:</strong>
              @if($repo['archived']) <span class="badge bg-warning">Archived</span> @endif
              @if($repo['disabled']) <span class="badge bg-danger">Disabled</span> @endif
              @if($repo['is_template']) <span class="badge bg-info">Template</span> @endif
              @if($repo['fork']) <span class="badge bg-info">Forked</span> @endif
            </p>

            <p class="mb-1">
              <strong>Allow Forking:</strong> {{ $repo['allow_forking'] ? 'Yes' : 'No' }}
            </p>
            <p class="mb-1">
              <strong>Web Commit Signoff:</strong> {{ $repo['web_commit_signoff_required'] ? 'Required' : 'Not Required' }}
            </p>

            @if($repo['mirror_url'])
            <p class="mb-1">
              <strong>Mirror URL:</strong> <a href="{{ $repo['mirror_url'] }}" target="_blank">{{ $repo['mirror_url'] }}</a>
            </p>
            @endif
          </div>
        </div>
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
  function copyUrl(id) {
    const input = document.getElementById(id);
    input.select();
    input.setSelectionRange(0, 99999); // untuk mobile
    navigator.clipboard.writeText(input.value).then(() => {
    // opsional: ubah tombol jadi ikon centang sesaat
    const btn = input.parentElement.querySelector('button');
    const icon = btn.querySelector('i');
    const originalClass = icon.className;
    icon.className = 'bi bi-check';
    setTimeout(() => {
    icon.className = originalClass;
    }, 2000);
    }).catch(err => {
    console.error('Gagal menyalin:', err);
    });
  }
</script>
@endpush