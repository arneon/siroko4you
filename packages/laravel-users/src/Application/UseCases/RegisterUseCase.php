<?php

namespace Arneon\LaravelUsers\Application\UseCases;

use Arneon\LaravelUsers\Domain\Contracts\UseCases\Register as UseCaseInterface;
use Arneon\LaravelUsers\Domain\Repositories\Repository;
class RegisterUseCase implements UseCaseInterface
{
    public function __construct(
        private readonly Repository $repository
    ){}

    public function __invoke(array $request): array
    {
        return $this->repository->register($request);
    }
}
