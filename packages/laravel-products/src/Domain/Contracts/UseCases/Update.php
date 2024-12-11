<?php

namespace Arneon\LaravelProducts\Domain\Contracts\UseCases;

interface Update
{
    public function __invoke(int $id, array $request): array;
}
