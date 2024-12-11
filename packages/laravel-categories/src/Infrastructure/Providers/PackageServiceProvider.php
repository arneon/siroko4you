<?php

namespace Arneon\LaravelCategories\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Arneon\LaravelCategories\Domain\Repositories\Repository;
use Arneon\LaravelCategories\Infrastructure\Persistence\Eloquent\EloquentRepository;
use Arneon\LaravelCategories\Infrastructure\Persistence\Redis\RedisRepository;
use Arneon\LaravelCategories\Application\Console\Commands\CategoryInit;

class PackageServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(Repository::class, function ($app) {
            return new EloquentRepository();
        });

        $this->app
            ->when(\Arneon\LaravelCategories\Application\UseCases\FindAllUseCase::class)
            ->needs(Repository::class)
            ->give(RedisRepository::class);

    }
    public function boot()
    {
        if (app()->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');

            $this->commands([
                CategoryInit::class,
            ]);
        }

        // Publish the config file
        $this->publishes([
            __DIR__ . '/../../../config/categories.php' => config_path('arneon-categories.php'),
        ], 'config');
        // Merge default config in case the user does not publish the config
        $this->mergeConfigFrom(
            __DIR__ . '/../../../config/categories.php', 'arneon-categories'
        );

        $this->loadRoutesFrom(__DIR__.'/../Routes/Api.php');

        $this->publishes([
            __DIR__ . '/../../../tests' => base_path('/tests/laravel-categories'),
        ], 'tests');

        $this->loadViewsFrom(__DIR__ . '/../Views', 'arneon/laravel-categories');
        $this->publishes([
            __DIR__ . '/../Views' => resource_path('views/vendor/arneon/laravel-categories'),
        ], 'views');

        $locale = config('app.locale');
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'laravel-categories');

    }
}
