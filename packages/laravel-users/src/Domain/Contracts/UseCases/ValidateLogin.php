<?php

namespace Arneon\LaravelUsers\Domain\Contracts\UseCases;

interface ValidateLogin
{
    public function __invoke(string $email, string $password);
}
