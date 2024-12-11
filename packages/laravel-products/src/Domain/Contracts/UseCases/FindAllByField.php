<?php

namespace Arneon\LaravelProducts\Domain\Contracts\UseCases;

interface FindAllByField
{
    public function __invoke(string $field, mixed $fieldValue, string $operator = '='): array;
}
