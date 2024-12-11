<?php

namespace Arneon\LaravelUsers\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Arneon\LaravelUsers\Domain\Repositories\Repository;
use Arneon\LaravelUsers\Infrastructure\Persistence\Eloquent\EloquentRepository;

class PackageServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(Repository::class, EloquentRepository::class);
    }
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../Routes/Api.php');

        $this->publishes([
            __DIR__ . '/../../../tests' => base_path('/tests/laravel-users'),
        ], 'tests');

        $this->loadViewsFrom(__DIR__ . '/../Views', 'arneon/laravel-users');
        $this->publishes([
            __DIR__ . '/../Views' => resource_path('views/vendor/arneon/laravel-users'),
        ], 'views');
    }
}
