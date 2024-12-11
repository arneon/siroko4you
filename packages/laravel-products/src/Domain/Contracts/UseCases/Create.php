<?php

namespace Arneon\LaravelProducts\Domain\Contracts\UseCases;

interface Create
{
    public function __invoke(array $request): array;
}
