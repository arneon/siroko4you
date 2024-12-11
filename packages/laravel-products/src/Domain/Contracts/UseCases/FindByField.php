<?php

namespace Arneon\LaravelProducts\Domain\Contracts\UseCases;

interface FindByField
{
    public function __invoke(string $field, mixed $fieldValue): array;
}
