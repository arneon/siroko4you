<?php

namespace Arneon\LaravelShoppingCart\Domain\Events;

class EntityCreated
{
    public $entity;

    public function __construct($entity)
    {
        $this->entity = $entity;
    }
}
