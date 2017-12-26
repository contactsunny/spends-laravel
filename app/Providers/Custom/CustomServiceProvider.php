<?php
/**
 * Created by PhpStorm.
 * User: srinidhi
 * Date: 16/2/17
 * Time: 10:53 AM
 */

namespace App\Providers\Custom;

use Illuminate\Support\ServiceProvider;

class CustomServiceProvider extends ServiceProvider
{
    public function register() {
        $this->app->bind('App\Interfaces\FolderRepoInterface', 'App\Repositories\FolderRepository');
        $this->app->bind('App\Interfaces\LinkRepositoryInterface', 'App\Repositories\LinkRepository');
    }
}