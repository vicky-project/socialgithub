<?
namespace Modules\SocialGithub\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class GithubController extends Controller
{
  public function index(Request $request) {
    return view('socialgithub::index');
  }
}