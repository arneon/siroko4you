<?php

namespace Arneon\LaravelProducts\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Arneon\LaravelProducts\Domain\Repositories\Repository;
use Arneon\LaravelProducts\Infrastructure\Persistence\Eloquent\EloquentRepository;
use Arneon\LaravelProducts\Infrastructure\Persistence\Redis\RedisRepository;
use Arneon\LaravelProducts\Application\Console\Commands\ProductInit;

class PackageServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(Repository::class, function ($app) {
            return new EloquentRepository();
        });

        $this->app
            ->when(\Arneon\LaravelProducts\Application\UseCases\FindAllUseCase::class)
            ->needs(Repository::class)
            ->give(RedisRepository::class);

    }
    public function boot()
    {
        if (app()->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');

            $this->commands([
                ProductInit::class,
            ]);
        }

        // Publish the config file
        $this->publishes([
            __DIR__ . '/../../../config/products.php' => config_path('arneon-products.php'),
        ], 'config');
        // Merge default config in case the user does not publish the config
        $this->mergeConfigFrom(
            __DIR__ . '/../../../config/products.php', 'arneon-products'
        );

        $this->loadRoutesFrom(__DIR__.'/../Routes/Api.php');

        $this->publishes([
            __DIR__ . '/../../../tests' => base_path('/tests/laravel-products'),
        ], 'tests');

        $this->loadViewsFrom(__DIR__ . '/../Views', 'arneon/laravel-products');
        $this->publishes([
            __DIR__ . '/../Views' => resource_path('views/vendor/arneon/laravel-products'),
        ], 'views');

        $locale = config('app.locale');
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'laravel-products');

    }
}
