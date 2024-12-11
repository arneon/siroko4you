<?php

namespace Arneon\LaravelUsers\Domain\Contracts\UseCases;

interface Register
{
    public function __invoke(array $request): array;
}
