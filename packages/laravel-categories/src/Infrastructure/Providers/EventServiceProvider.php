<?php

namespace Arneon\LaravelCategories\Infrastructure\Providers;


use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Arneon\LaravelCategories\Domain\Events\EntityCreated;
use Arneon\LaravelCategories\Infrastructure\Listeners\HandleEntityCreated;
use Arneon\LaravelCategories\Domain\Events\EntityDeleted;
use Arneon\LaravelCategories\Infrastructure\Listeners\HandleEntityDeleted;
use Arneon\LaravelCategories\Domain\Events\EntityUpdated;
use Arneon\LaravelCategories\Infrastructure\Listeners\HandleEntityUpdated;

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
