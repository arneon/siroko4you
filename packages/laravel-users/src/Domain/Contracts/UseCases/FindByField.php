<?php

namespace Arneon\LaravelUsers\Domain\Contracts\UseCases;

interface FindByField
{
    public function __invoke(string $field, mixed $fieldValue): array;
}
