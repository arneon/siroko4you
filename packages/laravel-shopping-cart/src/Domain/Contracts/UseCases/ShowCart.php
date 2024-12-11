<?php

namespace Arneon\LaravelShoppingCart\Domain\Contracts\UseCases;

interface ShowCart
{
    public function __invoke(int $cartId): array;
}
