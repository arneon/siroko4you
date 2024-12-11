<?php

namespace Arneon\LaravelCategories\Domain\Contracts\UseCases;

interface Create
{
    public function __invoke(array $request): array;
}
