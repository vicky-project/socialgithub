<?php
namespace Modules\SocialGithub\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\SocialAccount\Interfaces\SocialAccountInterface;
use Modules\SocialAccount\Models\SocialAccount;

class GithubUser extends Model implements SocialAccountInterface
{
  protected $table = 'github_users';
  protected $fillable = ['provider_id',
    'email',
    'name',
    'nickname',
    'avatar',
    'data'];
  protected $casts = ['data' => 'array'];

  public function provider(): MorphOne {
    return $this->morphOne(SocialAccount::class, "providerable");
  }
}