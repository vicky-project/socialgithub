<?

use Illuminate\Support\Facades\Route;
use Modules\SocialGithub\Http\Controllers\GithubRepoController;

Route::group([
  'middleware' => ['auth', 'web'],
  'prefix' => 'github',
  'as' => 'github.'
], function() {
  Route::get('', [GithubRepoController::class, 'index'])->name('index');
});