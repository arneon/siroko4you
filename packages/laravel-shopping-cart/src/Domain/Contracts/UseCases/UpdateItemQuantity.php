<?php

namespace Arneon\LaravelShoppingCart\Domain\Contracts\UseCases;

interface UpdateItemQuantity
{
    public function __invoke(array $request): array;
}
