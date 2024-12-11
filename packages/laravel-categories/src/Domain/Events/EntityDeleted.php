<?php

namespace Arneon\LaravelCategories\Domain\Events;

class EntityDeleted
{
    public $entity;

    public function __construct($entity)
    {
        $this->entity = $entity;
    }
}
