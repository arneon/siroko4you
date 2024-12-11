<?php

namespace Arneon\LaravelCategories\Domain\Contracts\UseCases;

interface FindByField
{
    public function __invoke(string $field, mixed $fieldValue): array;
}
