<?php

namespace Arneon\LaravelShoppingCart\Domain\Contracts\UseCases;

interface RemoveItem
{
    public function __invoke(array $request): array;
}
