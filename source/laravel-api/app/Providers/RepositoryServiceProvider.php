<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $aryUserRepository = [
            'UserRepository',
            'UserMetaRepository'
        ];

        foreach ($aryUserRepository as $strUserRepository) {
            $strInterface = "App\Domains\User\Contracts\\{$strUserRepository}";
            $strImpl = "App\Repositories\Mysql\User\\{$strUserRepository}";
            $this->app->bind($strInterface, $strImpl);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
