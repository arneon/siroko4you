<?php

namespace Arneon\LaravelCategories\Domain\Events;

class EntityCreated
{
    public $entity;

    public function __construct($entity)
    {
        $this->entity = $entity;
    }
}
