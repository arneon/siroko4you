<?php

namespace Arneon\LaravelCategories\Domain\Events;

class EntityUpdated
{
    public $entity;

    public function __construct($entity)
    {
        $this->entity = $entity;
    }
}
