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
        $this->bindUser();
        $this->bindPost();
    }

    private function bindUser(): void
    {
        $repository = [
            'UserRepository',
            'UserMetaRepository',
        ];
        $this->bindRepository($repository, "User");
    }

    private function bindPost(): void
    {
        $repository = [
            'PostMetaRepository',
            'PostRepository',
            'SlideRepository'
        ];

        $this->bindRepository($repository, "Post");
    }

    private function bindRepository(array $repository, string $domain): void
    {
        foreach ($repository as $repo) {
            $bindInterface = "App\Domains\\$domain\\Contracts\\$repo";
            $bindImpl = "App\Repositories\Mysql\\$domain\\$repo";
            $this->app->bind($bindInterface, $bindImpl);
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
