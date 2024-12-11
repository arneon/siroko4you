<?php

namespace Arneon\LaravelProducts\Domain\Contracts\UseCases;

interface FindAll
{
    public function __invoke(): array;
}
