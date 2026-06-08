<?php

namespace Modules\SocialGithub\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Modules\SocialGithub\Models\GithubUser;

class GithubRepoController extends Controller
{
  public function index(Request $request) {
    // Ambil user Github yang terhubung dengan user saat ini
    // Asumsi: SocialAccount punya kolom 'user_id' dan morphTo 'providerable'
    $user = $request->user();
    $githubUser = GithubUser::whereHas('provider', function ($query) use ($user) {
      $query->where('user_id', $user->id);
    })->first();

    if (!$githubUser) {
      abort(404, 'Akun GitHub belum terhubung.');
    }

    $nickname = $githubUser->nickname; // atau $githubUser->data['login']
    $repos = [];

    // Panggil GitHub API
    $response = Http::get("https://api.github.com/users/{$nickname}/repos", [
      'sort' => 'updated', // urutkan berdasarkan update terbaru
      'per_page' => 100, // maks 100 per halaman
    ]);

    if ($response->successful()) {
      $repos = $response->json();
    } else {
      // Handle error: bisa lempar exception atau return view dengan pesan
      $status = $response->status();
      $message = $response->json()['message'] ?? 'Gagal mengambil data repositori.';
      return view('socialgithub::repos', [
        'repos' => [],
        'error' => "Error {$status}: {$message}"
      ]);
    }

    return view('socialgithub::repos', compact('repos', 'githubUser'));
  }
}