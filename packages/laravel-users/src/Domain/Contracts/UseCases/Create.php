<?php

namespace Arneon\LaravelUsers\Domain\Contracts\UseCases;

interface Create
{
    public function __invoke(array $request): array;
}
