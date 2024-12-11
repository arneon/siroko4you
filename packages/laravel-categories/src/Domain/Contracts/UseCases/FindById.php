<?php

namespace Arneon\LaravelCategories\Domain\Contracts\UseCases;

interface FindById
{
    public function __invoke(int $id): array;
}
