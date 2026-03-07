<?php
namespace Modules\SocialGithub\Providers;

use Modules\SocialAccount\Interfaces\SocialProvider;
use Modules\SocialAccount\Enums\Provider;
use Modules\SocialGithub\Models\GithubUser;

class GithubProvider implements SocialProvider
{
  public function getName(): string
  {
    return Provider::GITHUB->value;
  }

  public function getLabel(): string
  {
    return Provider::GITHUB->label();
  }

  public function getIcon(): string
  {
    return 'bi bi-github';
  }

  public function getLoginUrl(): string
  {
    return route('social.login', Provider::GITHUB->value);
  }

  public function handleCallback($socialUser): array
  {
    // Cari atau buat record di tabel github_users
    $user = GithubUser::firstOrCreate(
      ['provider_id' => $socialUser->getId()],
      [
        'email' => $socialUser->getEmail(),
        'name' => $socialUser->getName(),
        'nickname' => $socialUser->getNickname(),
        'avatar' => $socialUser->getAvatar(),
        'data' => $socialUser->user,
      ]
    );

    return [
      'providerable_id' => $user->id,
      'providerable_type' => GithubUser::class,
      'provider_data' => [
        'email' => $user->email,
        'name' => $user->name,
        'nickname' => $user->nickname,
        'avatar' => $user->avatar,
      ],
    ];
  }
}