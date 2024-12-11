<?php

namespace Arneon\LaravelShoppingCart\Domain\Contracts\UseCases;

interface AddItem
{
    public function __invoke(array $request): array;
}
