<?php

namespace Arneon\LaravelShoppingCart\Infrastructure\Providers;


use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Arneon\LaravelShoppingCart\Domain\Events\EntityCreated;
use Arneon\LaravelShoppingCart\Infrastructure\Listeners\HandleEntityCreated;
use Arneon\LaravelShoppingCart\Domain\Events\EntityDeleted;
use Arneon\LaravelShoppingCart\Infrastructure\Listeners\HandleEntityDeleted;
use Arneon\LaravelShoppingCart\Domain\Events\EntityUpdated;
use Arneon\LaravelShoppingCart\Infrastructure\Listeners\HandleEntityUpdated;

class EventServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Event::listen(
            EntityCreated::class,
            HandleEntityCreated::class
        );
        Event::listen(
            EntityDeleted::class,
            HandleEntityDeleted::class
        );
        Event::listen(
            EntityUpdated::class,
            HandleEntityUpdated::class
        );

    }
}
