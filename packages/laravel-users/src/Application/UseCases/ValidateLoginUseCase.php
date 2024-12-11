<?php

namespace Arneon\LaravelUsers\Application\UseCases;

use Arneon\LaravelUsers\Domain\Contracts\UseCases\ValidateLogin;
use Arneon\LaravelUsers\Domain\Repositories\Repository as RepositoryInterface;

class ValidateLoginUseCase implements ValidateLogin
{
    public function __construct(
        private readonly RepositoryInterface $repository,
    )
    {
    }
    public function __invoke(string $email, string $password)
    {
        return $this->repository->validateLogin($email, $password);
    }


}
