<?php

namespace Arneon\LaravelUsers\Domain\Contracts\UseCases;

interface FindAll
{
    public function __invoke(): array;
}
