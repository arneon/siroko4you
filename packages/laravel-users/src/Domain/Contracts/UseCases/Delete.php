<?php

namespace Arneon\LaravelUsers\Domain\Contracts\UseCases;

interface Delete
{
    public function __invoke(int $id): array;
}
