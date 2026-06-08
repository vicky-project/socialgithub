<?php

namespace Modules\SocialGithub\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Modules\SocialGithub\Models\GithubUser;

class GithubRepoController extends Controller
{
  public function index(Request $request) {
    $searchUsername = $request->input('username', '');
    $page = (int) $request->input('page', 1);
    $perPage = 30; // default GitHub

    if (empty($searchUsername)) {
      $user = $request->user();
      $githubUser = GithubUser::whereHas('provider', function ($query) use($user) {
        $query->where('user_id', $user->id);
      })->first();

      if (!$githubUser) {
        abort(404, 'Akun GitHub belum terhubung.');
      }

      $username = $githubUser->nickname;
      $isOwnProfile = true;
    } else {
      $username = preg_replace('/[^a-zA-Z0-9\-]/', '', $searchUsername);
      $isOwnProfile = false;
    }

    // 1. Dapatkan data user GitHub (untuk total repo & info lainnya)
    $userResponse = Http::get("https://api.github.com/users/{$username}");

    $totalPublicRepos = 0;
    $userName = null;
    $userAvatar = null;

    if ($userResponse->successful()) {
      $userData = $userResponse->json();
      $totalPublicRepos = $userData['public_repos'] ?? 0;
      $userName = $userData['name'] ?? $username;
      $userAvatar = $userData['avatar_url'] ?? null;
    } elseif ($userResponse->status() == 404) {
      $error = "Pengguna '{$username}' tidak ditemukan.";
      // tidak fetch repos jika user tidak ada
      $repos = [];
      return view('socialgithub::repos', compact(
        'repos', 'error', 'username', 'isOwnProfile', 'totalPublicRepos',
        'userName', 'userAvatar', 'page', 'perPage'
      ));
    } else {
      $error = "Gagal mengambil data pengguna GitHub.";
      $repos = [];
      return view('socialgithub::repos', compact(
        'repos', 'error', 'username', 'isOwnProfile', 'totalPublicRepos',
        'userName', 'userAvatar', 'page', 'perPage'
      ));
    }

    // 2. Ambil repositori dengan pagination
    $repos = [];
    $error = null;

    $response = Http::get("https://api.github.com/users/{$username}/repos", [
      'sort' => 'updated',
      'per_page' => $perPage,
      'page' => $page,
    ]);

    if ($response->successful()) {
      $repos = $response->json();

      // Buat LengthAwarePaginator manual
      $paginator = new LengthAwarePaginator(
        $repos,
        $totalPublicRepos,
        $perPage,
        $page,
        [
          'path' => route('github.index'),
          'query' => ['username' => $username],
        ]
      );
    } else {
      $status = $response->status();
      $message = $response->json()['message'] ?? 'Gagal mengambil data repositori.';
      $error = "Error {$status}: {$message}";
      $paginator = new LengthAwarePaginator([], 0, $perPage, 1);
    }

    // Warna bahasa pemrograman
    $languageColors = [];
    $colorFile = module_path('SocialGithub', 'Data/github-colors.json');
    if (file_exists($colorFile)) {
      $languageColors = json_decode(file_get_contents($colorFile), true);
    } else {
      $languageColors = [
        'JavaScript' => '#f1e05a',
        'TypeScript' => '#2b7489',
        'Python' => '#3572A5',
        'Java' => '#b07219',
        'Ruby' => '#701516',
        'PHP' => '#4F5D95',
        'C++' => '#f34b7d',
        'C' => '#555555',
        'HTML' => '#e34c26',
        'CSS' => '#563d7c',
        'Go' => '#00ADD8',
        'Rust' => '#dea584',
        'Swift' => '#ffac45',
        'Kotlin' => '#F18E33',
        'Dart' => '#00B4AB',
        'Shell' => '#89e051',
      ];
    }

    $title = "Repositories - {$username}";

    return view('socialgithub::repos', compact(
      'paginator', 'repos', 'error', 'username', 'isOwnProfile',
      'totalPublicRepos', 'userName', 'userAvatar', 'page', 'perPage',
      'languageColors', 'title'
    ));
  }
}