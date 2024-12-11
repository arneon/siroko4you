<?php

namespace Arneon\LaravelShoppingCart\Domain\Events;

class EntityUpdated
{
    public $entity;

    public function __construct($entity)
    {
        $this->entity = $entity;
    }
}
