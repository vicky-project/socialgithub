<?php

namespace Modules\SocialGithub\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Modules\SocialGithub\Models\GithubUser;

class GithubRepoController extends Controller
{
  public function index(Request $request) {
    $searchUsername = $request->input('username', '');

    // Jika tidak ada input username, gunakan akun GitHub user yang login
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
      // Hanya izinkan karakter alfanumerik dan dash
      $username = preg_replace('/[^a-zA-Z0-9\-]/', '', $searchUsername);
      $isOwnProfile = false;
    }

    $repos = [];
    $error = null;

    // Panggil GitHub API
    $response = Http::get("https://api.github.com/users/{$username}/repos", [
      'sort' => 'updated',
      'per_page' => 100,
    ]);

    if ($response->successful()) {
      $repos = $response->json();
    } else {
      $status = $response->status();
      $message = $response->json()['message'] ?? 'Gagal mengambil data repositori.';
      $error = "Error {$status}: {$message}";
    }

    // Warna bahasa pemrograman
    $languageColors = [];
    $colorFile = module_path('SocialGithub', 'Data/github-colors.json');
    if (file_exists($colorFile)) {
      $languageColors = json_decode(file_get_contents($colorFile), true);
    } else {
      // Fallback warna populer (seperti sebelumnya)
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
        'Vue' => '#2c3e50',
        'Jupyter Notebook' => '#DA5B0B',
      ];
    }

    $title = "Repositories - {$username}";

    return view('socialgithub::repos', compact(
      'repos', 'error', 'username', 'isOwnProfile', 'title', 'languageColors'
    ));
  }
}