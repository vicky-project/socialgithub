<?php

namespace Modules\SocialGithub\Installations;

use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\Artisan;

class PostInstallation
{
  public function handle(string $moduleName) {
    try {
      $modules = array_merge(["users", "socialaccount"], [$moduleName]);

      foreach ($modules as $modulename) {
        $module = Module::find($modulename);
        $module->enable();
      }

      Artisan::call("migrate");
    } catch (\Exception $e) {
      logger()->error(
        "Failed to run post installation of social google module: " .
        $e->getMessage()
      );

      throw $e;
    }
  }
}