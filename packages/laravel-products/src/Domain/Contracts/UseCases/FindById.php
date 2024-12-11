<?php

namespace Arneon\LaravelProducts\Domain\Contracts\UseCases;

interface FindById
{
    public function __invoke(int $id): array;
}
