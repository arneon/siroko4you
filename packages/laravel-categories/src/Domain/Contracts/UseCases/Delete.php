<?php

namespace Arneon\LaravelCategories\Domain\Contracts\UseCases;

interface Delete
{
    public function __invoke(int $id): array;
}
