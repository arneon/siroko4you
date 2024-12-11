<?php

namespace Arneon\LaravelShoppingCart\Application\UseCases;

use Arneon\LaravelShoppingCart\Domain\Contracts\UseCases\FindAll;
use Arneon\LaravelShoppingCart\Domain\Repositories\Repository as RepositoryInterface;

class FindAllUseCase implements FindAll
{
    public function __construct(
        private readonly RepositoryInterface $repository,
    )
    {
    }
    public function __invoke(): array
    {
        return $this->repository->findAll();
    }


}
