<?php

namespace Arneon\LaravelCategories\Domain\Contracts\UseCases;

interface Update
{
    public function __invoke(int $id, array $request): array;
}
