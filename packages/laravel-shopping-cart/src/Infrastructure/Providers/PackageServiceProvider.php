<?php

namespace Arneon\LaravelShoppingCart\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Arneon\LaravelShoppingCart\Domain\Repositories\Repository;
use Arneon\LaravelShoppingCart\Infrastructure\Persistence\Eloquent\EloquentRepository;
use Arneon\LaravelShoppingCart\Application\Console\Commands\ShoppingCartInit as Command;

class PackageServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(Repository::class, function ($app) {
            return new EloquentRepository();
        });
    }
    public function boot()
    {
        if (app()->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');

            $this->commands([
                Command::class,
            ]);
        }

        // Publish the config file
        $this->publishes([
            __DIR__ . '/../../../config/shopping_carts.php' => config_path('arneon-shopping_carts.php'),
        ], 'config');
        // Merge default config in case the user does not publish the config
        $this->mergeConfigFrom(
            __DIR__ . '/../../../config/shopping_carts.php', 'arneon-shopping_carts'
        );

        $this->loadRoutesFrom(__DIR__.'/../Routes/Api.php');

        $this->publishes([
            __DIR__ . '/../../../tests' => base_path('/tests/laravel-shopping_cart'),
        ], 'tests');

        $this->loadViewsFrom(__DIR__ . '/../Views', 'arneon/laravel-shopping_cart');
        $this->publishes([
            __DIR__ . '/../Views' => resource_path('views/vendor/arneon/laravel-shopping_cart'),
        ], 'views');

        $locale = config('app.locale');
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'laravel-shopping_cart');

    }
}
