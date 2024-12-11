<?php

namespace Arneon\LaravelProducts\Domain\Contracts\UseCases;

interface Delete
{
    public function __invoke(int $id): array;
}
