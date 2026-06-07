<?

use Illuminate\Support\Facades\Route;
use Modules\SocialGithub\Http\Controllers\GithubController;

Route::group([
  'middleware' => ['auth', 'web'],
  'prefix' => 'github',
  'as' => 'github.'
], function() {
  Route::get('', [GithubController::class, 'index'])->name('index');
});