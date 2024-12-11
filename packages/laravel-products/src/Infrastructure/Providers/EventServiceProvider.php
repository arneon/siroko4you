<?php

namespace Arneon\LaravelProducts\Infrastructure\Providers;


use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Arneon\LaravelProducts\Domain\Events\EntityCreated;
use Arneon\LaravelProducts\Infrastructure\Listeners\HandleEntityCreated;
use Arneon\LaravelProducts\Domain\Events\EntityDeleted;
use Arneon\LaravelProducts\Infrastructure\Listeners\HandleEntityDeleted;
use Arneon\LaravelProducts\Domain\Events\EntityUpdated;
use Arneon\LaravelProducts\Infrastructure\Listeners\HandleEntityUpdated;

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
