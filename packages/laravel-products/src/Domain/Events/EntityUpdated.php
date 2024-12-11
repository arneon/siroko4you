<?php

namespace Arneon\LaravelProducts\Domain\Events;

class EntityUpdated
{
    public $entity;

    public function __construct($entity)
    {
        $this->entity = $entity;
    }
}
